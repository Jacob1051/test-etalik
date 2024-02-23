<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;

class ImportExcelService
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(protected EntityManagerInterface $entityManager)
    {
    }

    /**
     * @param $excelFile
     * @param $dir
     * @param $columns
     * @return array
     * @throws Exception
     */
    public function import($excelFile, $dir, $columns = null): array
    {
        ini_set('max_execution_time', 0);

        $originalName = $excelFile->getClientOriginalName();
        $pathPart     = pathinfo($originalName);
        $extension    = $pathPart['extension'];

        if ($extension !== 'xlsx' && $extension !== 'xls' && $extension !== 'xlsm')
            return [
                'status'  => 'error',
                'message' => 'Le format de ce fichier est invalide, veuillez importer des fichiers Excel!'
            ];

        $filenameExcel = md5(uniqid()) . '.' . $extension;

        $excelFile->move($dir, $filenameExcel);

        $filename = $dir . $filenameExcel;

        if ('xlsx' === $extension || 'xlsm' === $extension) {
            $reader = new Xlsx();
        } else {
            $reader = new Xls();
        }

        $reader->setReadDataOnly(true);

        $loader     = $reader->load($filename);

        if(!$columns)
            $columns = 'A1:'.$loader->getSheet(0)->getHighestColumn().'1';
        $headers    = $loader->getSheet(0)->rangeToArray($columns);// retest

        $headers    = isset($headers[0]) && is_array($headers[0]) ? $headers[0] : [];

        @unlink($filename);

        return [
            'header' => $headers,
            'loader' => $loader
        ];
    }

    /**
     * @param $loader
     * @param $sheetNum
     * @return array
     */
    #[ArrayShape(['sheet_name' => 'mixed', 'data' => 'mixed'])]
    public function getArrayDataBySheet($loader, $sheetNum): array
    {
        $activeSheet = $loader->getSheet($sheetNum);
        $sheet       = $activeSheet->toArray();
        $dataBody    = array_filter($sheet);

        array_shift($dataBody);

        // remove null array value
        return array_filter($dataBody, function ($value) {
            if (!empty(array_filter($value)))
                return $value;
        });
    }
}
<?php

namespace App\Service;

use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\Exception\ReaderNotOpenedException;
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
     * @throws UnsupportedTypeException
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws ReaderNotOpenedException
     */
    public function import($excelFile, $dir, $columns = null)
    {
        ini_set('max_execution_time', 0);

        $originalName = $excelFile->getClientOriginalName();
        $pathPart     = pathinfo($originalName);
        $extension    = $pathPart['extension'];

        if (!in_array($extension, ['xlsx', 'xls', 'xlsm']))
            return [
                'status'  => 'error',
                'message' => 'Le format de ce fichier est invalide, veuillez importer des fichiers Excel!'
            ];

        $filenameExcel = md5(uniqid()) . '.' . $extension;

        $excelFile->move($dir, $filenameExcel);

        $filename = $dir . $filenameExcel;

        $reader = ReaderEntityFactory::createReaderFromFile($filename);

        $reader->open($filename);

        $headers = [];

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                if ($rowIndex === 1) {
                    foreach ($row->getCells() as $cell) {
                        $headers[] = $cell->getValue();
                    }
                    break 2;
                }
            }
        }

//        if ('xlsx' === $extension || 'xlsm' === $extension) {
//            $reader = new Xlsx();
//        } else {
//            $reader = new Xls();
//        }
//
//        $reader->setReadDataOnly(true);
//
//        $loader     = $reader->load($filename);
//
//        if(!$columns)
//            $columns = 'A1:'.$loader->getSheet(0)->getHighestColumn().'1';
//        $headers    = $loader->getSheet(0)->rangeToArray($columns);// retest
//
//        $headers    = isset($headers[0]) && is_array($headers[0]) ? $headers[0] : [];
//
        @unlink($filename);

        return [ $headers, $reader ];
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
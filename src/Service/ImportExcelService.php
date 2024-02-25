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
     * Standardized excel importation method.
     * Return an array containing the headers(titles) and the reader(excel data).
     * @param $excelFile
     * @param $dir
     * @param $columns
     * @return array
     * @throws UnsupportedTypeException
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws ReaderNotOpenedException
     * @throws \Exception
     */
    public function import($excelFile, $dir): array
    {
        ini_set('max_execution_time', 0);

        $originalName = $excelFile->getClientOriginalName();
        $pathPart     = pathinfo($originalName);
        $extension    = $pathPart['extension'];

        if (!in_array($extension, ['xlsx', 'xls', 'xlsm']))
            throw new \Exception('Le format de ce fichier est invalide, veuillez importer des fichiers Excel!');

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

        return [ $headers, $reader ];
    }
}

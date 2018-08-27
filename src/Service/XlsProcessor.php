<?php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Roromix\Bundle\SpreadsheetBundle\Factory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Component\Filesystem\Filesystem;

class XlsProcessor
{
    /**
     * @var Factory
     */
    private $factory;

    public function __construct()
    {
        $this->factory = new Factory();
    }

    public function getSpreadSheet(String $path): Spreadsheet
    {
        return $this->factory->createSpreadsheet($path);
    }

    public function createSpreadSheet()
    {
        return $this->factory->createSpreadsheet();
    }

    /**
     * @param Spreadsheet $spreadsheet
     * @param String $path
     * @param String $fileName
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function save(Spreadsheet $spreadsheet, String $path, String $fileName)
    {
        $fileSystem = new Filesystem();
        if (!$fileSystem->exists($path )) {
            $fileSystem->mkdir($path );  //  0777 permissions by default
        }

        $writer = $this->factory->createWriter($spreadsheet, 'Xls');
        $writer->save($path  . '/' . $fileName);
    }
}

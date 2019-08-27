<?php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use Roromix\Bundle\SpreadsheetBundle\Factory;
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

    /**
     * @return Spreadsheet
     */
    public function createSpreadSheet(): Spreadsheet
    {
        return $this->factory->createSpreadsheet();
    }

    /**
     * @param Spreadsheet $spreadsheet
     * @param String $path
     * @param String $fileName
     * @throws Exception
     */
    public function save(Spreadsheet $spreadsheet, String $path, String $fileName): void
    {
        $fileSystem = new Filesystem();
        if (!$fileSystem->exists($path )) {
            $fileSystem->mkdir($path );  //  0777 permissions by default
        }

        $writer = $this->factory->createWriter($spreadsheet, 'Xls');
        $writer->save($path  . '/' . $fileName);
    }
}

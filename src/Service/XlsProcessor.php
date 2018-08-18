<?php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Roromix\Bundle\SpreadsheetBundle\Factory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Component\Filesystem\Filesystem;

class XlsProcessor
{
    /**
     * @var string
     */
    private $targetDirectory;

    /**
     * @var Factory
     */
    private $factory;

    /**
     * FileUploader constructor.
     * @param string $targetDirectory
     */
    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;

        $this->factory = new Factory();
    }

    public function getSpreadSheet(String $fileName): Spreadsheet
    {
        return $this->factory->createSpreadsheet($this->getTargetDirectory() . '/' . $fileName);
    }

    public function createSpreadSheet()
    {
        return $this->factory->createSpreadsheet();
    }

    /**
     * @param Spreadsheet $spreadsheet
     * @param String $entityName
     * @param String $fileName
     * @return string
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function save(Spreadsheet $spreadsheet, String $entityName, String $fileName): string
    {
        $saveToDirectory = $this->getTargetDirectory() . '/' . strtolower($entityName);
        $fileSystem = new Filesystem();
        if (!$fileSystem->exists($saveToDirectory )) {
            $fileSystem->mkdir($saveToDirectory );  //  0777 permissions by default
        }

        $writer = $this->factory->createWriter($spreadsheet, 'Xls');
        $writer->save($saveToDirectory  . '/' . $fileName);

        return $fileName;
    }

    /**
     * @return string
     */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}

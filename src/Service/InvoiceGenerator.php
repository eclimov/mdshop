<?php

namespace App\Service;

use App\Entity\Invoice;
use App\Service\XlsProcessor;

class InvoiceGenerator
{
    /**
     * @var string
     */
    private $targetDirectory;

    /**
     * @var XlsProcessor
     */
    private $xlsProcessor;

    /**
     * @param string $targetDirectory
     * @param XlsProcessor $xlsProcessor
     */
    public function __construct(string $targetDirectory, XlsProcessor $xlsProcessor)
    {
        $this->targetDirectory = $targetDirectory;
        $this->xlsProcessor = $xlsProcessor;
    }

    public function generate(Invoice $invoice)
    {
        $spreadsheet = $this->getXlsProcessor()
            ->getSpreadSheet($this->getInvoiceDirectory() . '/' . 'template.xlsx');

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue(
            'C2',
            $invoice->getOrderDate()->format('d.m.Y')
        );
        $sheet->setCellValue(
            'D2',
            $invoice->getDeliveryDate()->format('d.m.Y')
        );
        $sheet->setCellValue(
            'I4',
            $invoice->getCarrier()->getName()
        );
        $seller = $invoice->getSeller();
        $sheet->setCellValue(
            'C5',
            $seller->getName()
            . ' IBAN ' . $seller->getIban()
            . ' ' . $seller->getBankAffiliate()->getAffiliateNumber()
            . ' ' . $seller->getAddresses()[0]->getAddress()
        );
        $buyer = $invoice->getBuyer();
        $sheet->setCellValue(
            'C6',
            $buyer->getName()
            . ' IBAN ' . $buyer->getIban()
            . ' ' . $buyer->getBankAffiliate()->getAffiliateNumber()
            . ' ' . $buyer->getAddresses()[0]->getAddress()
        );
        $sheet->setCellValue(
            'O4',
            $seller->getFiscalCode() . ' / ' . $seller->getVat()
        );
        $sheet->setCellValue(
            'O5',
            $seller->getFiscalCode() . ' / ' . $seller->getVat()
        );
        $sheet->setCellValue(
            'O6',
            $seller->getFiscalCode() . ' / ' . $seller->getVat()
        );
        $sheet->setCellValue(
            'M7',
            $invoice->getAttachedDocument()
        );
        $sheet->setCellValue(
            'C9',
            $invoice->getLoadingPoint()->getAddress()
        );
        $sheet->setCellValue(
            'G9',
            $invoice->getUnloadingPoint()->getAddress()
        );
        $fileName = $invoice->getId() . '.xlsx';
        $this->getXlsProcessor()->save(
            $spreadsheet,
            $this->getInvoiceDirectory(),
            $fileName
        );

        return $this->getInvoiceDirectory() . '/' . $fileName;
    }

    public function getInvoiceDirectory()
    {
        return $this->targetDirectory;
    }

    public function getXlsProcessor()
    {
        return $this->xlsProcessor;
    }
}

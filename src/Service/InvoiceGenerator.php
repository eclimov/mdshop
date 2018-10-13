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
            $invoice->getCarrier()->getFullName()
        );
        $seller = $invoice->getSeller();
        $sheet->setCellValue(
            'C5',
            '"' . $seller->getFullName()
            . ' IBAN ' . $seller->getIban()
            . ' ' . $seller->getBankAffiliate()->getAffiliateNumber()
            . ' ' . $seller->getAddresses()[0]->getAddress()
        );
        $buyer = $invoice->getBuyer();
        $buyerAddresses = $buyer->getAddresses();
        $sheet->setCellValue(
            'C6',
            '"' . $buyer->getFullName()
            . ' IBAN ' . $buyer->getIban()
            . ' ' . $buyer->getBankAffiliate()->getAffiliateNumber()
            . ' ' . (\count($buyerAddresses) > 0 ? $buyerAddresses[0]->getAddress() : '')
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
        $loadingPoint = $invoice->getLoadingPoint();
        $sheet->setCellValue(
            'C9',
            ($loadingPoint !== null) ? $loadingPoint->getAddress() : ''
        );
        $unloadingPoint = $invoice->getUnloadingPoint();
        $sheet->setCellValue(
            'G9',
            ($unloadingPoint !== null) ? $unloadingPoint->getAddress() : ''
        );
        $approvedByEmployee = $invoice->getApprovedByEmployee();
        $sheet->setCellValue(
            'C25',
            trim($approvedByEmployee->getPosition() . ' ' . $approvedByEmployee->getName())
        );
        $processedByEmployee = $invoice->getProcessedByEmployee();
        $sheet->setCellValue(
            'C27',
            trim($processedByEmployee->getPosition() . ' ' . $processedByEmployee->getName())
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

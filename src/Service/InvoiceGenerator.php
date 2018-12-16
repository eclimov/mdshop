<?php

namespace App\Service;

use App\Entity\Company;
use App\Entity\CompanyAddress;
use App\Entity\Invoice;
use App\Repository\CompanyAddressRepository;
use App\Repository\CompanyRepository;
use App\Service\XlsProcessor;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\ManagerRegistry;

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

    public function generate(Invoice $invoice, ManagerRegistry $doctrine)
    {
        $spreadsheet = $this->getXlsProcessor()
            ->getSpreadSheet($this->getInvoiceDirectory() . '/' . 'template.xlsx');

        $sheet = $spreadsheet->getActiveSheet();

        $seller = $invoice->getSeller();
        $buyer = $invoice->getBuyer();
        $carrier = $invoice->getCarrier();

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
            $carrier->getShortName()
        );

        $sellerJuridicAddresses = $doctrine
            ->getRepository(CompanyAddress::class)
            ->findJuridicAddressesByCompany($seller);
        $sheet->setCellValue(
            'C5',
            $seller->getName()
            . ' IBAN ' . $seller->getIban()
            . ' ' . $seller->getBankAffiliate()->getAffiliateNumber()
            . ' a.j.' . $sellerJuridicAddresses[0]->getAddress()
        );
        $buyerJuridicAddresses = $doctrine
            ->getRepository(CompanyAddress::class)
            ->findJuridicAddressesByCompany($buyer);
        $sheet->setCellValue(
            'C6',
            $buyer->getName()
            . ' IBAN ' . $buyer->getIban()
            . ' ' . $buyer->getBankAffiliate()->getAffiliateNumber()
            . ' ' . (\count($buyerJuridicAddresses) > 0 ? ('a.j.' .$buyerJuridicAddresses[0]->getAddress()) : '')
        );
        $sheet->setCellValue(
            'O4',
            $carrier->getFiscalCode() . ' / ' . $carrier->getVat()
        );
        $sheet->setCellValue(
            'O5',
            $seller->getFiscalCode() . ' / ' . $seller->getVat()
        );
        $sheet->setCellValue(
            'O6',
            $buyer->getFiscalCode() . ' / ' . $buyer->getVat()
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
            ($unloadingPoint !== null) ? ('mun.Chisinau ' . $unloadingPoint->getAddress()) : ''
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

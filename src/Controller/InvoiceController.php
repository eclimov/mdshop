<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\CompanyAddress;
use App\Form\InvoiceType;
use App\Service\InvoiceGenerator;
use App\Service\XlsProcessor;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Invoice;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Route(name="invoice.")
 */
class InvoiceController extends AbstractController {
    /**
     * @Route("/invoice", name="list", methods={"GET"})
     * @return Response
     * @throws \LogicException
     */
    public function list(): Response
    {
        $invoices = $this->getDoctrine()
            ->getRepository(Invoice::class)
            ->findAll();

        return $this->render('invoice/list.html.twig', [
            'invoices' => $invoices,
        ]);
    }

    /**
     * @Route("/invoice/{id}/edit", name="edit", requirements={"id" = "\d+"}, methods={"GET", "POST"})
     * @param Request $request
     * @param Invoice $invoice
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function edit(Request $request, Invoice $invoice, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(
            InvoiceType::class,
            $invoice,
            [
                'companyInitiator' => $invoice->getSeller(),
                'company' => $invoice->getBuyer(),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('notice', 'Invoice info has been updated');

            return $this->redirectToRoute(
                'invoice.list'
            );
        }

        return $this->render('invoice/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("company/{id}/invoice/create", name="create", requirements={"id" = "\d+"}, methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Company $company
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em, Company $company): Response
    {
        $companyInitiator = $this->getUser()->getCompany();

        $invoice = new Invoice();
        $form = $this->createForm(
            InvoiceType::class,
            $invoice,
            [
                'companyInitiator' => $companyInitiator,
                'company' => $company,
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $invoice->setBuyer($company);
            $invoice->setSeller($companyInitiator);
            $em->persist($invoice);
            $em->flush();

            $this->addFlash('notice', 'Invoice has been created');

            return $this->redirectToRoute(
                'invoice.view',
                ['id' => $invoice->getId(),]
            );
        }

        return $this->render('invoice/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("invoice/{id}", name="view", requirements={"id" = "\d+"}, methods={"GET"})
     * @param Invoice $invoice
     * @return Response
     */
    public function view(Invoice $invoice): Response
    {
        $deleteForm = $this->createDeleteForm($invoice);

        return $this->render('invoice/show.html.twig', [
            'invoice' => $invoice,
            'deleteForm' => $deleteForm->createView(),
        ]);
    }

    /**
     * @Route("/invoice/{id}/delete", name="delete", requirements={"id" = "\d+"}, methods={"DELETE", "POST"})
     * @param Invoice $invoice
     * @param EntityManagerInterface $em
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Invoice $invoice, EntityManagerInterface $em): Response
    {
        $em->remove($invoice);
        $em->flush();

        $this->addFlash('notice', 'Invoice has been deleted');

        return $this->redirectToRoute('invoice.list');
    }

    /**
     * @param Invoice $invoice
     * @return FormInterface
     */
    public function createDeleteForm(Invoice $invoice): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('invoice.delete', ['id' => $invoice->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @Route("invoice/{id}/download", name="download", requirements={"id" = "\d+"}, methods={"GET"})
     * @param Invoice $invoice
     * @param InvoiceGenerator $invoiceGenerator
     * @return Response
     */
    public function download(Invoice $invoice, InvoiceGenerator $invoiceGenerator): Response
    {
        return $this->file(
            $invoiceGenerator->generate(
                $invoice,
                $this->getDoctrine()
            )
        );
    }
}

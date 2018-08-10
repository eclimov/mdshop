<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\CompanyAddress;
use App\Form\CompanyAddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(name="companyAddress.")
 */
class CompanyAddressController extends AbstractController {
    /**
     * @Route("/companyAddress", name="list")
     * @Method("GET")
     * @return Response
     * @throws \LogicException
     */
    public function list(): Response
    {
        $companyAddresses = $this->getDoctrine()
            ->getRepository(CompanyAddress::class)
            ->findAll();

        return $this->render('companyAddress/list.html.twig', [
            'companyAddresses' => $companyAddresses,
        ]);
    }

    /**
     * @Route("company/{id}/createCompanyAddress", name="create", requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Company $company
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em, Company $company): Response
    {
        $companyAddress = new CompanyAddress();
        $form = $this->createForm(CompanyAddressType::class, $companyAddress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyAddress->setCompany($company);
            $em->persist($companyAddress);
            $em->flush();

            $this->addFlash('notice', 'Company Address "' . $companyAddress->getAddress() . '" has been created');

            return $this->redirectToRoute(
                'company.view',
                ['id' => $companyAddress->getCompany()->getId(),]
            );
        }

        return $this->render('companyAddress/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/companyAddress/{id}/delete", name="delete", requirements={"id" = "\d+"})
     * @Method({"DELETE", "POST"})
     * @param CompanyAddress $companyAddress
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete(CompanyAddress $companyAddress, EntityManagerInterface $em): Response
    {
        $em->remove($companyAddress);
        $em->flush();

        $this->addFlash('notice', 'Company Address "' . $companyAddress->getAddress() . '" has been deleted');

        return $this->redirectToRoute(
            'company.view',
            ['id' => $companyAddress->getCompany()->getId(),]
        );
    }

    /**
     * @Route("/companyAddress/{id}/edit", name="edit", requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param CompanyAddress $companyAddress
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function edit(Request $request, CompanyAddress $companyAddress, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CompanyAddressType::class, $companyAddress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('notice', 'Company address info has been updated');

            return $this->redirectToRoute(
                'company.view',
                ['id' => $companyAddress->getCompany()->getId(),]
            );
        }

        return $this->render('companyAddress/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param CompanyAddress $companyAddress
     * @return FormInterface
     */
    public function createDeleteForm(CompanyAddress $companyAddress): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('companyAddress.delete', ['id' => $companyAddress->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}

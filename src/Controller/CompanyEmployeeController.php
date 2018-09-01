<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\CompanyEmployee;
use App\Form\CompanyEmployeeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(name="companyEmployee.")
 */
class CompanyEmployeeController extends AbstractController {
    /**
     * @Route("/companyEmployee", name="list")
     * @Method("GET")
     * @return Response
     * @throws \LogicException
     */
    public function list(): Response
    {
        $companyEmployees = $this->getDoctrine()
            ->getRepository(CompanyEmployee::class)
            ->findAll();

        return $this->render('companyAddress/list.html.twig', [
            'companyEmployees' => $companyEmployees,
        ]);
    }

    /**
     * @Route("company/{id}/createCompanyEmployee", name="create", requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Company $company
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em, Company $company): Response
    {
        $companyEmployee = new CompanyEmployee();
        $form = $this->createForm(CompanyEmployeeType::class, $companyEmployee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyEmployee->setCompany($company);
            $em->persist($companyEmployee);
            $em->flush();

            $this->addFlash('notice', 'Company Employee "' . $companyEmployee->getName() . '" has been created');

            return $this->redirectToRoute(
                'company.view',
                ['id' => $company->getId(),]
            );
        }

        return $this->render('companyEmployee/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/companyEmployee/{id}/delete", name="delete", requirements={"id" = "\d+"})
     * @Method({"DELETE", "POST"})
     * @param CompanyEmployee $companyEmployee
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete(CompanyEmployee $companyEmployee, EntityManagerInterface $em): Response
    {
        $em->remove($companyEmployee);
        $em->flush();

        $this->addFlash('notice', 'Company Employee "' . $companyEmployee->getName() . '" has been deleted');

        return $this->redirectToRoute(
            'company.view',
            ['id' => $companyEmployee->getCompany()->getId(),]
        );
    }

    /**
     * @Route("/companyEmployee/{id}/edit", name="edit", requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param CompanyEmployee $companyEmployee
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function edit(Request $request, CompanyEmployee $companyEmployee, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CompanyEmployeeType::class, $companyEmployee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('notice', 'Company employee info has been updated');

            return $this->redirectToRoute(
                'company.view',
                ['id' => $companyEmployee->getCompany()->getId(),]
            );
        }

        return $this->render('companyEmployee/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param CompanyEmployee $companyEmployee
     * @return FormInterface
     */
    public function createDeleteForm(CompanyEmployee $companyEmployee): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('companyEmployee.delete', ['id' => $companyEmployee->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}

<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Form\CompanyType;
use App\Repository\CompanyAddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="company.")
 */
class CompanyController extends AbstractController {
    /**
     * @Route("/company", name="list", methods={"GET"})
     * @return Response
     * @throws \LogicException
     */
    public function list(): Response
    {
        $companies = $this->getDoctrine()
            ->getRepository(Company::class)
            ->findVisibleToUser(
                $this->getUser()
            );

        return $this->render('company/list.html.twig', [
            'companies' => $companies,
        ]);
    }

    /**
     * @Route("company/{id}", name="view", requirements={"id" = "\d+"}, methods={"GET"})
     * @param Company $company
     * @param CompanyAddressRepository $companyAddressRepository
     * @return Response
     */
    public function view(Company $company, CompanyAddressRepository $companyAddressRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user->getRole() !== 'ROLE_ADMIN' && $company->isHidden()) {
            throw new AccessDeniedHttpException();
        }

        if(!\count($companyAddressRepository->findJuridicByCompany($company))) {
            $this->addFlash('warning', 'Company has no juridic address');
        }

        $deleteForm = $this->createDeleteForm($company);

        return $this->render('company/show.html.twig', [
            'company' => $company,
            'deleteForm' => $deleteForm->createView(),
        ]);
    }

    /**
     * @Route("company/create", name="create", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($company);
            $em->flush();

            $this->addFlash('notice', 'Company has been created');

            return $this->redirectToRoute(
                'company.list'
            );
        }

        return $this->render('company/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/company/{id}/delete", name="delete", requirements={"id" = "\d+"}, methods={"DELETE", "POST"})
     * @param Company $company
     * @param EntityManagerInterface $em
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Company $company, EntityManagerInterface $em): Response
    {
        $em->remove($company);
        $em->flush();

        $this->addFlash('notice', 'Company "' . $company->getName() . '" has been deleted');

        return $this->redirectToRoute('company.list');
    }

    /**
     * @Route("/company/{id}/edit", name="edit", requirements={"id" = "\d+"}, methods={"GET", "POST"})
     * @param Request $request
     * @param Company $company
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function edit(Request $request, Company $company, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('notice', 'Company info has been updated');

            return $this->redirectToRoute(
                'company.list'
            );
        }

        return $this->render('company/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Company $company
     * @return FormInterface
     */
    public function createDeleteForm(Company $company): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('company.delete', ['id' => $company->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}

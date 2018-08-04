<?php

namespace App\Controller;

use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(name="company.")
 */
class CompanyController extends AbstractController {
    /**
     * @Route("/company", name="list")
     * @Method("GET")
     * @return Response
     * @throws \LogicException
     */
    public function list(): Response
    {
        $companies = $this->getDoctrine()
            ->getRepository(Company::class)
            ->findAll();

        return $this->render('company/list.html.twig', [
            'companies' => $companies,
        ]);
    }

    /**
     * @Route("company/create", name="create")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $job = new Company();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($job);
            $em->flush();

            $this->addFlash('notice', 'Job has been created');

            return $this->redirectToRoute(
                'job.view',
                ['token' => $job->getToken(),]
            );
        }

        return $this->render('job/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/company/{id}/delete", name="delete", requirements={"id" = "\d+"})
     * @Method({"DELETE", "POST"})
     * @param Company $company
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete(Company $company, EntityManagerInterface $em): Response
    {
        $em->remove($company);
        $em->flush();

        $this->addFlash('notice', 'Company "' . $company->getName() . '" has been deleted');

        return $this->redirectToRoute('company.list');
    }

    /**
     * @Route("/company/{id}/edit", name="edit", requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Company $company
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function edit(Request $request, Company $company, EntityManagerInterface $em): Response
    {
        return $this->render('base.html.twig');
    }
}

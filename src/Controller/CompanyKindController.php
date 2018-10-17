<?php

namespace App\Controller;

use App\Entity\CompanyKind;
use App\Form\CompanyKindType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(name="companyKind.")
 */
class CompanyKindController extends AbstractController {
    /**
     * @Route("/companyKind", name="list", methods={"GET"})
     * @return Response
     * @throws \LogicException
     */
    public function list(): Response
    {
        $companyKinds = $this->getDoctrine()
            ->getRepository(CompanyKind::class)
            ->findAll();

        return $this->render('companyKind/list.html.twig', [
            'companyKinds' => $companyKinds,
        ]);
    }

    /**
     * @Route("companyKind/create", name="create", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $companyKind = new CompanyKind();
        $form = $this->createForm(CompanyKindType::class, $companyKind);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($companyKind);
            $em->flush();

            $this->addFlash('notice', 'Company kind has been created');

            return $this->redirectToRoute(
                'companyKind.list'
            );
        }

        return $this->render('companyKind/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/companyKind/{id}/delete", name="delete", requirements={"id" = "\d+"}, methods={"DELETE", "POST"})
     * @param CompanyKind $companyKind
     * @param EntityManagerInterface $em
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(CompanyKind $companyKind, EntityManagerInterface $em): Response
    {
        $em->remove($companyKind);
        $em->flush();

        $this->addFlash('notice', 'Company kind "' . $companyKind->getName() . '" has been deleted');

        return $this->redirectToRoute('companyKind.list');
    }

    /**
     * @Route("/companyKind/{id}/edit", name="edit", requirements={"id" = "\d+"}, methods={"GET", "POST"})
     * @param Request $request
     * @param CompanyKind $companyKind
     * @param EntityManagerInterface $em
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, CompanyKind $companyKind, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CompanyKindType::class, $companyKind);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('notice', 'Company kind info has been updated');

            return $this->redirectToRoute(
                'companyKind.list'
            );
        }

        return $this->render('companyKind/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param CompanyKind $companyKind
     * @return FormInterface
     */
    public function createDeleteForm(CompanyKind $companyKind): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('companyKind.delete', ['id' => $companyKind->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}

<?php

namespace App\Controller;

use App\Entity\Bank;
use App\Entity\BankAffiliate;
use App\Form\BankAffiliateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(name="bankAffiliate.")
 */
class BankAffiliateController extends AbstractController {
    /**
     * @Route("/bankAffiliate", name="list")
     * @Method("GET")
     * @return Response
     * @throws \LogicException
     */
    public function list(): Response
    {
        $bankAffiliates = $this->getDoctrine()
            ->getRepository(BankAffiliate::class)
            ->findAll();

        return $this->render('bank/list.html.twig', [
            'bankAffiliates' => $bankAffiliates,
        ]);
    }

    /**
     * @Route("bank/{id}/createAffiliate", name="create", requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Bank $bank
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em, Bank $bank): Response
    {
        $bankAffiliate = new BankAffiliate();
        $form = $this->createForm(BankAffiliateType::class, $bankAffiliate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bankAffiliate->setBank($bank);
            $em->persist($bankAffiliate);
            $em->flush();

            $this->addFlash('notice', 'Bank Affiliate "' . $bankAffiliate->getAffiliateNumber() . '" has been created');

            return $this->redirectToRoute(
                'bank.view',
                ['id' => $bankAffiliate->getBank()->getId(),]
            );
        }

        return $this->render('bankAffiliate/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/bankAffiliate/{id}/delete", name="delete", requirements={"id" = "\d+"})
     * @Method({"DELETE", "POST"})
     * @param BankAffiliate $bankAffiliate
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete(BankAffiliate $bankAffiliate, EntityManagerInterface $em): Response
    {
        $em->remove($bankAffiliate);
        $em->flush();

        $this->addFlash('notice', 'Bank Affiliate "' . $bankAffiliate->getAffiliateNumber() . '" has been deleted');

        return $this->redirectToRoute(
            'bank.view',
            ['id' => $bankAffiliate->getBank()->getId(),]
        );
    }

    /**
     * @Route("/bankAffiliate/{id}/edit", name="edit", requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param BankAffiliate $bankAffiliate
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function edit(Request $request, BankAffiliate $bankAffiliate, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(BankAffiliateType::class, $bankAffiliate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('notice', 'Bank affiliate info has been updated');

            return $this->redirectToRoute(
                'bank.view',
                ['id' => $bankAffiliate->getBank()->getId(),]
            );
        }

        return $this->render('bankAffiliate/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param BankAffiliate $bankAffiliate
     * @return FormInterface
     */
    public function createDeleteForm(BankAffiliate $bankAffiliate): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bankAffiliate.delete', ['id' => $bankAffiliate->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}

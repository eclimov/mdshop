<?php

namespace App\Controller;

use App\Entity\Bank;
use App\Entity\BankAffiliate;
use App\Form\BankAffiliateType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="bankAffiliate.")
 */
class BankAffiliateController extends AbstractController {
    /**
     * @Route("bank/{id}/affiliate/create", name="create", requirements={"id" = "\d+"}, methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Bank $bank
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em, Bank $bank): Response
    {
        $bankAffiliate = new BankAffiliate();
        $bankAffiliate->setBank($bank);

        $form = $this->createForm(BankAffiliateType::class, $bankAffiliate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
     * @Route("/bankAffiliate/{id}/delete", name="delete", requirements={"id" = "\d+"}, methods={"DELETE", "POST"})
     * @param BankAffiliate $bankAffiliate
     * @param EntityManagerInterface $em
     * @return Response
     * @IsGranted("ROLE_ADMIN")
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
     * @Route("/bankAffiliate/{id}/edit", name="edit", requirements={"id" = "\d+"}, methods={"GET", "POST"})
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
}

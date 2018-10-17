<?php

namespace App\Controller;

use App\Entity\Bank;
use App\Form\BankType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(name="bank.")
 */
class BankController extends AbstractController {
    /**
     * @Route("/bank", name="list", methods={"GET"})
     * @return Response
     * @throws \LogicException
     */
    public function list(): Response
    {
        $banks = $this->getDoctrine()
            ->getRepository(Bank::class)
            ->findAll();

        return $this->render('bank/list.html.twig', [
            'banks' => $banks,
        ]);
    }

    /**
     * @Route("bank/{id}", name="view", requirements={"id" = "\d+"}, methods={"GET"})
     * @param Bank $bank
     * @return Response
     */
    public function view(Bank $bank): Response
    {
        $deleteForm = $this->createDeleteForm($bank);

        return $this->render('bank/show.html.twig', [
            'bank' => $bank,
            'deleteForm' => $deleteForm->createView(),
        ]);
    }

    /**
     * @Route("bank/create", name="create", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $bank = new Bank();
        $form = $this->createForm(BankType::class, $bank);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($bank);
            $em->flush();

            $this->addFlash('notice', 'Bank "' . $bank->getName() . '" has been created');

            return $this->redirectToRoute(
                'bank.list',
                ['id' => $bank->getId(),]
            );
        }

        return $this->render('bank/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/bank/{id}/delete", name="delete", requirements={"id" = "\d+"}, methods={"DELETE", "POST"})
     * @param Bank $bank
     * @param EntityManagerInterface $em
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Bank $bank, EntityManagerInterface $em): Response
    {
        $em->remove($bank);
        $em->flush();

        $this->addFlash('notice', 'Bank "' . $bank->getName() . '" has been deleted');

        return $this->redirectToRoute('bank.list');
    }

    /**
     * @Route("/bank/{id}/edit", name="edit", requirements={"id" = "\d+"}, methods={"GET", "POST"})
     * @param Request $request
     * @param Bank $bank
     * @param EntityManagerInterface $em
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Bank $bank, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(BankType::class, $bank);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('notice', 'Bank info has been updated');

            return $this->redirectToRoute(
                'bank.list'
            );
        }

        return $this->render('bank/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Bank $bank
     * @return FormInterface
     */
    public function createDeleteForm(Bank $bank): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bank.delete', ['id' => $bank->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}

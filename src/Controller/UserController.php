<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route(name="user.")
 */
class UserController extends AbstractController {
    /**
     * @Route("/user", name="list")
     * @Method("GET")
     * @return Response
     * @throws \LogicException
     */
    public function list(): Response
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->render('user/list.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("user/create", name="create")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $user->getPassword();
            $passwordEncoded = $passwordEncoder->encodePassword($user, $password);
            $user->setPassword($passwordEncoded);

            $em->persist($user);
            $em->flush();

            $this->addFlash('notice', 'User "' . $user->getUsername() . '" has been created');

            return $this->redirectToRoute(
                'user.list',
                ['id' => $user->getId(),]
            );
        }

        return $this->render('user/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/{id}/delete", name="delete", requirements={"id" = "\d+"})
     * @Method({"DELETE", "POST"})
     * @param User $user
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete(User $user, EntityManagerInterface $em): Response
    {
        if ($user->getUsername() !== $this->getUser()->getUsername()) {  // Do not allow deleting user himself
            $em->remove($user);
            $em->flush();

            $this->addFlash('notice', 'User "' . $user->getUsername() . '" has been deleted');
        } else {
            $this->addFlash('error', 'You cannot delete yourself');
        }

        return $this->redirectToRoute('user.list');
    }

    /**
     * @Route("/user/{id}/edit", name="edit", requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param User $user
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function edit(Request $request, User $user, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $user->getPassword();
            $passwordEncoded = $passwordEncoder->encodePassword($user, $password);
            $user->setPassword($passwordEncoded);

            $em->flush();

            $this->addFlash('notice', 'User info has been updated');

            return $this->redirectToRoute(
                'user.list'
            );
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param User $user
     * @return FormInterface
     */
    public function createDeleteForm(User $user): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user.delete', ['id' => $user->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="item.")
 */
class ItemController extends AbstractController {
    /**
     * @Route("/", name="list", methods={"GET"})
     * @return Response
     */
    public function list(): Response
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->render('item/list.html.twig', [
            'users' => $users,
        ]);
    }
}

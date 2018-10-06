<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(name="sideprojects.")
 */
class SideProjectsController extends AbstractController {
    /**
     * @Route("/sideprojects/pureshkabird/privacypolicy", name="privacypolicy", methods={"GET"})
     * @return Response
     */
    public function list(): Response
    {
        return $this->render('sideProjects/pureshkabird/privacy_policy.html.twig');
    }
}

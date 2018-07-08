<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="company.")
 */
class CompanyController extends AbstractController {

    /**
     * @Route("/", name="show")
     * @Method("GET")
     * @return Response
     */
    public function show(): Response
    {
        return $this->render('base.html.twig');
    }
}

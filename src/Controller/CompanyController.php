<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="company.")
 */
class CompanyController extends Controller {

    /**
     * @Route("/{company}", name="show", requirements={"company" = "\d+"}, defaults={"company": 1})
     * @Method("GET")
     * @return Response
     */
    public function show(): Response
    {
        return $this->render('base.html.twig');
    }
}

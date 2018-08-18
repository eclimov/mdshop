<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\XlsProcessor;

/**
 * @Route(name="item.")
 */
class ItemController extends AbstractController {
    /**
     * @Route("/", name="list")
     * @Method("GET")
     * @return Response
     */
    public function list(): Response
    {
        return $this->render('item/list.html.twig');
    }
}

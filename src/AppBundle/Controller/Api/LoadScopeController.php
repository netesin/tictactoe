<?php

namespace AppBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class LoadScopeController extends Controller
{
    /**
     * @Route("/api/loadScope", methods={"GET"}, name="api.loadScope")
     *
     * @return JsonResponse
     */
    public function indexAction()
    {
        $response = new JsonResponse();
        $game     = $this->container->get('game');

        $response->setData($game->getScope());

        return $response;
    }
}

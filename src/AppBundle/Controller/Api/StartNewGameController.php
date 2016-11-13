<?php

namespace AppBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class StartNewGameController extends Controller
{
    /**
     * @Route("/api/startNewGame", methods={"POST"}, name="api.startNewGame")
     *
     * @return JsonResponse
     */
    public function indexAction()
    {
        $response = new JsonResponse();
        $game     = $this->container->get('game');

        $game->startNewGame();

        $response->setData($game->startNewGame());

        return $response;
    }
}

<?php

namespace AppBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class LoadGameController extends Controller
{
    /**
     * @Route("/api/loadGame", methods={"GET"}, name="api.loadGame")
     *
     * @return JsonResponse
     */
    public function indexAction()
    {
        $response = new JsonResponse();
        $game     = $this->container->get('game');

        $cells = null;

        if ($game->getCells() !== $game->getDefaultCells()) {
            $cells = $game->getCells();
        }

        $response->setData($cells);

        return $response;
    }
}

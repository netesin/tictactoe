<?php

namespace AppBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetCompMoveController extends Controller
{
    /**
     * @Route("/api/getCompMove", methods={"GET"}, name="api.getCompMove")
     *
     * @return JsonResponse
     */
    public function indexAction()
    {
        $response = new JsonResponse();
        $game     = $this->container->get('game');
        $data     = [];

        if ($cell = $game->getCompMove()) {
            $data['success'] = true;
            $data['cell']    = $cell;
            $data['winner']  = $game->getWinner();
        } else {
            $data['success'] = false;
        }

        $response->setData($data);

        return $response;
    }
}

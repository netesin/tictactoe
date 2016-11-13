<?php

namespace AppBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SetUserMoveController extends Controller
{
    /**
     * @Route("/api/setUserMove", methods={"POST"}, name="api.setUserMove")
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function indexAction(Request $request)
    {
        $response = new JsonResponse();
        $game     = $this->container->get('game');
        $data     = [];

        $json = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE || in_array('cell', $json)) {
            $data['success'] = false;
        } else {
            $data['success'] = $game->setUserMove($json['cell']);
            $data['winner']  = $game->getWinner();
        }

        $response->setData($data);

        return $response;
    }
}

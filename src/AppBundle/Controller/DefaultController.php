<?php

namespace AppBundle\Controller;

use AppBundle\Service\PiCamera;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:Default:default.html.twig');
    }

    /**
     * @Route("/selfie")
     */
    public function selfieAction()
    {
        /** @var PiCamera $camera */
        $camera = $this->get('pi_camera');
        $success = $camera->selfie();

        return new JsonResponse(\json_encode(['success' => $success]));
    }

    /**
     * @Route("/timelaps")
     */
    public function timelapsAction()
    {
        /** @var PiCamera $camera */
        $camera = $this->get('pi_camera');
        $success = $camera->timelaps();

        return new JsonResponse(\json_encode(['success' => $success]));
    }

    /**
     * @Route("/help")
     */
    public function helpAction()
    {
        return new JsonResponse(\json_encode(['success' => true]));
    }

    /**
     * @Route("/settings")
     */
    public function settingsAction()
    {
        return new JsonResponse(\json_encode(['success' => true]));
    }
}
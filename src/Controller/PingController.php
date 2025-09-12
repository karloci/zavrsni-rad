<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class PingController extends AbstractController
{
    #[Route("/ping", name: "ping")]
    public function ping(): JsonResponse
    {
        return $this->json("OK");
    }
}
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HakunaMatataController extends AbstractController
{
    #[Route('/hakuna-matata', name: 'app_hakuna_matata')]
    public function index(): Response
    {
        return $this->render('hakuna_matata/index.html.twig', [
            'controller_name' => 'HakunaMatataController',
        ]);
    }
}

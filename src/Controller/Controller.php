<?php

namespace App\Controller;

use App\Repository\DataFromSensorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        
        return $this->redirectToRoute('login');
    }

    #[Route('/local/{local}', name: 'local')]
    public function local(String $local, DataFromSensorRepository $dataFromSensorRepository): Response
    {

        $lastData = $dataFromSensorRepository->findLastDataByLocal($local);
        $currentData = new \DateTime("now");
        $dateLastData = $lastData->getSendedAt();
        $interval = $currentData->diff($dateLastData);
        
        return $this->render('/local.html.twig', [
            'lastData' => $lastData,
            'interval' => $interval
        ]);
    }


    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('/about.html.twig', [
        ]);
    }
}
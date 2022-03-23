<?php

namespace App\Controller;

use App\Entity\DataSearch;
use App\Form\DataSearchType;
use App\Repository\DataFromSensorRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function admin(): Response
    {
        return $this->render('admin/index.html.twig', []);
    }

    #[Route('/admin/showData', name: 'showData')]
    public function showData(Request $request, DataFromSensorRepository $dataFromSensorRepo): Response
    {
        $search = new DataSearch();

        $searchForm = $this->createForm(DataSearchType::class, $search);

        $searchForm->handleRequest($request);

        $datas = $dataFromSensorRepo->findDataBySearch($search);
        
        if ($datas == null){
            $this->redirectToRoute("noData", [
            'local' => "HELB"
        ]);
        }
        $co2DataValue = [];
        $humidityDataValue = [];
        $temperatureDataValue = [];
        $co2Date = [];
        $humidityDate = [];
        $temperatureDate = [];

        $goodCo2Counter = 0;
        $midCo2Counter = 0;
        $badCo2Counter = 0;


        foreach ($datas as $dataForChart) {
            if ($dataForChart->getType()->getValue() == "CO2") {
                $midValue = $dataForChart->getValue();
                $co2DataValue[] = $midValue;
                $co2Date[] = $dataForChart->getSendedAt()->format("d-m-y G:i");

                if($midValue < 600){
                    $goodCo2Counter ++;
                }else if ($midValue > 600 && $midValue < 900){
                    $midCo2Counter ++;
                }else{
                    $badCo2Counter++;
                }
            } else if ($dataForChart->getType()->getValue() == "Humidity") {
                $humidityDataValue[] = $dataForChart->getValue();
                $humidityDate[] = $dataForChart->getSendedAt()->format("d-m-y G:i");


            } else if ($dataForChart->getType()->getValue() == "Temperature") {
                $temperatureDataValue[] = $dataForChart->getValue();
                $temperatureDate[] = $dataForChart->getSendedAt()->format("d-m-y G:i");


            }
            
        }
        return $this->render('admin/showData.html.twig', [
            'datas' => $datas,
            'co2DataValue' => json_encode($co2DataValue),
            'humidityDataValue' => json_encode($humidityDataValue),
            'temperatureDataValue' => json_encode($temperatureDataValue),
            'dataValue' => json_encode($temperatureDataValue),
            'co2Date' => json_encode($co2Date),
            'humidityDate' => json_encode($humidityDate),
            'temperatureDate' => json_encode($temperatureDate),
            'goodCo2Counter' => json_encode($goodCo2Counter),
            'midCo2Counter' => json_encode($midCo2Counter),
            'badCo2Counter' => json_encode($badCo2Counter), 
            'searchForm' => $searchForm->createView(),
        ]);
    }

    #[Route('/admin/stage1', name: 'stage1')]
    public function stage1(): Response
    {
        return $this->render('admin/stage1.html.twig', []);
    }

    #[Route('/admin/stage2', name: 'stage2')]
    public function stage2(): Response
    {
        return $this->render('admin/stage2.html.twig', []);
    }

    #[Route('/admin/localDetails/{local}', name: 'localDetails')]
    public function localDetails(String $local, DataFromSensorRepository $dataFromSensorRepo): Response
    {
        $dataASC = $dataFromSensorRepo->findByLocal($local, "ASC");
        $dataFromDB = $dataFromSensorRepo->findByLocal($local, "DESC");
        $lastData = $dataFromSensorRepo->findLastDataByLocal($local);
        
        if ($dataASC == null || $dataFromDB == null || $lastData == null){
            return $this->redirectToRoute("noData", [
            'local' => $local
        ]);
        }

        $co2DataValue = [];
        $humidityDataValue = [];
        $temperatureDataValue = [];
        $co2Date = [];
        $humidityDate = [];
        $temperatureDate = [];

        $goodCo2Counter = 0;
        $midCo2Counter = 0;
        $badCo2Counter = 0;


        foreach ($dataASC as $dataForChart) {
            if ($dataForChart->getType()->getValue() == "CO2") {
                $midValue = $dataForChart->getValue();
                $co2DataValue[] = $midValue;
                $co2Date[] = $dataForChart->getSendedAt()->format("d-m-y G:i");

                if($midValue < 600){
                    $goodCo2Counter ++;
                }else if ($midValue > 600 && $midValue < 900){
                    $midCo2Counter ++;
                }else{
                    $badCo2Counter++;
                }
            } else if ($dataForChart->getType()->getValue() == "Humidity") {
                $humidityDataValue[] = $dataForChart->getValue();
                $humidityDate[] = $dataForChart->getSendedAt()->format("d-m-y G:i");

            } else if ($dataForChart->getType()->getValue() == "Temperature") {
                $temperatureDataValue[] = $dataForChart->getValue();
                $temperatureDate[] = $dataForChart->getSendedAt()->format("d-m-y G:i");

            }
            
        }
        $currentData = new \DateTime("now");
        $dateLastData = $lastData->getSendedAt();
        $interval = $currentData->diff($dateLastData);
        

        return $this->render('admin/localDetails.html.twig', [
            'datas' => $dataFromDB,
            'lastData' => $lastData,
            'interval' => $interval,
            'co2DataValue' => json_encode($co2DataValue),
            'humidityDataValue' => json_encode($humidityDataValue),
            'temperatureDataValue' => json_encode($temperatureDataValue),
            'dataValue' => json_encode($temperatureDataValue),
            'co2Date' => json_encode($co2Date),
            'humidityDate' => json_encode($humidityDate),
            'temperatureDate' => json_encode($temperatureDate),
            'goodCo2Counter' => json_encode($goodCo2Counter),
            'midCo2Counter' => json_encode($midCo2Counter),
            'badCo2Counter' => json_encode($badCo2Counter),

        ]);
    }

    #[Route('/admin/noData/{local}', name: 'noData')]
    public function noData(String $local): Response
    {
        return $this->render('admin/noData.html.twig', [
            'local' => $local
        ]);
    }
}
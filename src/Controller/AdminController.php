<?php

namespace App\Controller;

use App\Repository\DataFromSensorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function admin(): Response
    {
        return $this->render('admin/index.html.twig', []);
    }

    #[Route('/admin/showData', name: 'showData')]
    public function showData(DataFromSensorRepository $dataFromSensorRepo): Response
    {
        $datas = $dataFromSensorRepo->findAll();
          
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
                $co2Date[] = $dataForChart->getSendedAt()->format("d-m-y h:i a");

                if($midValue < 600){
                    $goodCo2Counter ++;
                }else if ($midValue > 600 && $midValue < 900){
                    $midCo2Counter ++;
                }else{
                    $badCo2Counter++;
                }
            } else if ($dataForChart->getType()->getValue() == "Humidity") {
                $humidityDataValue[] = $dataForChart->getValue();
                $humidityDate[] = $dataForChart->getSendedAt()->format("d-m-y h:i a");


            } else if ($dataForChart->getType()->getValue() == "Temperature") {
                $temperatureDataValue[] = $dataForChart->getValue();
                $temperatureDate[] = $dataForChart->getSendedAt()->format("d-m-y h:i a");


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
                $co2Date[] = $dataForChart->getSendedAt()->format("d-m-y h:i a");

                if($midValue < 600){
                    $goodCo2Counter ++;
                }else if ($midValue > 600 && $midValue < 900){
                    $midCo2Counter ++;
                }else{
                    $badCo2Counter++;
                }
            } else if ($dataForChart->getType()->getValue() == "Humidity") {
                $humidityDataValue[] = $dataForChart->getValue();
                $humidityDate[] = $dataForChart->getSendedAt()->format("d-m-y h:i a");

            } else if ($dataForChart->getType()->getValue() == "Temperature") {
                $temperatureDataValue[] = $dataForChart->getValue();
                $temperatureDate[] = $dataForChart->getSendedAt()->format("d-m-y h:i a");

            }
            
        }
        $currentData = new \DateTime("now");
        $dateLastData = $lastData->getSendedAt();
        $interval = $currentData->diff($dateLastData);
        //dd($tabCo2Counter);
        //dd($humidityDataValue);

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
}
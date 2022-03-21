<?php

namespace App\Entity;

use App\Repository\SensorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SensorRepository::class)]
class Sensor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $local;

    #[ORM\Column(type: 'string', length: 255)]
    private $sensorNbr;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocal(): ?string
    {
        return $this->local;
    }

    public function setLocal(string $local): self
    {
        $this->local = $local;

        return $this;
    }

    public function getSensorNbr(): ?string
    {
        return $this->sensorNbr;
    }

    public function setSensorNbr(string $sensorNbr): self
    {
        $this->sensorNbr = $sensorNbr;

        return $this;
    }
}

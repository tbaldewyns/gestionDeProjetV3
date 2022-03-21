<?php

namespace App\Entity;

use App\Repository\DataFromSensorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DataFromSensorRepository::class)]
class DataFromSensor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $SensorNbr;

    #[ORM\ManyToOne(targetEntity: DataType::class, inversedBy: 'dataFromSensors')]
    #[ORM\JoinColumn(nullable: false)]
    private $type;

    #[ORM\Column(type: 'float')]
    private $value;

    #[ORM\Column(type: 'string', length: 255)]
    private $local;

    #[ORM\Column(type: 'datetime')]
    private $sendedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSensorNbr(): ?string
    {
        return $this->SensorNbr;
    }

    public function setSensorNbr(string $SensorNbr): self
    {
        $this->SensorNbr = $SensorNbr;

        return $this;
    }

    public function getType(): ?DataType
    {
        return $this->type;
    }

    public function setType(?DataType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
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

    public function getSendedAt(): ?\DateTimeInterface
    {
        return $this->sendedAt;
    }

    public function setSendedAt(\DateTimeInterface $sendedAt): self
    {
        $this->sendedAt = $sendedAt;

        return $this;
    }
}

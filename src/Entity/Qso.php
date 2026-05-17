<?php

namespace App\Entity;

use App\Enum\State;
use App\Repository\QsoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QsoRepository::class)]
class Qso
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $activatorCallsign = null;

    #[ORM\Column(length: 255)]
    private ?string $parkReference = null;

    #[ORM\Column(enumType: State::class)]
    private ?State $state = null;

    #[ORM\Column(length: 255)]
    private ?string $band = null;

    #[ORM\Column(length: 255)]
    private ?string $mode = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $contactedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivatorCallsign(): ?string
    {
        return $this->activatorCallsign;
    }

    public function setActivatorCallsign(string $activatorCallsign): static
    {
        $this->activatorCallsign = $activatorCallsign;

        return $this;
    }

    public function getParkReference(): ?string
    {
        return $this->parkReference;
    }

    public function setParkReference(string $parkReference): static
    {
        $this->parkReference = $parkReference;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(State $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getBand(): ?string
    {
        return $this->band;
    }

    public function setBand(string $band): static
    {
        $this->band = $band;

        return $this;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(string $mode): static
    {
        $this->mode = $mode;

        return $this;
    }

    public function getContactedAt(): ?\DateTimeImmutable
    {
        return $this->contactedAt;
    }

    public function setContactedAt(?\DateTimeImmutable $contactedAt): static
    {
        $this->contactedAt = $contactedAt;

        return $this;
    }
}

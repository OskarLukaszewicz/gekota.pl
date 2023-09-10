<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class AnimalTable
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $temperature;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $humidity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $warming;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $terrariumDimensions;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $substrate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $food;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $UVB;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lifespan;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $CITES;

    public function getTemperature(): ?string
    {
        return $this->temperature;
    }

    public function setTemperature(string $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getHumidity(): ?string
    {
        return $this->humidity;
    }

    public function setHumidity(string $humidity): self
    {
        $this->humidity = $humidity;

        return $this;
    }

    public function getWarming(): ?string
    {
        return $this->warming;
    }

    public function setWarming(string $warming): self
    {
        $this->warming = $warming;

        return $this;
    }

    public function getTerrariumDimensions(): ?string
    {
        return $this->terrariumDimensions;
    }

    public function setTerrariumDimensions(string $terrariumDimensions): self
    {
        $this->terrariumDimensions = $terrariumDimensions;

        return $this;
    }

    public function getSubstrate(): ?string
    {
        return $this->substrate;
    }

    public function setSubstrate(string $substrate): self
    {
        $this->substrate = $substrate;

        return $this;
    }

    public function getFood(): ?string
    {
        return $this->food;
    }

    public function setFood(string $food): self
    {
        $this->food = $food;

        return $this;
    }

    public function getUVB(): ?string
    {
        return $this->UVB;
    }

    public function setUVB(string $UVB): self
    {
        $this->UVB = $UVB;

        return $this;
    }

    public function getLifespan(): ?string
    {
        return $this->lifespan;
    }

    public function setLifespan(string $lifespan): self
    {
        $this->lifespan = $lifespan;

        return $this;
    }

    public function getCITES(): ?string
    {
        return $this->CITES;
    }

    public function setCITES(string $CITES): self
    {
        $this->CITES = $CITES;

        return $this;
    }
}

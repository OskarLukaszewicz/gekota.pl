<?php

namespace App\Entity;

use App\Repository\DashboardConfigRepository;
use Doctrine\ORM\Mapping as ORM;
use Faker\Provider\Lorem;

/**
 * @ORM\Entity(repositoryClass=DashboardConfigRepository::class)
 */
class DashboardConfig
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default" : 4})
     */
    private $animalsNum;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default" : 4})
     */
    private $blogPostsNum;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default" : 4})
     */
    private $eventsWeeksNum;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $chartDaysRange;

    public function __construct()
    {
        $this->animalsNum = 4;
        $this->blogPostsNum = 4;
        $this->eventsWeeksNum = 8;
        $this->chartDaysRange = 14;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnimalsNum(): ?int
    {
        return $this->animalsNum;
    }

    public function setAnimalsNum(?int $animalsNum): self
    {
        $this->animalsNum = $animalsNum;

        return $this;
    }

    public function getBlogPostsNum(): ?int
    {
        return $this->blogPostsNum;
    }

    public function setBlogPostsNum(?int $blogPostsNum): self
    {
        $this->blogPostsNum = $blogPostsNum;

        return $this;
    }

    public function getEventsWeeksNum(): ?int
    {
        return $this->eventsWeeksNum;
    }

    public function setEventsWeeksNum(?int $eventsWeeksNum): self
    {
        $this->eventsWeeksNum = $eventsWeeksNum;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getChartDaysRange(): ?string
    {
        return $this->chartDaysRange;
    }

    public function setChartDaysRange(string $chartDaysRange): self
    {
        $this->chartDaysRange = $chartDaysRange;

        return $this;
    }

}

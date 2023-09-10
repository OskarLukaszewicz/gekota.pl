<?php

namespace App\Entity;

use App\Repository\GalleryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\EntityInterface\OneToManyEntityInterface;
use App\Entity\EntityInterface\ManyToOneEntityInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=GalleryRepository::class)
 * @UniqueEntity("sectionName")
 */
class Gallery implements OneToManyEntityInterface
{
    private const CLASSNAME = "Gallery";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sectionName;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="gallery")
     */
    private $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function addRelation(ManyToOneEntityInterface $image)
    {
        $this->addImage($image);
    }

    public function removeRelation(ManyToOneEntityInterface $image)
    {
        $this->removeImage($image);
    }

    public function getClassName(): string
    {
        return self::CLASSNAME;
    }

    public function getSectionName(): ?string
    {
        return $this->sectionName;
    }

    public function setSectionName(string $sectionName): self
    {
        $this->sectionName = $sectionName;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
            $this->images[] = $image;
            $image->setGallery($this);

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($image->getGallery() === $this) {
            $image->setGallery(null);
        }
        return $this;
    }

}

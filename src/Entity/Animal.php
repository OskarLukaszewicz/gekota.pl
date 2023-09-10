<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use App\Service\NoImageService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\EntityInterface\OneToManyEntityInterface;
use App\Entity\EntityInterface\ManyToOneEntityInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass=AnimalRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("spieces", message = "Gatunek musi być wartością unikalną dla każdego zwierzaka")
 */
class Animal implements OneToManyEntityInterface
{
    private const CLASSNAME = "Animal";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $class;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $spieces;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private $price;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank
     */
    private $commonName;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="animal")
     */
    private $images;

    /**
     * @ORM\Column(type="boolean")
     */
    private $avaible;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $inStock;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Embedded(class="AnimalTable", columnPrefix=false) 
     */
    private $animalTable;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->animalTable = new AnimalTable();
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

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }

    public function getSpieces(): ?string
    {
        return $this->spieces;
    }

    public function setSpieces(string $spieces): self
    {
        $this->spieces = $spieces;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCommonName(): ?string
    {
        return $this->commonName;
    }

    public function setCommonName(?string $commonName): self
    {
        $this->commonName = $commonName;

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
            $image->setAnimal($this);

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getAnimal() === $this) {
                
                $image->setAnimal(null);

            }
        }
        
        return $this;
    }

    public function isAvaible(): ?bool
    {
        return $this->avaible;
    }

    public function setAvaible(bool $avaible): self
    {
        $this->avaible = $avaible;

        return $this;
    }

    public function isInStock(): ?bool
    {
        return ($this->inStock ? true : false);
    }

    public function getInStock(): ?int
    {
        return $this->inStock;
    }

    public function setInStock(?int $inStock): self
    {
        $this->inStock = $inStock;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAnimalTable(): AnimalTable
    {
        return $this->animalTable;
    }

    public function setAnimalTable(AnimalTable $animalTable): self
    {
        $this->animalTable = $animalTable;

        return $this;
    }
    public function getNewsImg()
    {
        if (!empty($this->images) && count($this->images) > 0)
        {
            return $this->images[0];
        }

        return NoImageService::getNoImageInstance();
    }

    public function getOfferListImages()
    {
        $images = [];

        foreach ($this->images as $image) {
            if ($image->isAnimalFrontImage()) $images[] = $image;
        }

        return  empty($images) ? [NoImageService::getNoImageInstance()] : $images;
    }

    public function getProductImages()
    {
        $images = [];

        foreach ($this->images as $image) {
            if ($image->isAnimalFrontImage()) $images[] = $image;
        }

        foreach ($this->images as $image) {
            if (!$image->isAnimalFrontImage()) $images[] = $image;
        }

        return  empty($images) ? [NoImageService::getNoImageInstance()] : $images;
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {

        if ( $eventArgs->getObject() instanceof Animal ){
            if (
                $eventArgs->hasChangedField('inStock') && $eventArgs->getNewValue('inStock') == 0
            ) {
                $this->setAvaible(0);
            };

            if (
                $eventArgs->hasChangedField('inStock') && $eventArgs->getNewValue('inStock') != 0
            ) {
                $this->setAvaible(1);
            }
        }
    }
}

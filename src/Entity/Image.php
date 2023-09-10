<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\EntityInterface\OneToManyEntityInterface;
use App\Entity\EntityInterface\ManyToOneEntityInterface;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 * @Vich\Uploadable()
 */
class Image implements ManyToOneEntityInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Vich\UploadableField(mapping="images", fileNameProperty="url")
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity=Animal::class, inversedBy="images")
     * @ORM\JoinColumn(nullable=true)
     */
    private $animal;

    /**
     * @ORM\ManyToOne(targetEntity=Gallery::class, inversedBy="images")
     * @ORM\JoinColumn(nullable=true)
     */
    private $gallery;


    /**
     * @ORM\ManyToOne(targetEntity=BlogPost::class, inversedBy="images")
     * @ORM\JoinColumn(nullable=true)
     */
    private $blogPost;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $figcaption;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $animalFrontImage;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $postFrontImage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setRelation(OneToManyEntityInterface $entity): self
    {
        $className = $entity->getClassName();
        switch ($className) {
            case 'Animal':
                $this->setAnimal($entity);
                break;
            case 'BlogPost':
                $this->setBlogPost($entity);
                break;
            case 'Gallery':
                $this->setGallery($entity);
                break;
        }

        return $this;
    }
    public function unsetRelation(OneToManyEntityInterface $entity): self
    {
        $className = $entity->getClassName();
        switch ($className) {
            case 'Animal':
                $this->setAnimal(null);
                break;
            case 'BlogPost':
                $this->setBlogPost(null);
                break;
            case 'Gallery':
                $this->setGallery(null);
                break;
        }

        return $this;
    }



    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file): void
    {
        $this->file = $file;
    }

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): self
    {
        $this->animal = $animal;

        return $this;
    }

    public function getGallery(): ?Gallery
    {
        return $this->gallery;
    }

    public function setGallery(?Gallery $gallery): self
    {
        $this->gallery = $gallery;

        return $this;
    }

    public function getBlogPost(): ?BlogPost
    {
        return $this->blogPost;
    }

    public function setBlogPost(?BlogPost $blogPost): self
    {
        $this->blogPost = $blogPost;

        return $this;
    }

    public function getFigcaption(): ?string
    {
        return $this->figcaption;
    }

    public function setFigcaption(?string $figcaption): self
    {
        $this->figcaption = $figcaption;
        
        return $this;
    }

    public function isPostFrontImage(): bool
    {
        return $this->postFrontImage;
    }

    public function setPostFrontImage(bool $flag): self
    {
        $this->postFrontImage = $flag;

        return $this;
    }
    public function isAnimalFrontImage(): bool
    {
        return $this->animalFrontImage;
    }

    public function setAnimalFrontImage(bool $flag): self
    {
        $this->animalFrontImage = $flag;

        return $this;
    }

    public function __toString()
    {
        return $this->getUrl();
    }

}

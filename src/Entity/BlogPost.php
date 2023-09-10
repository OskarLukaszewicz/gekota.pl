<?php

namespace App\Entity;

use App\Repository\BlogPostRepository;
use App\Service\NoImageService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\EntityInterface\OneToManyEntityInterface;
use App\Entity\EntityInterface\ManyToOneEntityInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BlogPostRepository::class)
 * @UniqueEntity("slug", message = "Slug musi być wartością unikalną dla każdego posta")
 */
class BlogPost implements OneToManyEntityInterface
{
    private const CLASSNAME = "BlogPost";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private $content;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Regex(
     *     pattern="/^[ -~]+$/",
     *     message="Pole nie może zawierać znaków specjalnych.")
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts")
     */
    private $author;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="blogPost")
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAuthor(): ?User
    {
        if (!$this->author) {
            $author = new User();
            $author->setNickname("Gekota");
            return $author;
            // if BlogPost asks for author which was deleted or just doesnt exist
        }
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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
            $image->setBlogPost($this);

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getBlogPost() === $this) {
                $image->setBlogPost(null);
            }
        }
        return $this;
    }

    public function getPostFrontImage()
    {

        foreach ($this->images as $image) {
            if ($image->isPostFrontImage()) return $image;
        }

        return NoImageService::getNoImageInstance();
    }

    public function getBlogPostGallery()
    {
        $images = [];
        foreach ($this->images as $image) {
            if (!$image->isPostFrontImage()) $images[] = $image;
        }

        return $images;
    }
}

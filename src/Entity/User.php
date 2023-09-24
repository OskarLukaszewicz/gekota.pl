<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity("username", message = "Nazwa użytkownika jest już zajęta")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "Username musi mieć przynajmniej {{ limit }} znaków",
     *      maxMessage = "Username nie może mieć więcej niż {{ limit }} znaków"
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "Hasło musi mieć przynajmniej {{ limit }} znaków",
     *      maxMessage = "Hasło nie może mieć więcej niż {{ limit }} znaków"
     * )
     */
    private $password;

    /**
     * @Assert\NotBlank
     * @Assert\IdenticalTo(propertyPath="password", message="Podane hasła muszą być identyczne")
     */
    private $retypedPassword;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 5,
     *      max = 20,
     *      minMessage = "Nickname musi mieć przynajmniej {{ limit }} znaków",
     *      maxMessage = "Nickname nie może mieć więcej niż {{ limit }} znaków"
     * )
     */
    private $nickname;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */

    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=BlogPost::class, mappedBy="author")
     */
    private $posts;

    /**
     * @ORM\OneToOne(targetEntity=DashboardConfig::class,)
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $dashboardConfig;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    public function getRetypedPassword(): ?string
    {
        return $this->retypedPassword;
    }

    public function setRetypedPassword(string $retypedPassword): self
    {
        $this->retypedPassword = $retypedPassword;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
    public function getDashboardConfig(): ?DashboardConfig
    {
        return $this->dashboardConfig;
    }

    public function setDashboardConfig(DashboardConfig $dashboardConfig): self
    {
        $this->dashboardConfig = $dashboardConfig;

        return $this;
    }

    /**
     * @return Collection<int, BlogPost>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(BlogPost $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(BlogPost $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        return null;
    }
    
    public function __toString()
    {
        return $this->getNickname();
    }
}

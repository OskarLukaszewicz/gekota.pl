<?php

namespace App\DataFixtures;

use App\Entity\Animal;
use App\Entity\AnimalTable;
use App\Entity\BlogPost;
use App\Entity\DashboardConfig;
use App\Entity\Event;
use App\Entity\Gallery;
use App\Entity\Image;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;
    private $faker;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->faker = Factory::create();
        $this->hasher = $hasher;
    }
    private const USERS = 
    [
        [
            "username" => "admin123",
            "password" => "admin123",
            "nickname" => "mrAdmin",
            "roles" => ["ROLE_SUPERADMIN"],
            ],
        [
            "username" => "example",
            "password" => "password123",
            "nickname" => "Gekota",
            "roles" => ["ROLE_SUPERADMIN"],
        ]
    ];

    private const ANIMALS = 
    [
        "lizard" => "lizards", 
        "lizard1" => "lizards",
        "lizard2" => "lizards",
        "lizard3" => "lizards",
        "snake" => "snakes",   
        "snake1" => "snakes",  
        "turtle" => "turtles", 
        "turtle1" => "turtles",
        "frog" => "amphibians",
        "frog1" => "amphibians",
        "spider" => "arachnids", 
        "scorpio" => "arachnids", 
        "mantis" => "mantises", 
        "mantis1" => "mantises", 
        "bug" => "others",      
        "bug1" => "others",     
    ];
    private const GALLERIES = 
    [
        ["sectionName" => "animals"],
        ["sectionName" => "shop"],
        ["sectionName" => "terrariums"]
    ];

    private const IMAGES = 
    [
        [
            "animalId" => 0,
            "imageType" => "animal",
            "url" => "fixtureImages/lizards/lizard.jpg"
        ],
        [
            "animalId" => 0,
            "imageType" => "animal",
            "url" => "fixtureImages/lizards/lizard1.jpg"
        ],
        [
            "animalId" => 0,
            "imageType" => "animal",
            "url" => "fixtureImages/lizards/lizard2.jpg"
        ],
        [
            "animalId" => 0,
            "imageType" => "animal",
            "url" => "fixtureImages/lizards/lizard3.jpg"
        ],
        [
            "animalId" => 1,
            "imageType" => "animal",
            "url" => "fixtureImages/lizards/lizard4.jpg"
        ],
        [
            "animalId" => 1,
            "imageType" => "animal",
            "url" => "fixtureImages/lizards/lizard5.jpg"
        ],
        [
            "animalId" => 1,
            "imageType" => "animal",
            "url" => "fixtureImages/lizards/lizard6.jpg"
        ],
        [
            "animalId" => 1,
            "imageType" => "animal",
            "url" => "fixtureImages/lizards/lizard7.jpg"
        ],
        [
            "animalId" => 2,
            "imageType" => "animal",
            "url" => "fixtureImages/lizards/lizard8.jpg"
        ],
        [
            "animalId" => 2,
            "imageType" => "animal",
            "url" => "fixtureImages/lizards/lizard9.jpg"
        ],
        [
            "animalId" => 2,
            "imageType" => "animal",
            "url" => "fixtureImages/lizards/lizard10.jpg"
        ],
        [
            "animalId" => 2,
            "imageType" => "animal",
            "url" => "fixtureImages/lizards/lizard11.jpg"
        ],
        [
            "animalId" => 3,
            "imageType" => "animal",
            "url" => "fixtureImages/lizards/lizard12.jpg"
        ],
        [
            "animalId" => 3,
            "imageType" => "animal",
            "url" => "fixtureImages/lizards/lizard13.jpg"
        ],
        [
            "animalId" => 3,
            "imageType" => "animal",
            "url" => "fixtureImages/lizards/lizard14.jpg"
        ],
        [
            "animalId" => 3,
            "imageType" => "animal",
            "url" => "fixtureImages/lizards/lizard15.jpg"
        ],
        [
            "animalId" => 4,
            "imageType" => "animal",
            "url" => "fixtureImages/snakes/snake.jpg"
        ],
        [
            "animalId" => 4,
            "imageType" => "animal",
            "url" => "fixtureImages/snakes/snake1.jpg"
        ],
        [
            "animalId" => 4,
            "imageType" => "animal",
            "url" => "fixtureImages/snakes/snake2.jpg"
        ],
        [
            "animalId" => 4,
            "imageType" => "animal",
            "url" => "fixtureImages/snakes/snake3.jpg"
        ],
        [
            "animalId" => 5,
            "imageType" => "animal",
            "url" => "fixtureImages/snakes/snake4.jpg"
        ],
        [
            "animalId" => 5,
            "imageType" => "animal",
            "url" => "fixtureImages/snakes/snake5.jpg"
        ],
        [
            "animalId" => 5,
            "imageType" => "animal",
            "url" => "fixtureImages/snakes/snake6.jpg"
        ],
        [
            "animalId" => 5,
            "imageType" => "animal",
            "url" => "fixtureImages/snakes/snake7.jpg"
        ],
        [
            "animalId" => 6,
            "imageType" => "animal",
            "url" => "fixtureImages/turtles/turtle.jpg"
        ],
        [
            "animalId" => 7,
            "imageType" => "animal",
            "url" => "fixtureImages/turtles/turtle1.jpg"
        ],
        [
            "animalId" => 8,
            "imageType" => "animal",
            "url" => "fixtureImages/amphibians/frog.jpg"
        ],
        [
            "animalId" => 9,
            "imageType" => "animal",
            "url" => "fixtureImages/amphibians/frog1.jpg"
        ],
        [
            "animalId" => 10,
            "imageType" => "animal",
            "url" => "fixtureImages/arachnids/spider.jpg"
        ],
        [
            "animalId" => 11,
            "imageType" => "animal",
            "url" => "fixtureImages/arachnids/scorpio.jpg"
        ],
        [
            "animalId" => 12,
            "imageType" => "animal",
            "url" => "fixtureImages/mantises/mantis.jpg"
        ],
        [
            "animalId" => 13,
            "imageType" => "animal",
            "url" => "fixtureImages/mantises/mantis1.jpg"
        ],
        [
            "animalId" => 14,
            "imageType" => "animal",
            "url" => "fixtureImages/others/bug.jpg"
        ],
        [
            "animalId" => 15,
            "imageType" => "animal",
            "url" => "fixtureImages/others/bug1.jpg"
        ],
        [
            "galleryId" => 0,
            "imageType" => "gallery",
            "url" => "terrarium.jpg"
        ],
        [
            "galleryId" => 0,
            "imageType" => "gallery",
            "url" => "terrarium1.jpg"
        ],
        [
            "galleryId" => 0,
            "imageType" => "gallery",
            "url" => "terrarium2.jpg"
        ],
        [
            "galleryId" => 0,
            "imageType" => "gallery",
            "url" => "terrarium3.jpg"
        ],
        [
            "blogPostId" => 0,
            "imageType" => "blogPost",
            "url" => "orzesiony1.jpg"
        ],
        [
            "blogPostId" => 1,
            "imageType" => "blogPost",
            "url" => "ptaszniki.jpg"
        ],
        [
            "blogPostId" => 2,
            "imageType" => "blogPost",
            "url" => "felsuma.jpg"
        ],
        [
            "blogPostId" => 3,
            "imageType" => "blogPost",
            "url" => "logo.png"
        ],
    ];
    
    
    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadImages($manager);
        $this->loadAnimals($manager);
        $this->loadBlogPosts($manager);
        $this->loadEvents($manager);
        $this->loadGalleries($manager);
        
        $manager->flush();
    }

    public function loadImages($manager)
    {
        foreach (self::IMAGES as $imageFixture)
        {
            $image = new Image();
            $image->setFigcaption($this->faker->colorName());
            switch ($imageFixture["imageType"])
            {
                case "animal":
                    $image->setUrl($imageFixture["url"]);
                    $this->setReference("animal_" . $imageFixture["url"], $image);
                    break;
                case "gallery":
                    $image->setUrl($imageFixture["url"]);
                    $this->setReference("gallery_" . $imageFixture["url"], $image);
                    break;
                case "blogPost":
                    $image->setUrl($imageFixture["url"]);
                    $this->setReference("blogPost_" . $imageFixture["url"], $image);
                    break;
            }

            $manager->persist($image);
        }
        $manager->flush();
    }
    
    public function loadAnimals($manager)
    {
        $i = 0;
        foreach(self::ANIMALS as $spieces => $class)
        {
            $animal = new Animal();
            $animal->setClass($class);
            $animal->setSpieces($spieces);
            $animal->setPrice(rand(2, 10)*100);
            $animal->setCreatedAt(new \DateTimeImmutable());
            $animal->setCommonName("Zwierzak " . $this->faker->name("male"));
            $animal->setInStock(rand(0,8));
            $animal->getInStock() ? $animal->setAvaible(true) : $animal->setAvaible(false);
            $animal->setDescription($this->faker->realText(120));

            $animalTable = new AnimalTable();

            $animalTable->setTemperature(strval(rand(28,34)));
            $animalTable->setHumidity(strval(rand(6,8)*10)."%");
            $animalTable->setWarming(rand(0,1) ? "kabel grzejny" : "UVB");
            $animalTable->setTerrariumDimensions(strval(rand(3,10)*10) . "/" . (rand(3,10)*10) . "/" . (rand(3,10)*10));
            $animalTable->setSubstrate(rand(0,1) ? "piasek" : "glina");
            $animalTable->setFood(rand(0,1) ? "świerszcze" : "mączniki");
            $animalTable->setUVB(rand(0,1) ? "tak" : "nie");
            $animalTable->setLifespan(rand(8,20));
            $animalTable->setCITES(rand(0,1) ? "tak" : "nie");

            $animal->setAnimalTable($animalTable);

            $j=0;

            foreach(self::IMAGES as $image) {
                if (isset($image['animalId']) && $image['animalId'] == $i) {
                    $imageObject = $this->getReference("animal_" . $image["url"]);
                    if ($j === 0) {
                        $imageObject->setAnimalFrontImage(true);
                    } else {
                        $imageObject->setAnimalFrontImage(false);
                    }
                    $animal->addImage($imageObject);
                    $j+=1;
                }
            }

            $i++;

            $manager->persist($animal);

        }

        $manager->flush();
    }

    public function loadBlogPosts(ObjectManager $manager)
    {
        for ($i=0; $i<4; $i++)
        {
            $blogPost = new BlogPost();

            $blogPost->setTitle($this->faker->realText(20));
            $blogPost->setCreatedAt(new \DateTimeImmutable());
            $blogPost->setContent($this->faker->realText(200));
            $blogPost->setSlug($this->faker->slug());

            $author = $this->getReference("user_1");
            $blogPost->setAuthor($author);

            $j=0;
            
            foreach(self::IMAGES as $image) {
                
                if (isset($image['blogPostId']) && $image['blogPostId'] === $i) {
                    $imageObject = $this->getReference("blogPost_" . $image["url"]);
                    if ($j == 0) {
                        $imageObject->setPostFrontImage(true);
                    } else {
                        $imageObject->setPostFrontImage(false);
                    }
                    $blogPost->addImage($imageObject);
                }
            }
            
            $manager->persist($blogPost);   
        }

        $manager->flush();
    }

    public function loadEvents($manager)
    {
        for ($i = 0; $i < 5; $i++) 
        {
            $event = new Event();
            $event->setDate($this->faker->dateTimeBetween('1 days', '100 days'));
            $event->setCity($this->faker->city());
            $event->setSource($this->faker->url());

            $manager->persist($event);
            
        }
    }

    public function loadGalleries($manager)
    {
        $i=0;
        foreach (self::GALLERIES as $galleryFixture)
        {
            $gallery = new Gallery();
            $gallery->setSectionName($galleryFixture["sectionName"]);

            foreach(self::IMAGES as $image) {
                if (isset($image['galleryId']) && $image['galleryId'] == $i) {
                    $gallery->addImage($this->getReference("gallery_" . $image["url"]));
                }
            }

            $i++;
            $manager->persist($gallery);
        }
        $manager->flush();
    }

    public function loadUsers($manager)
    {
        foreach(self::USERS as $userFixture) {
            $user = new User();

            $user->setUsername($userFixture["username"]);
            $user->setPassword($this->hasher->hashPassword($user, $userFixture["password"]));
            $user->setNickname($userFixture["nickname"]);
            $user->setRoles($userFixture['roles']);

            $dashboardConfig = new DashboardConfig();

            $manager->persist($dashboardConfig);

            $user->setDashboardConfig($dashboardConfig);

            $this->setReference("user_1", $user);

            $manager->persist($user);
        }
    }
    
}

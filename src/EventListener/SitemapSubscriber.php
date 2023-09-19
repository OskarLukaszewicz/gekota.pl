<?php

namespace App\EventListener;

use App\Repository\AnimalRepository;
use App\Repository\BlogPostRepository;
use App\Repository\GalleryRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

class SitemapSubscriber implements EventSubscriberInterface
{
    /**
     * @var array
     */
    private static $CATEGORIES = [
        'lizards',
        'snakes',
        'turtles',
        'amphibians',
        'arachnids',
        'mantises',
        'others'
    ];

    /**
     * @var GalleryRepository
     */
    private $galleryRepository;

    /**
     * @var AnimalRepository
     */
    private $animalRepository;

    /**
     * @var BlogPostRepository
     */
    private $blogPostRepository;


    /**
     * @param BlogPostRepository $blogPostRepository
     */
    public function __construct(BlogPostRepository $blogPostRepository, AnimalRepository $animalRepository, GalleryRepository $galleryRepository)
    {
        $this->blogPostRepository = $blogPostRepository;
        $this->animalRepository = $animalRepository;
        $this->galleryRepository = $galleryRepository;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            SitemapPopulateEvent::class => 'populate',
        ];
    }

    /**
     * @param SitemapPopulateEvent $event
     */
    public function populate(SitemapPopulateEvent $event): void
    {
        $urlContainer = $event->getUrlContainer();
        $urlGenerator = $event->getUrlGenerator();
        $this->registerBlogPostsUrls($urlContainer, $urlGenerator);
        $this->registerProductCategoriesUrls($urlContainer, $urlGenerator);
        $this->registerProductsUrls($urlContainer, $urlGenerator);
        $this->registerGalleriesUrls($urlContainer, $urlGenerator);
    }

    /**
     * @param UrlContainerInterface $urls
     * @param UrlGeneratorInterface $router
     */
    public function registerBlogPostsUrls(UrlContainerInterface $urls, UrlGeneratorInterface $router): void
    {
        $posts = $this->blogPostRepository->findAll();

        foreach ($posts as $post) {
            $urls->addUrl(
                new UrlConcrete(
                    $router->generate(
                        "blog_post_show",
                        ['slug' => $post->getSlug()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ),
                    new \DateTime(),
                    'monthly',
                    '0.7'
                ),
                'blog'
            );
        }
    }

    /**
     * @param UrlContainerInterface $urls
     * @param UrlGeneratorInterface $router
     */
    public function registerProductCategoriesUrls(UrlContainerInterface $urls, UrlGeneratorInterface $router): void
    {
        foreach (self::$CATEGORIES as $category) {
            $urls->addUrl(
                new UrlConcrete(
                    $router->generate(
                        'offer_list_show',
                        ['class' => $category],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ),
                    new \DateTime(),
                    'daily',
                    '1.0'
                ),
                'products'
            );
        }
    }
    
    /**
     * @param UrlContainerInterface $urls
     * @param UrlGeneratorInterface $router
     */
    public function registerProductsUrls(UrlContainerInterface $urls, UrlGeneratorInterface $router): void
    {
        $animals = $this->animalRepository->findAll();

        foreach ($animals as $animal) {
            $urls->addUrl(
                new UrlConcrete(
                    $router->generate(
                        'product_show',
                        [
                            'class' => $animal->getClass(),
                            'product' => $animal->getSpieces()
                        ],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ),
                    new \DateTime(),
                    'daily',
                    '0.7'
                ),
                'products'
            );
        }
    }

    /**
     * @param UrlContainerInterface $urls
     * @param UrlGeneratorInterface $router
     */
    public function registerGalleriesUrls(UrlContainerInterface $urls, UrlGeneratorInterface $router): void
    {
        $galleries = $this->galleryRepository->findAll();

        foreach ($galleries as $gallery) {

            if ($gallery->getSectionName() === "terrariums") {
                continue;
            }

            $urls->addUrl(
                new UrlConcrete(
                    $router->generate(
                        'gallery_show',
                        ['sectionName' => $gallery->getSectionName()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ),
                    new \DateTime(),
                    'weekly',
                    '1.0'
                ),
                'gallery'
            );
        }
    }
}
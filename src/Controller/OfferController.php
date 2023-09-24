<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends AbstractController
{

    private static $CATEGORIES = [
        'lizards' => [
            'class' => 'lizards',
            'name' => 'Jaszczurki',
            'filename' => 'kategoriaJaszczurki.jpg',
            'mobileFilename' => 'kategoriaJaszczurki.jpg'
        ],
        'snakes' => [
            'class' => 'snakes',
            'name' => 'Węże',
            'filename' => 'kategoriaWeze.jpg',
            'mobileFilename' => 'kategoriaWezeMobile.jpg'
        ],
        'turtles' => [
            'class' => 'turtles',
            'name' => 'Żółwie',
            'filename' => 'kategoriaZolwie.jpg',
            'mobileFilename' => 'kategoriaZolwieMobile.jpg',
        ],
        'amphibians' => [
            'class' => 'amphibians',
            'name' => 'Płazy',
            'filename' => 'kategoriaPlazy.jpg',
            'mobileFilename' => 'kategoriaPlazy.jpg'
        ],
        'arachnids' => [
            'class' => 'arachnids',
            'name' => 'Pajęczaki',
            'filename' => 'kategoriaPajeczaki.jpg',
            'mobileFilename' => 'kategoriaPajeczakiMobile.jpg',
        ],
        'mantises' => [
            'class' => 'mantises',
            'name' => 'Modliszki',
            'filename' => 'kategoriaModliszki.jpg',
            'mobileFilename' => 'kategoriaModliszkiMobile.jpg'
        ],
        'others' => [
            'class' => 'others',
            'name' => 'Inne zwierzaki',
            'filename' => 'kategoriaInne.jpg',
            'mobileFilename' => 'kategoriaInneMobile.jpg',
        ],
    ];

    /**
     * @Route("/offer", name="offer_show", options={"sitemap" = {"priority" = 1.0, "changefreq" = "yearly" }})
     */
    public function index()
    {
        
        return $this->render("offer.html.twig", ['categories' => self::$CATEGORIES]);
    }

    /**
     * @Route("/offer/{class}", name="offer_list_show")
     */
    public function showOfferList(string $class, ManagerRegistry $doctrine)
    {
        $repository = $doctrine->getRepository("App\Entity\Animal");
        $animals = $repository->findBy(["class" => $class, "avaible" => true]);

        return $this->render("offerList/offerList.html.twig", ['animals' => $animals, 'categories' => self::$CATEGORIES]);
    }

    /**
     * @Route("/offer/{class}/{product}", name="product_show")
     */
    public function showProduct(string $class, string $product, ManagerRegistry $doctrine)
    {
        $repository = $doctrine->getRepository("App\Entity\Animal");
        $animal = $repository->findOneBy(['spieces' => str_replace("-"," ", $product)]);

        return $this->render("product/product.html.twig", ['categories' => self::$CATEGORIES, 'animal' => $animal]);
    }
}
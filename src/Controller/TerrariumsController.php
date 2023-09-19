<?php

namespace App\Controller;

use App\Entity\Gallery;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TerrariumsController extends AbstractController
{

    /**
     * @Route("/terrariums/order", name="order_terrarium_show", options={"sitemap" = {"priority" = 0.7, "changefreq" = "yearly" }})
     */

    public function showTerrariumOrderPage()
    {
        return $this->render("order.html.twig");
    }

    /**
     * @Route("/terrariums/arrangements", name="terrarium_arrangements_show", options={"sitemap" = {"priority" = 0.7, "changefreq" = "monthly" }})
     */

     public function showTerrariumArrangements(ManagerRegistry $doctrine)
     {
        $gallery = $doctrine->getRepository("App\Entity\Gallery")->findOneBy(["sectionName" => "terrariums"]);
        $images = $doctrine->getRepository("App\Entity\Image")->findBy(["gallery" => $gallery->getId()]);
         return $this->render("gallery.html.twig", ['images'=>$images, 'gallery' => $gallery]);
     }

    
}
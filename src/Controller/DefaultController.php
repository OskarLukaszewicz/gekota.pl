<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Gallery;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @Route("/", name="default_index", options={"sitemap" = true, "changefreq" = "weekly"})
     */
    public function index()
    {
        $recentlyAddedProducts = $this->doctrine->getRepository("App\Entity\Animal")->findBy(["avaible" => true], ['createdAt' => "DESC"], 4);
        $recentlyAddedProducts2 = $this->doctrine->getRepository("App\Entity\Animal")->findBy(["avaible" => true], ['createdAt' => "DESC"], 4, 4);
        return $this->render("index.html.twig", ['animals' => $recentlyAddedProducts, 'animals2' => $recentlyAddedProducts2]);
    }

    /**
     * @Route("/contact", name="contact_show", options={"sitemap" = {"priority" = 0.7, "changefreq" = "yearly"}})
     */
    public function showContact()
    {
        return $this->render("contact.html.twig");
    }

    /**
     * @Route("/calendar", name="calendar_show", options={"sitemap" = {"priority" = 0.7, "changefreq" = "weekly" }})
     */
    public function showCalendar()
    {
        $events = $this->doctrine->getRepository("App\Entity\Event")->findBy([], ['date' => 'ASC']);
        return $this->render("calendar.html.twig", ['events' => $events]);
    }

    /**
     * @Route("/gallery/{sectionName}", name="gallery_show")
     */

     public function showGallery(Gallery $gallery)
     {
         
         $images = $this->doctrine->getRepository("App\Entity\Image")->findBy(["gallery" => $gallery->getId()]);
        
 
         return $this->render("gallery.html.twig", ['images' => $images, 'gallery' => $gallery]);
     }

}
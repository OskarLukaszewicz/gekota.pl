<?php

namespace App\Controller\Admin;

use App\Entity\Gallery;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class GalleryCrudController extends AbstractController 
{

    private const SECTION = "galleries";
    private const GALLERIES = [
        "animals" => "Zwierzęta", 
        "shop" => "Sklep", 
        "terrariums" => "Terraria"];
    private $galleryRepository;
    private $imageRepository;
    private $em;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->galleryRepository = $doctrine->getRepository("App\Entity\Gallery");
        $this->imageRepository = $doctrine->getRepository("App\Entity\Image");
        $this->em = $doctrine->getManager();
    }

    /**
     * @Route("/admin/galleries", name="admin_galleries_show")
     */
    public function index()
    {
        $galleries = $this->galleryRepository->findAll();

        if (empty($galleries)) {

            foreach (self::GALLERIES as $key => $gallerySectionName)
            {
                $gallery = new Gallery();
                $gallery->setSectionName($key);
                
                $this->em->persist($gallery);
            }
    
            $this->em->flush();
            $this->index();

        }

        return $this->render('admin/galleries.html.twig', ['galleries' => self::GALLERIES, 'images' => [], 'sectionName' => null, 'gallery' => null, 'excludedIds' => null]);

    }

    /**
     * @Route("/admin/galleries/show/{sectionName}", name="admin_gallery_show")
     */
    public function showGallery(Gallery $gallery, string $sectionName, ?bool $addImage, Request $request)
    {

        $images = $gallery->getImages();

        $excludedIds = [];

        $search = null;

        if ($request->query->get('search') !== null && $request->getMethod() === "GET") {
            $search = $request->query->get('search');
        }

        foreach ($gallery->getImages() as $image) { $excludedIds[] = $image->getId(); }

        return $this->render('admin/galleries.html.twig', ['galleries' => self::GALLERIES, 'images' => $images, 'sectionName' => $sectionName, 'excludedIds' => $excludedIds, 'gallery' => $gallery, 'search' => $search]);
    }

}
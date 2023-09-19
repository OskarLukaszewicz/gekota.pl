<?php

namespace App\Controller\Admin;

use App\Entity\Animal;
use App\Entity\Image;
use App\Exception\ActionNotFoundException;
use App\Exception\ObjectByIdNotFoundException;
use App\Repository\ImageRepository;
use App\Service\Flasher;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\AnimalType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AnimalCrudController extends AbstractController 
{

    private const ENTITY = "Animal";
    private const SECTION = "animals";
    private const ANIMAL_TYPES = [
        'lizards' => 'Jaszczurki',
        'snakes' => 'Węże',
        'turtles' => 'Żółwie',
        'amphibians' => "Płazy",
        'arachnids' => 'Pajęczaki',
        'mantises' => "Modliszki",
        'others' => "Inne"
    ];
    private $animalRepository;
    private $imageRepository;
    private $em;
    private $slugger;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->animalRepository = $doctrine->getRepository("App\Entity\Animal");
        $this->imageRepository = $doctrine->getRepository("App\Entity\Image");
        $this->em = $doctrine->getManager();
    }

    /**
     * @Route("/admin/animals/ajax/{id}")
     */
    public function switchAvailabilityAjax(Animal $animal, Request $request)
    {
        if ($request->isXmlHttpRequest() && $request->getMethod() === "POST") {

            $animal->setAvaible(!($animal->isAvaible()));
            
            $this->em->flush();
    
            return new Response($animal->getCommonName(), '200');
        }
    }


    /**
     * @Route("/admin/animals/mobile/{class}", name="admin_mobile_show", defaults={"class": "lizards"})
     */
    public function mobileIndex(string $class = "lizards")
    {

        $animals = $this->animalRepository->findBy(['class' => $class], ['avaible' => 'DESC']);

        return $this->render('admin/mobileView.html.twig', ['class' => $class, 'animalTypes' => self::ANIMAL_TYPES, 'animals' => $animals]);
    }


    /**
     * @Route("/admin/animals/{class}/{spieces?}", name="admin_animals_show", defaults={"class": "lizards"})
     */
    public function index(string $class = "lizards", ?string $spieces, ?bool $addImage)
    {
        
        $animals = $this->animalRepository->findBy(['class' => $class], ['avaible' => 'DESC']);

        $selectedAnimal = ($spieces ? $this->animalRepository->findOneBy(['spieces' => (str_replace("-"," ",$spieces))]) : null);

        $excludedIds = [];

        if ($selectedAnimal) {

            foreach ($selectedAnimal->getImages() as $image) { $excludedIds[] = $image->getId(); }

        }
        

        return $this->render('admin/animals.html.twig', ['class' => $class, 'animalTypes' => self::ANIMAL_TYPES, 'animals' => $animals, 'selectedAnimal' => $selectedAnimal, 'spieces' => $spieces, 'excludedIds' => $excludedIds]);
    }

    /**
     * @Route("/admin/animals/action/{action}/{id?}", priority=1, name="admin_animals_action")
     */
    public function animalAction(string $action, ?int $id, Request $request)
    {
        switch ($action) {
            case "remove":
                $animal = $this->animalRepository->find($id);

                if (!$animal) throw new ObjectByIdNotFoundException($this->animalRepository->getClassName() ,$id);

                foreach ($animal->getImages() as $image) {$image->unsetRelation($animal);}

                $this->em->remove($animal);

                $this->em->flush();

                $this->addFlash(
                    'notice',
                    Flasher::getFlashMessage($action, self::ENTITY, $animal->getCommonName())
                 );

                $route = $request->headers->get('referer');

                return $route ? $this->redirect($route) : $this->redirectToRoute("admin_" . self::SECTION . "_show");
                
            case "create":
                $animal = new Animal();
                $form = $this->createForm(AnimalType::class, $animal);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $animal = $form->getData();
                    $animal->setAvaible($animal->isInStock());
                    $animal->setCreatedAt(new \DateTimeImmutable());
                    $this->em->persist($animal);
                    $this->em->flush();

                    $this->addFlash(
                       'notice',
                       Flasher::getFlashMessage($action, self::ENTITY, $animal->getCommonName())
                    );

                    return $this->redirectToRoute("admin_" . self::SECTION . "_action", ['action' => "create"]);
                }

                return $this->renderForm('admin/actionPage.html.twig', ['form' => $form]);

            case "update":
                $animal = $this->animalRepository->find($id);

                if (!$animal) throw new ObjectByIdNotFoundException($this->animalRepository->getClassName() ,$id);

                $form = $this->createForm(AnimalType::class, $animal);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {

                    $animal = $form->getData();

                    $this->em->flush();

                    $this->addFlash(
                        'notice',
                        Flasher::getFlashMessage($action, self::ENTITY, $animal->getCommonName())
                     );

                    return $this->redirectToRoute("admin_" . self::SECTION . "_show");
                }

                return $this->renderForm('admin/actionPage.html.twig', ['form' => $form]);
            
            case "makeFront":

                $animal = $this->animalRepository->find($id);
                $images = $animal->getImages();
                
                foreach ($images as $img) {
                    $img->setAnimalFrontImage(false);
                }

                 $image = $this->imageRepository->find($request->request->get('imageId'));

                if ($image instanceof Image) {
                    $image->setAnimalFrontImage(true);
                    $this->em->flush();
                };

                $this->addFlash(
                    'remove2',
                    '1'
                );

                $route = $request->headers->get('referer');

                return $route ? $this->redirect($route) : $this->redirectToRoute("admin_" . self::SECTION . "_show");

            default:
                throw new ActionNotFoundException('Akcja "' . $action . '" nie została rozpoznana przez aplikację.');
        }
    }



    /**
     * @Route("/admin/animals/ajax/{id}/{inStock}")
     */
    public function changeInStockAjax(Animal $animal, int $inStock, Request $request)
    {
        if ($request->isXmlHttpRequest() && $request->getMethod() === "POST") {

            $animal->setInStock($inStock);
            
            $this->em->flush();
    
            return new Response($animal->getInStock(), '200');
        }
    }

    /**
     * @Route("/admin/animals/ajax/price/{id}/{price}")
     */
    public function changePriceAjax(Animal $animal, int $price, Request $request)
    {
        if ($request->isXmlHttpRequest() && $request->getMethod() === "POST") {

            $animal->setPrice($price);

            $this->em->flush();

            return new Response($animal->getPrice(), '200');
        }
    }



    

}
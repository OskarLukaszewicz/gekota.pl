<?php

namespace App\Controller\Admin;

use App\Exception\ActionNotFoundException;
use App\Exception\ObjectByIdNotFoundException;
use App\Service\Flasher;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Image;
use App\Form\Type\ImageType;


class ImageCrudController extends AbstractController 
{

    private const EMBEDDED_IMAGE_USERS = [
        "animals.html.twig" => 'getAnimal',
        "blog.html.twig" => 'getBlogPost',
        "galleries.html.twig" => 'getGallery'
    ];
    private const ENTITY = "Image";
    private const SECTION = "images";
    private $imageRepository;
    private $em;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->imageRepository = $doctrine->getRepository("App\Entity\Image");
        $this->em = $doctrine->getManager();
    }

    /**
     * @Route("/admin/images/{show?}", name="admin_images_show")
     */
    public function index(?bool $show)
    {
        return $this->render('admin/images.html.twig', ['excludedIds' => null, 'show' => $show]);
    }

    public function showImages(?array $excludedIds, ?string $action, ?string $parentTemplate, Request $request, RequestStack $requestStack) 
    {
        empty($excludedIds) ? $images = $this->imageRepository->findAll() : $images = $this->imageRepository->getImagesExceptExcluded($excludedIds);
        
        if ($parentTemplate !== null && $parentTemplate !== 'images.html.twig') {
            $images = array_filter($images, function($image) use ($parentTemplate) {
                return !(call_user_func([$image, self::EMBEDDED_IMAGE_USERS[$parentTemplate]]));
            });
        }
        return $this->render('admin/imageList.html.twig', ['images' => $images, 'action' => $action, 'excludedIds' => $excludedIds, 'parentTemplate' => $parentTemplate]);

    }

    /**
     * @Route("/admin/images/action/{action}/{id?}", name="admin_images_action")
     */
    public function imageAction(string $action, ?int $id, Request $request)
    {
        switch ($action) {
            case "remove":

                $image = $this->imageRepository->find($id);

                if (!$image) throw new ObjectByIdNotFoundException($this->imageRepository->getClassName() ,$id);

                $this->em->remove($image);
                $this->em->flush();

                $this->addFlash(
                    'notice',
                    Flasher::getFlashMessage($action, self::ENTITY, $image->getUrl())
                 );

                 $route = $request->headers->get('referer');

                 return $route ? $this->redirect($route) : $this->redirectToRoute("admin_" . self::SECTION . "_show");
                
            case "create":
                
                $image = new Image();
                $form = $this->createForm(ImageType::class, $image, [
                    'action' => $this->generateUrl('admin_images_action', ['action' => 'create'])
                ]);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $image = $form->getData();
                    $image->setPostFrontImage(false);
                    $image->setAnimalFrontImage(false);

                    $this->em->persist($image);
                    $this->em->flush();

                    $this->addFlash(
                        'notice',
                        Flasher::getFlashMessage($action, self::ENTITY, $image->getUrl())
                     );

                     $route = $request->headers->get('referer');

                     return $route ? $this->redirect($route) : $this->redirectToRoute("admin_" . self::SECTION . "_show");
                }

                return $this->renderForm('admin/embeddedActionPage.html.twig', ['form' => $form]);

            default:
                throw new ActionNotFoundException('Akcja "' . $action . '" nie została rozpoznana przez aplikację.');
        }
    }
}
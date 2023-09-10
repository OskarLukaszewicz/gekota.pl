<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use App\Entity\User;
use App\Exception\ActionNotFoundException;
use App\Exception\ObjectByIdNotFoundException;
use App\Form\Type\BlogPostType;
use App\Service\Flasher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Image;


class BlogCrudController extends AbstractController 
{
    private const ENTITY = "BlogPost";
    private const SECTION = "blog";
    private $blogRepository;
    private $imageRepository;
    private $userRepository;
    private $em;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->blogRepository = $doctrine->getRepository("App\Entity\BlogPost");
        $this->userRepository = $doctrine->getRepository("App\Entity\User");
        $this->imageRepository = $doctrine->getRepository("App\Entity\Image");
        $this->em = $doctrine->getManager();
    }

    /**
     * @Route("/admin/blog/{slug?}/{addImage?}", name="admin_blog_show")
     */
    public function index(?string $slug, ?bool $addImage, Request $request)
    {
        $blogPosts = $this->blogRepository->findBy([] , ['createdAt' => 'DESC']);

        $selectedPost = ($slug ? $this->blogRepository->findOneBy(['slug' => $slug]) : null);

        $search = null;

        if ($request->query->get('search') !== null && $request->getMethod() === "GET") {
            $search = $request->query->get('search');
        }
    
        $excludedIds = [];

        if ($addImage) {

            foreach ($selectedPost->getImages() as $image) { $excludedIds[] = $image->getId(); }

        }
        
        return $this->render('admin/blog.html.twig', ['blogPosts' => $blogPosts, 'selectedPost' => $selectedPost, 'slug' => $slug, 'addImage' => $addImage, 'excludedIds' => $excludedIds, 'search' => $search]);
    }

    /**
     * @Route("/admin/blog/action/{action}/{id?}", priority=1, name="admin_blog_action")
     */
    public function blogAction(string $action, ?int $id, Request $request)
    {
        switch ($action) {
            case "remove":
                $post = $this->blogRepository->find($id);

                if (!$post) throw new ObjectByIdNotFoundException($this->blogRepository->getClassName() ,$id);

                foreach ($post->getImages() as $image) {$image->unsetRelation($post);}

                $this->em->remove($post);
                
                $this->em->flush();

                $this->addFlash(
                    'notice',
                    Flasher::getFlashMessage($action, self::ENTITY)
                 );
                
                return $this->redirectToRoute("admin_" . self::SECTION . "_show");
                
            case "create":
                $post = new BlogPost();
                $form = $this->createForm(BlogPostType::class, $post);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $post = $form->getData();
                    $post->setCreatedAt(new \DateTimeImmutable());
                    $post->setAuthor($this->getUser());
                    $post->setSlug(str_replace(" ","-",$post->getSlug()));
                    $this->em->persist($post);
                    $this->em->flush();
                    
                    $this->addFlash(
                        'notice',
                        Flasher::getFlashMessage($action, self::ENTITY)
                     );

                    return $this->redirectToRoute("admin_" . self::SECTION . "_show");
                }

                return $this->renderForm('admin/actionPage.html.twig', ['form' => $form]);

            case "update":

                $blog = $this->blogRepository->find($id);

                if (!$blog) throw new ObjectByIdNotFoundException($this->blogRepository->getClassName() ,$id);

                $form = $this->createForm(BlogPostType::class, $blog);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {

                    $blog = $form->getData();

                    $this->em->flush();

                    $this->addFlash(
                        'notice',
                        Flasher::getFlashMessage($action, self::ENTITY)
                     );

                    return $this->redirectToRoute("admin_" . self::SECTION . "_show");
                }

                return $this->renderForm('admin/actionPage.html.twig', ['form' => $form]);

            case "makeFront":

                $post = $this->blogRepository->find($id);
                $images = $post->getImages();
                
                foreach ($images as $img) {
                    $img->setPostFrontImage(false);
                }

                $image = $this->imageRepository->find($request->request->get('imageId'));

                if ($image instanceof Image) {
                    $image->setPostFrontImage(true);
                    $this->em->flush();
                };

                $this->addFlash(
                    'remove2',
                    '1'
                );
                
                $route = $request->headers->get('referer');

                return $route ? $this->redirect($route) : $this->redirectToRoute("admin_" . self::SECTION . "_show");

            default:
                throw new ActionNotFoundException('Akcja ' . $action . ' nie została rozpoznana przez aplikację.');
        }
    }


    // public function makeImageFront(BlogPost $blogPost, int $id)
    // {
    //     $manyToOneEntityIds = $request->request->get('ids');

    //     $images = $blogPost->getImages();
    //     foreach ($images as $image) {
    //         $image->setPostFrontImage(false);
    //     }
        
    // }

}
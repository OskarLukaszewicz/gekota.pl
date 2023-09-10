<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class BlogController extends AbstractController
{

    /**
     * @Route("/blog", name="blog_list_show")
     */
    public function showBlogList(ManagerRegistry $doctrine)
    {
        $repository = $doctrine->getRepository('App\Entity\BlogPost');
        $blogPosts = $repository->findBy([], ['createdAt' => "DESC"]);
        return $this->render("blog.html.twig", ['blogPosts' => $blogPosts]);
    }

    /**
     * @Route("/blog/{slug}", name="blog_post_show")
     */

     public function showBlogPost(ManagerRegistry $doctrine, string $slug)
     {
        $repository = $doctrine->getRepository('App\Entity\BlogPost');
        $blogPost = $repository->findOneBy(['slug' => $slug]);
        return $this->render("blogPost.html.twig", ['post' => $blogPost]);

     }
}
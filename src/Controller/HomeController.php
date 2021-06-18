<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home_user")
     */
    public function user(): Response
    {
        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        $posts = $postRepository->findBy(array(), array('createdAt' => 'DESC'), 5);

        $commentRepository = $this->getDoctrine()->getRepository(Comment::class);
        $comments = $commentRepository->findBy(array("valid" => 1), array('createdAt' => 'DESC'), 5);

        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $category = $categoryRepository->findAll();

        return $this->render('home/user.html.twig', [
            'posts' => $posts,
            'comments' => $comments,
            'categories' => $category,
        ]);
    }

    /**
     * @Route("/admin", name="home_admin")
     */
    public function admin(): Response
    {
        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        $posts = $postRepository->findBy(array(), array('createdAt' => 'DESC'), 5);

        $commentRepository = $this->getDoctrine()->getRepository(Comment::class);
        $comments = $commentRepository->findBy(array(), array('createdAt' => 'DESC'), 5);

        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $category = $categoryRepository->findAll();

        return $this->render('home/admin.html.twig', [
            'posts' => $posts,
            'comments' => $comments,
            'categories' => $category,
        ]);
    }
}

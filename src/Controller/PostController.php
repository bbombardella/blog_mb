<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="post")
     */
    public function index(int $page = 1): Response
    {
        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        $posts = $postRepository->findBy(array(), array('createdAt' => 'DESC'), 10, ($page-1)*10);
        return $this->render('post/admin.html.twig', [
            'posts' => $posts,
        ]);
    }
}

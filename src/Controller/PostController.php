<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    /**
     * @Route("/admin/posts/page/{page}", name="post")
     */
    public function index(int $page = 1): Response
    {
        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        $posts = $postRepository->findBy(array(), array('createdAt' => 'DESC'), 10, ($page - 1) * 10);
        return $this->render('post/admin.html.twig', [
            'posts' => $posts,
        ]);
    }


    /**
     * @Route("/admin/posts/delete/{slug}", name="post_delete")
     */
    public function show(int $id): Response
    {
        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        $posts = $postRepository->find($id);

        if ($posts) {
            return $this->render('post/show.html.twig', [
                'posts' => $posts,
            ]);
        } else {
            die(404);
        }
    }

    public function create(Request $request)
    {

        $post = new Post();

        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugger = new AsciiSlugger();

            $post->setSlug($slugger->slug($post->getTitle()));

            $postManager = $this->getDoctrine()->getManager();
            $postManager->persist($post);
            $postManager->flush();
        }

        return $this->render('post/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function edit()
    {
    }

    public function delete()
    {
    }
}

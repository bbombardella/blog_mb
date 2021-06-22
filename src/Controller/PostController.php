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

    public function index(int $page = 1): Response
    {
        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        $posts = $postRepository->findBy(array(), array('createdAt' => 'DESC'), 10, ($page - 1) * 10);
        $pagination = [
            'page' => $page,
            'pagesNb' => ceil($postRepository->total() / 10),
            'routeName' => 'posts',
            'routeParams' => []
        ];
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/admin/posts/page/{page}", name="post")
     */
    public function admin(int $page = 1): Response
    {
        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        $posts = $postRepository->findBy(array(), array('createdAt' => 'DESC'), 10, ($page - 1) * 10);
        $pagination = [
            'page' => $page,
            'pagesNb' => ceil($postRepository->total() / 10),
            'routeName' => 'posts_admin',
            'routeParams' => []
        ];
        return $this->render('post/admin.html.twig', [
            'posts' => $posts,
            'pagination' => $pagination
        ]);
    }


    /**
     * @Route("posts/{slug}", name="post_show")
     */
    public function show(string $slug): Response
    {
        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        $post = $postRepository->findOneBy([
            'slug' => $slug
        ]);

        if (!$post || $post->getPublishedAt() > (new \DateTime('now'))) {
            return $this->redirectToRoute('home_user');
        }

        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
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

            return $this->redirectToRoute("posts", [
                'page' => 1
            ]);
        }

        return $this->render('form.admin.html.twig', [
            'form' => $form->createView(),
            'title' => "Création d'un post"
        ]);
    }

    public function edit(Request $request, int $id)
    {
        $postManager = $this->getDoctrine()->getManager();
        $postRepository = $postManager->getRepository(Post::class);
        $post = $postRepository->find($id);

        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugger = new AsciiSlugger();

            $post->setSlug($slugger->slug($post->getTitle()));
            $post->setUpdatedAt(new \DateTime('now'));

            $postManager->flush();

            return $this->redirectToRoute("posts", [
                'page' => 1
            ]);
        }

        return $this->render('form.admin.html.twig', [
            'form' => $form->createView(),
            'title' => "Modification d'un post"
        ]);
    }

    public function delete(int $id)
    {
        $postManager = $this->getDoctrine()->getManager();
        $postRepository = $postManager->getRepository(Post::class);

        $post = $postRepository->find($id);

        $postManager->remove($post);
        $postManager->flush();

        return $this->redirectToRoute("posts", [
            'page' => 1
        ]);
    }
}

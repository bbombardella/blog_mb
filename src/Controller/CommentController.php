<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use App\Form\CommentFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment", name="comment")
     */
    public function index(): Response
    {
        $commentRepository = $this->getDoctrine()->getRepository(Comment::class);
        $comments = $commentRepository->findBy(array(), array('createdAt' => 'DESC'));

        return $this->render('comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }

    public function show(int $id): Response
    {
        $commentRepository = $this->getDoctrine()->getRepository(Comment::class);
        $comment = $commentRepository->find($id);

        if (!$comment) {
            throw $this->createNotFoundException(
                "Pas de Comment trouvé avec l'id \"${id}\""
            );
        }

        return $this->render('comment/show.html.twig', [
            'comments' => $comment,
        ]);
    }

    public function valid(int $id): Response
    {
        $commentRepository = $this->getDoctrine()->getRepository(Comment::class);
        $comment = $commentRepository->find($id);


        if (!$comment) {
            throw $this->createNotFoundException(
                "Pas de commentaire trouvé avec l'id \"${id}\""
            );
        }

        $comment->setValid(!$comment->getValid());


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->redirectToRoute("comments");
    }

    public function create(Request $request, string $slug): Response
    {
        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        $post = $postRepository->findOneBy([
            'slug' => $slug
        ]);

        if (!$post || $post->getPublishedAt() > (new \DateTime('now'))) {
            return $this->redirectToRoute('home_user');
        }

        $comment = new Comment();

        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setPost($post);
            $commentManager = $this->getDoctrine()->getManager();

            $commentManager->persist($comment);
            $commentManager->flush();

            return $this->redirectToRoute("posts_show", [
                'slug' => $slug
            ]);
        }

        return $this->render('comment/form.html.twig', [
            'form' => $form->createView(),
            'title' => "Rédaction du commentaire sur le post \"{$post->getTitle()}\""
        ]);
    }

    public function delete(int $id): Response
    {
        $commentRepository = $this->getDoctrine()->getRepository(Comment::class);
        $comment = $commentRepository->find($id);

        if (!$comment) {
            throw $this->createNotFoundException(
                "Pas de Comment trouvé avec l'id \"${id}\""
            );
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->redirectToRoute("comments");
    }
}

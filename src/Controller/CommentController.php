<?php

namespace App\Controller;

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
        $comments = $commentRepository->findAll();

        if (!$comments) {
            throw $this->createNotFoundException(
                "Pas de commentaires disponibles !"
            );
        }

        return $this->render('comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }

    public function show(int $id): Response {
        $commentRepository = $this->getDoctrine()->getRepository(Comment::class);
        $comment = $commentRepository->find($id);

        if (!$comment) {
            throw $this->createNotFoundException(
                "Pas de Comment trouvé avec l'id \"${id}\""
            );
        }

        return $this->render('comment/show.html.twig', [
            'post' => $post
        ]);
    }

    public function valid(int $id): Response {
        $commentRepository = $this->getDoctrine()->getRepository(Comment::class);
        $comment = $commentRepository->find($id);

        if (!$comment) {
            throw $this->createNotFoundException(
                "Pas de Comment trouvé avec l'id \"${id}\""
            );
        }

        $comment->valid = !$comment->valid;

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();
    }

    public function create(Request $request): Response {
        $comment = new Comment();

        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest();

        if($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
        }

        return $this->render('comment/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function remove(int $id): Response {
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
    }
}

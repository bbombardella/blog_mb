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
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
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

    public function edit(): Response {

    }

    public function remove(): Response {

    }
}

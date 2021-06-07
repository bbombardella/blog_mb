<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    public function show(int $id): Response {
        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $category = $categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException(
                "Aucune catégorie ne correspond à l'id \"${id}\""
            );
        }

        return $this->render('category/show.html.twig', [
            'post' => $post
        ]);
    }

    public function create(Request $request): Response {
        $category = new Category();

        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest();

        if($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
        }

        return $this->render('category/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function edit($id, Request $request): Response {
        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $category = $categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException(
                "Aucune catégorie ne correspond à l'id \"${id}\""
            );
        }

        if($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->editView()
        ]);
    }

    public function remove($id): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $categoryRepository = $entityManager->getRepository(Category::class);

        $category = $categoryRepository->find($id);

        if(!$category) {
            throw $this->createNotFoundException(
                "Pas de Post trouvé avec l'id ".$id
            );
        }

        $entityManager->remove($category);
        $entityManager->flush();

    }
}

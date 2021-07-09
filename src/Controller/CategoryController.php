<?php

namespace App\Controller;

use App\Entity\CategoryEntity;
use App\Form\CategoryEntityType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'category_entities' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="category_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $categoryEntity = new CategoryEntity();
        $form = $this->createForm(CategoryEntityType::class, $categoryEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categoryEntity);
            $entityManager->flush();

            return $this->redirectToRoute('category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/new.html.twig', [
            'category_entity' => $categoryEntity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_show", methods={"GET"})
     */
    public function show(CategoryEntity $categoryEntity): Response
    {
        return $this->render('category/show.html.twig', [
            'category_entity' => $categoryEntity,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CategoryEntity $categoryEntity): Response
    {
        $form = $this->createForm(CategoryEntityType::class, $categoryEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/edit.html.twig', [
            'category_entity' => $categoryEntity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_delete", methods={"POST"})
     */
    public function delete(Request $request, CategoryEntity $categoryEntity): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoryEntity->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($categoryEntity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('category_index', [], Response::HTTP_SEE_OTHER);
    }
}

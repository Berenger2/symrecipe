<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController
{
    #[Route('/ingredient', name:'ingredient.index', methods:['GET'])]
/**
 * This controller display all ingredients
 *
 * @param  mixed $repository
 * @param  mixed $paginator
 * @param  mixed $request
 * @return Response
 */
function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
    $ingredients = $paginator->paginate(
        $repository->findAll(),
        $request->query->getInt('page', 1), /*page number*/
        10/*limit per page*/
    );

    return $this->render('pages/ingredient/list.html.twig', [
        'ingredients' => $ingredients,
    ]);
}

#[Route('/ingredient/nouveau', name:'ingredient.new', methods:['GET', 'POST'])]

/**
 * This controller show the form which create  an engredient
 *
 * @param  mixed $request
 * @param  mixed $manager
 * @return Response
 */
function new (Request $request, EntityManagerInterface $manager): Response {
    $ingredient = new Ingredient();
    $form = $this->createForm(IngredientType::class, $ingredient);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $ingredient = $form->getData();
        $manager->persist($ingredient);
        $manager->flush();
        $this->addFlash(
            'success',
            'Votre ingredient a été créé avec succès !'
        );

        return $this->redirectToRoute('ingredient.index');
    }
    return $this->render('pages/ingredient/add.html.twig', [
        'form' => $form->createView(),
    ]);
}

#[Route('/ingredient/edition/{id}', name:'ingredient.edit', methods:['GET', 'POST'])]

function edit(Ingredient $ingredient, Request $request, EntityManagerInterface $manager): Response
    {

    $form = $this->createForm(IngredientType::class, $ingredient);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $ingredient = $form->getData();
        $manager->persist($ingredient);
        $manager->flush();
        $this->addFlash(
            'success',
            'Votre ingredient a été modifié avec succès !'
        );

        return $this->redirectToRoute('ingredient.index');
    }
    return $this->render('pages/ingredient/edit.html.twig', [
        'form' => $form->createView(),
    ]);
}

#[Route('/ingredient/suppression/{id}', name:'ingredient.delete', methods:['POST', 'GET'])]

function delete(Ingredient $ingredient, EntityManagerInterface $manager): Response
    {

    $manager->remove($ingredient);
    $manager->flush();
    $this->addFlash(
        'success',
        'Votre ingredient a été supprimé avec succès !'
    );

    return $this->redirectToRoute('ingredient.index');
}

}

<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{
    #[Route('/recipe', name:'recipe.index', methods:['GET'])]
function index(RecipeRepository $repository,
    PaginatorInterface $paginator,
    Request $request): Response {

    $recipes = $paginator->paginate(
        $repository->findAll(),
        $request->query->getInt('page', 1), /*page number*/
        10/*limit per page*/
    );

    return $this->render('pages/recipe/list.html.twig', [
        'recipes' => $recipes,
    ]);
}

#[Route('/recipe/nouveau', name:'recipe.new', methods:['GET', 'POST'])]

/**
 * This controller show the form which create  an engredient
 *
 * @param  mixed $request
 * @param  mixed $manager
 * @return Response
 */
function new (Request $request, EntityManagerInterface $manager): Response {
    $recipe = new Recipe();
    $form = $this->createForm(RecipeType::class, $recipe);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $recipe = $form->getData();
        $manager->persist($recipe);
        $manager->flush();
        $this->addFlash(
            'success',
            'Votre recette a été créée avec succès !'
        );

        return $this->redirectToRoute('recipe.index');
    }
    return $this->render('pages/recipe/add.html.twig', [
        'form' => $form->createView(),
    ]);
}

#[Route('/recipe/edition/{id}', name:'recipe.edit', methods:['GET', 'POST'])]

function edit(Recipe $recipe, Request $request, EntityManagerInterface $manager): Response
    {

    $form = $this->createForm(RecipeType::class, $recipe);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $recipe = $form->getData();
        $manager->persist($recipe);
        $manager->flush();
        $this->addFlash(
            'success',
            'Votre recette a été modifiée avec succès !'
        );

        return $this->redirectToRoute('recipe.index');
    }
    return $this->render('pages/recipe/edit.html.twig', [
        'form' => $form->createView(),
    ]);
}

#[Route('/recipe/suppression/{id}', name:'recipe.delete', methods:['POST', 'GET'])]

function delete(Recipe $recipe, EntityManagerInterface $manager): Response
    {

    $manager->remove($recipe);
    $manager->flush();
    $this->addFlash(
        'success',
        'Votre recette a été supprimée avec succès !'
    );

    return $this->redirectToRoute('recipe.index');
}
}

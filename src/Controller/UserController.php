<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    #[Route('/utilisateur/edition/{id}', name:'security.edit', methods:['GET', 'POST'])]

function edit(User $user, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {

    if (!$this->getUser()) {

        $this->addFlash(
            'warning',
            'Merci de bien vouloir vous connecter'
        );
        return $this->redirectToRoute('security.login');
    }
    if ($this->getUser() !== $user) {
        $this->addFlash(
            'warning',
            "vous n'êtes pas autorisé a voir cette page"
        );

        return $this->redirectToRoute('home.index');
    }

    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        if ($hasher->isPasswordValid($user, $form->getData()->getPlainPassword())) {
            $user = $form->getData();
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                'Les information de votre compte ont bien été modifiées !'
            );

            return $this->redirectToRoute('security.index');

        } else {

            $this->addFlash(
                'warning',
                "Le mot de passe renseigné est incorrect."
            );
            $route = $request->headers->get('referer');
            return $this->redirect($route);
        }

    }
    return $this->render('pages/security/edit.html.twig', [
        'form' => $form->createView(),
    ]);
}

#[Route('/utilisateur/edition-mot-de-passe/{id}', name:'security.edit.password', methods:['GET', 'POST'])]

function editPassword(User $user, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {

    if (!$this->getUser()) {

        $this->addFlash(
            'warning',
            'Merci de bien vouloir vous connecter'
        );
        return $this->redirectToRoute('security.login');
    }
    if ($this->getUser() !== $user) {
        $this->addFlash(
            'warning',
            "vous n'êtes pas autorisé a voir cette page"
        );

        return $this->redirectToRoute('home.index');
    }

    $form = $this->createForm(UserPasswordType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        if ($hasher->isPasswordValid($user, $form->getData()['plainPassword'])) {
            $pass = $hasher->hashPassword($user, $form->getData()['newPassword']);
            $user->setPassword($pass);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                'Le mot de passe a été modifiées !'
            );
            return $this->redirectToRoute('home.index');
        } else {

            $this->addFlash(
                'warning',
                "Le mot de passe renseigné est incorrect."
            );
            $route = $request->headers->get('referer');
            return $this->redirect($route);
        }

    }
    return $this->render('pages/security/editPassword.html.twig', [
        'form' => $form->createView(),
    ]);
}

}

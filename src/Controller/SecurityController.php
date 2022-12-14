<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/connexion', name:'security.login', methods:['GET', 'POST'])]
function login(AuthenticationUtils $authenticationUtils): Response
    {
    return $this->render('pages/security/login.html.twig', [
        'last_username' => $authenticationUtils->getLastUsername(),
        'error' => $authenticationUtils->getLastAuthenticationError(),
    ]);
}

#[Route('/deconnexion', name:'security.logout', methods:['GET', 'POST'])]

function logout()
    {
    # code...
}

#[Route('/utilisateur', name:'security.index', methods:['GET'])]
function index(UserRepository $repository,
    PaginatorInterface $paginator,
    Request $request): Response {

    $users = $paginator->paginate(
        $repository->findAll(),
        $request->query->getInt('page', 1), /*page number*/
        10/*limit per page*/
    );

    return $this->render('pages/security/list.html.twig', [
        'users' => $users,
    ]);
}

#[Route('/registration', name:'security.registration', methods:['GET', 'POST'])]

function registration(Request $request, EntityManagerInterface $manager): Response
    {
    $user = new User;
    $user->setRoles(['ROLE_USER']);
    $form = $this->createForm(RegistrationType::class, $user);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $user = $form->getData();
        $manager->persist($user);
        $manager->flush();
        $this->addFlash(
            'success',
            "L'utilisateur a été créée avec succès !"
        );

        return $this->redirectToRoute('security.login');
    }

    return $this->render('pages/security/registration.html.twig', [
        'form' => $form->createView(),
    ]);
}


}

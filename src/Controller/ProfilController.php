<?php

namespace App\Controller;

use App\Form\ProfileFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            // Redirige ou affiche un message si non connecté
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($form->get('plainPassword')->getData())) {
                $hashedPassword = $userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData());
                $user->setPassword($hashedPassword);
            }
            if (!empty($form->get('email')->getData())) {
                $user->setEmail($form->get('email')->getData());
            }
            if(!empty($form->get('username')->getData())) {
                $user->setUsername($form->get('username')->getData());
            }
            if(!empty($form->get('roles')->getData())) {
                $user->setRoles($form->get('roles')->getData());
            }




            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_profil');

        }
        return $this->render('profil/profile.html.twig', [
            'profileForm' => $form,
        ]);

    }

}

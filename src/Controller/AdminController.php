<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\Voter\RolesVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{


    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        $user = $this->getUser();

        if(!$this->isGranted(RolesVoter::VIEW, $user)) {
            return $this->redirectToRoute('app_home');
        }




        return $this->render('admin/index.html.twig');
    }
}
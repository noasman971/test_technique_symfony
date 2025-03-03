<?php

namespace App\Controller;

use App\Entity\Infos;
use App\Entity\User;
use App\Security\Voter\RolesVoter;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class AdminController extends AbstractController
{


    #[Route('/admin', name: 'app_admin')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();

        if(!$this->isGranted(RolesVoter::VIEW, $user)) {
            return $this->redirectToRoute('app_home');
        }

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }


        $infos = $entityManager->getRepository(Infos::class)->findAll();


        $list = [];

        // sort by insertion to set the rank with the number of victory
        foreach ($infos as $info) {
            $list[] = $info;
        }
        for ($i = 1; $i < count($list); $i++) {
            $currentUser = $list[$i];
            $currentVictory = $currentUser->getVictory();
            $j = $i-1;

            while ($j >= 0 && $list[$j]->getVictory() < $currentVictory) {
                $list[$j+1] = $list[$j];
                $j--;
            }
            $list[$j+1] = $currentUser;
        }
        for ($i = 0; $i < count($list); $i++) {
            $list[$i]->setRank($i+1);
            $entityManager->persist($list[$i]);
        }
        $entityManager->flush();




        return $this->render('admin/index.html.twig', [
            'infos'=>$infos
        ]);
    }




    #[Route('/admin/edit/{id}', name: 'app_admin_edit')]
    public function edit(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        $infos = $entityManager->getRepository(Infos::class)->find($id);
        if (!$infos) {
            throw $this->createNotFoundException(
                'No Infos found for id '.$id
            );
        }

        $victory = $request->request->get('victory');
        $defeat = $request->request->get('defeat');





        if ($victory) {
            $infos->setVictory($victory);
            //$infos->setRank();

        }
        if($defeat){
            $infos->setDefeat($defeat);
        }
        $entityManager->flush();






        return $this->redirectToRoute('app_admin', [
            'id'=>$infos->getId()
        ]);



    }

}


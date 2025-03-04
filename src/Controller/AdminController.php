<?php

namespace App\Controller;

use App\Entity\Infos;
use App\Entity\User;
use App\Security\Voter\RolesVoter;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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

        return $this->render('admin/index.html.twig', [
            'infos'=>$infos
        ]);
    }




    #[Route('/admin/edit/{id}', name: 'app_admin_edit')]
    public function edit(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        $infos = $entityManager->getRepository(Infos::class)->find($id);


        $victory = $request->request->get('victory');
        $defeat = $request->request->get('defeat');
        $rank = $request->request->get('rank');


        if ($victory) {
            $infos->setVictory($victory);

        }
        if($defeat){
            $infos->setDefeat($defeat);
        }
        if($rank){
            $infos->setRank($rank);
        }
        $entityManager->flush();



        return $this->redirectToRoute('app_admin', [
            'id'=>$infos->getId()
        ]);

    }

    #[Route('/admin/import', name: 'app_admin_import')]
    public function import(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {


        $file = $request->files->get('data_csv');


        $pathname = $file->getPathname();
        $firstline =0;
        $cansend = true;
        if (($gestion = fopen($pathname, "r")) !== false) {
            while (($data = fgetcsv($gestion, 10000, ";")) !== FALSE) {

                if ($firstline == 0) {
                    $firstline++;
                    continue;
                }

                $findDuplicate = $entityManager->getRepository(User::class)->find($data[0]);
                if($findDuplicate){
                    continue;
                }
                else{
                    $cansend = false;
                }


                $em = $entityManager;
                $users = new User();
                $infos = new Infos();

                $users->setUsername($data[0]);
                $users->setEmail($data[1]);
                $users->setPassword($data[2]);
                $plainPassword = $data[2];
                $users->setPassword($userPasswordHasher->hashPassword($users, $plainPassword));
                $users->setRoles([$data[3]]); // Symfony attend un tableau pour les rôles
                $infos->setUser($users);
                $infos->setRank($data[4]);

                $infos->setVictory($data[5]);
                $infos->setDefeat($data[6]);

                $em->persist($users);
                $em->persist($infos);
            }
            fclose($gestion);
            if($cansend){
                $em->flush();
            }

        }


        return $this->redirectToRoute('app_admin', []);
    }


}


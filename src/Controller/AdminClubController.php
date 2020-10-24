<?php

namespace App\Controller;

use App\Entity\Club;
use App\Form\ClubType;
use App\Entity\Championnat;
use App\Repository\ClubRepository;
use App\Repository\UserRepository;
use App\Repository\SujetRepository;
use App\Repository\RencontreRepository;
use App\Repository\ClassementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminClubController extends AbstractController
{
    public function create(EntityManagerInterface $manager, Request $request) 
    {
        $club = new Club();

        $form = $this->createForm(ClubType::class, $club);

        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid()){ 
            $manager->persist($club);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le club <strong>{$club->getNom()}</strong> a bien été enregistrée !"
            );

            return $this->redirectToRoute('admin_clubs_create');
        }

        return $this->render('admin/club/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function edit(Club $club, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(ClubType::class, $club);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($club);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le club <strong>{$club->getNom()}</strong> a bien été enregistré"
            );
        }

        return $this->render('admin/club/edit.html.twig', [
            'club' => $club,
            'form' => $form->createView()
        ]);
    }

    public function delete(Club $club, EntityManagerInterface $manager, UserRepository $repoUser, RencontreRepository $repoRencontre, ClassementRepository $repoClassement, 
                            SujetRepository $repoSujet){

        $users = $repoUser->findByClubs($club);
        $rencontresDom = $repoRencontre->findByClubDom($club);
        $rencontresExt = $repoRencontre->findByClubExt($club);
        $classements = $repoClassement->findByClubs($club);
        $sujets = $repoSujet->findByClubs($club);

        foreach($users as $user)
        {
            $user->setClubs(null);
        }
        foreach($rencontresDom as $dom)
        {
            $manager->remove($dom);
        }
        foreach($rencontresExt as $ext)
        {
            $manager->remove($ext);
        }
        foreach($classements as $classement)
        {
            $manager->remove($classement);
        }
        foreach($sujets as $sujet)
        {
            $sujet->setClubs(null);
            $sujet->setChampionnats(null);
        }

        $club->setChampionnats(new Championnat());

        $manager->remove($club);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le club <strong>{$club->getNom()}</strong> a bien été supprimé !"
        );
        
    return $this->redirectToRoute('admin_clubs_index');
}
}

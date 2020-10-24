<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\User;
use App\Entity\Sujet;
use App\Entity\Reponse;
use App\Form\SujetType;
use App\Form\ReponseType;
use App\Entity\Championnat;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Repository\SujetRepository;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SignalementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModerateurController extends AbstractController
{
    public function editSujet(Sujet $sujet, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(SujetType::class, $sujet);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($sujet);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le sujet <strong>{$sujet->getTitre()}</strong> a bien été édité"
            );
        }

        return $this->render('moderateur/sujet/edit.html.twig', [
            'sujet' => $sujet,
            'form' => $form->createView()
        ]);
    }

    public function deleteSujet(Sujet $sujet, EntityManagerInterface $manager, ReponseRepository $repoReponse, SignalementRepository $repoSignal){
        $reponses = $repoReponse->findBySujets($sujet);
        $signalements = $repoSignal->findBySujets($sujet);

        foreach($reponses as $reponse)
        {
            $manager->remove($reponse);
        }
        
        foreach($signalements as $signalement)
        {
            $manager->remove($signalement);
        }

        $sujet->setUsers(new User());
        $sujet->setChampionnats(new Championnat());
        $sujet->setClubs(new Club());
        
        $manager->remove($sujet);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le sujet <strong>{$sujet->getTitre()}</strong> a bien été supprimé !"
        );
        
        return $this->redirectToRoute('moderateur_signalement');
    }

    public function editReponse(Reponse $reponse, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(ReponseType::class, $reponse);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($reponse);
            $manager->flush();

            $this->addFlash(
                'success',
                "La réponse a bien été éditée"
            );
        }

        return $this->render('moderateur/reponse/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView()
        ]);
    }

    public function deleteReponse(Reponse $reponse, EntityManagerInterface $manager, SignalementRepository $repoSignal){
        $reponse->setSujets(new Sujet());

        $signalements = $repoSignal->findByReponses($reponse);

        foreach($signalements as $signalement)
        {
            $manager->remove($signalement);
        }

        $manager->remove($reponse);
        $manager->flush();

        $this->addFlash(
            'success',
            "La réponse <strong>{$reponse->getId()}</strong> a bien été supprimée !"
        );
        
        return $this->redirectToRoute('moderateur_signalement');
    }

    public function indexSignalement(SignalementRepository $repoSignal){
        $signalementsSuj = $repoSignal->findSignalementsSujets();
        $signalementsRep = $repoSignal->findSignalementsReponses();

        return $this->render('moderateur/signalement/index.html.twig', [
            'signalementsSuj' => $signalementsSuj,
            'signalementsRep' => $signalementsRep
        ]);
    }    

    public function sujetSignalement($id, SignalementRepository $repoSignal, SujetRepository $repoSujet){

        $sujet = $repoSujet->findOneById($id);
        $signalements = $repoSignal->findBySujets($id);

        return $this->render('moderateur/signalement/sujet.html.twig', [
            'signalements' => $signalements,
            'sujet' => $sujet
        ]);
    }

    public function reponseSignalement($id, SignalementRepository $repoSignal, ReponseRepository $repoReponse){

        $reponse = $repoReponse->findOneById($id);
        $signalements = $repoSignal->findByReponses($id);

        return $this->render('moderateur/signalement/reponse.html.twig', [
            'signalements' => $signalements,
            'reponse' => $reponse
        ]);
    }


    public function sujetApprove(Request $request, SignalementRepository $repoSignal, SujetRepository $repoSujet, EntityManagerInterface $manager)
    {
        $suj = $request->get("id");

        $sujet = $repoSujet->findOneById($suj);

        $signalements = $repoSignal->findBySujets($sujet);

        foreach($signalements as $signalement)
        {
            $signalement->setEtat(1);
            $manager->persist($signalement);
        }

        $manager->flush();

        $this->addFlash(
            'success',
            "Le sujet a été approuvé !"
        );
        return $this->redirectToRoute('moderateur_signalement');
    }

    public function reponseApprove(Request $request, SignalementRepository $repoSignal, ReponseRepository $repoReponse, EntityManagerInterface $manager)
    {
        $rep = $request->get("id");

        $reponse = $repoReponse->findOneById($rep);

        $signalements = $repoSignal->findByReponses($reponse);

        foreach($signalements as $signalement)
        {
            $signalement->setEtat(1);
            $manager->persist($signalement);
        }

        $manager->flush();

        $this->addFlash(
            'success',
            "La réponse a été approuvée !"
        );
        return $this->redirectToRoute('moderateur_signalement');
    }

    public function indexBanni(RoleRepository $repoRole)
    {
        return $this->render('moderateur/banni/index.html.twig', [
            'bannis' => $repoRole->findByTitle('ROLE_BANNED')
        ]);
    }

    public function deleteBanni(User $user, EntityManagerInterface $manager, RoleRepository $repoRole){
        $role = $repoRole->findOneByTitle('ROLE_BANNED');

        $user->removeUserRole($role);

        $manager->persist($user);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'User <strong>{$user->getPseudo()}</strong> a bien été débanni !"
        );
        
        return $this->redirectToRoute('moderateur_ban_index');
    }

    public function createBanni(Request $request, UserRepository $repoUser, EntityManagerInterface $manager, RoleRepository $repoRole) 
    {
        $userId = $request->get("userId");

        $user = $repoUser->findOneBySlug($userId);
        $role = $repoRole->findOneByTitle('ROLE_BANNED');
        
        $user->addUserRole($role);

        $manager->persist($user);

        $manager->flush();

        return $this->json('User banni'); 
    }
}

<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\Pays;
use App\Entity\User;
use App\Entity\Message;
use App\Form\AccountType;
use App\Entity\Conversation;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use App\Repository\SujetRepository;
use App\Repository\MessageRepository;
use App\Repository\ReponseRepository;
use App\Repository\DemandeAmiRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ParticipantRepository;
use App\Repository\SignalementRepository;
use App\Repository\ConversationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUserController extends AbstractController
{
    public function edit(User $user, Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder){
        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le user <strong>{$user->getPseudo()}</strong> a bien été enregistré"
            );
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    public function delete(User $user, EntityManagerInterface $manager, ReponseRepository $repoReponse, SujetRepository $repoSujet, 
                            ParticipantRepository $repoParticip, MessageRepository $repoMess, DemandeAmiRepository $repoDemande, ConversationRepository $repoConvers,
                            SignalementRepository $repoSignal){

        $sujets = $repoSujet->findByUsers($user);
        $reponses = $repoReponse->findByUsers($user);
        $participants = $repoParticip->findByUsers($user);
        $messages = $repoMess->findByUsers($user);
        $amis = $repoDemande->findListeAmis($user->getSlug());
        $signalements = $repoSignal->findByUsers($user);

        foreach($signalements as $signalement)
        {
            $manager->remove($signalement);
        }

        foreach($sujets as $sujet)
        {
            $sujet->setUsers(null);
        }
        foreach($reponses as $reponse)
        {
            $reponse->setUsers(null);
        }

        // Toutes les participations du User qu'on supprime
        foreach($participants as $participant)
        {
            // Tous les participants de la conversation 
            $conversParticip = $participant->getConversations()->getParticipants();
            // Tous les messages de la conversation
            $conversMess = $repoMess->findByConversations($participant->getConversations());
            // La conversation
            $conversas = $participant->getConversations();

            foreach($conversMess as $mess)
            {
                $mess->getConversations()->setDernierMess(null);
                $mess->setConversations(new Conversation);
                $mess->setUsers(new User);

                $manager->remove($mess);
            }

            foreach($conversParticip as $particip)
            {
                $particip->setUsers(new User());
                $particip->setConversations(new Conversation());

                $manager->remove($particip);
            }
    
            $manager->flush();
            
            $conversas->setDernierMess(null);
            
            $manager->remove($conversas);
        }
        
        foreach($amis as $ami)
        {
            $ami->setUserAjout(null);
            $ami->setUserRec(null);

            $manager->remove($ami);
        }

        $user->setClubs(new Club());
        $user->setPayss(new Pays());

        $manager->remove($user);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le user <strong>{$user->getPseudo()}</strong> a bien été supprimé !"
        );
        
    return $this->redirectToRoute('admin_users_index');
}
}

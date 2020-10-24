<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use App\Form\ConversType;
use App\Entity\Participant;
use App\Entity\Conversation;
use App\Form\ConversationType;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ParticipantRepository;
use App\Repository\ConversationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminConversationController extends AbstractController
{
    public function create(EntityManagerInterface $manager, Request $request, ConversationRepository $repoConversation) 
    {
        $conversation = new Conversation();
        $message = new Message();
        $participantUn = new Participant();
        $participantDeux = new Participant();
    
        $form = $this->createForm(ConversationType::class);

        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid()){ 
            $data = $form->getData();

            $existe = $repoConversation->findConversationByParticipants($data['Premier_Participant']->getId(), $data['Deuxieme_Participant']->getId());

            if($data['Premier_Participant'] == $data['Deuxieme_Participant'])
            {
                $this->addFlash(
                    'danger',
                    "Les deux participants ne peuvent pas être identiques !"
                );

                return $this->redirectToRoute('admin_conversations_create');
            }
            elseif($existe)
            {
                $this->addFlash(
                    'danger',
                    "Une conversation entre ces deux utilisateurs existe déjà."
                );

                return $this->redirectToRoute('admin_conversations_create');
            }
            else{
                $message->setContenu('Début de la conversation');
                $message->setUsers($data['Premier_Participant']);
                $message->setConversations($conversation);

                $conversation->setDernierMess($message);

                $participantUn->setConversations($conversation);
                $participantUn->setUsers($data['Premier_Participant']);
                
                $participantDeux->setConversations($conversation);
                $participantDeux->setUsers($data['Deuxieme_Participant']);

                $manager->persist($conversation);
                $manager->persist($participantUn);
                $manager->persist($participantDeux);
                $manager->persist($message);

                $manager->flush();

                $this->addFlash(
                    'success',
                    "La conversation <strong>{$conversation->getId()}</strong> a bien été enregistrée !"
                );

                return $this->redirectToRoute('admin_conversations_create');
            }
        }

        return $this->render('admin/conversation/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function delete(Conversation $conversation, EntityManagerInterface $manager, ConversationRepository $repoConv, ParticipantRepository $repoParticip,
                            MessageRepository $repoMessage){

        $participants = $repoParticip->findByConversations($conversation);
        $messages = $repoMessage->findByConversations($conversation);

        foreach($participants as $participant)
        {
            $participant->setConversations(new Conversation());
            $participant->setUsers(new User());

            $manager->remove($participant);

        }
        foreach($messages as $message)
        {
            $message->getConversations()->setDernierMess(null);
            $message->setConversations(new Conversation());
            $message->setUsers(new User());

            $manager->remove($message);
        }

        $manager->flush();
        
        $conversation->setDernierMess(null);

        $manager->remove($conversation);
        $manager->flush();

        $this->addFlash(
            'success',
            "La conversation <strong>{$conversation->getId()}</strong> a bien été supprimée !"
        );
        
    return $this->redirectToRoute('admin_conversations_index');
    }
}

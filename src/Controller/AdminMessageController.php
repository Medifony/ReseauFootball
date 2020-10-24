<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use App\Form\MessageType;
use App\Entity\Conversation;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ParticipantRepository;
use App\Repository\ConversationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminMessageController extends AbstractController
{
    public function create(EntityManagerInterface $manager, Request $request, ParticipantRepository $repoParticipant) 
    {
        $message = new Message();

        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid()){
            $existe = $repoParticipant->findBy(array('users' => $message->getUsers(), 'conversations' => $message->getConversations()));
 
            if($existe)
            {
                $message->getConversations()->setDernierMess($message);
                $manager->persist($message);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Le message <strong>{$message->getContenu()}</strong> a bien été enregistré !"
                );

                return $this->redirectToRoute('admin_messages_create');
            }
            else
            {
                $this->addFlash(
                    'danger',
                    "Le user <strong>{$message->getUsers()->getPseudo()}</strong> ne fait pas partie de la conversation !"
                );

                return $this->redirectToRoute('admin_messages_create');
            }
        }

        return $this->render('admin/message/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function edit(Message $message, Request $request, EntityManagerInterface $manager, ParticipantRepository $repoParticipant){
        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $existe = $repoParticipant->findBy(array('users' => $message->getUsers(), 'conversations' => $message->getConversations()));

            if($existe)
            {
                $manager->persist($message);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Le message <strong>{$message->getContenu()}</strong> a bien été enregistré"
                );
            }
            else
            {
                $this->addFlash(
                    'danger',
                    "Le user <strong>{$message->getUsers()->getPseudo()}</strong> ne fait pas partie de la conversation !"
                );
            }
        }

        return $this->render('admin/message/edit.html.twig', [
            'message' => $message,
            'form' => $form->createView()
        ]);
    }

    public function delete(Message $message, EntityManagerInterface $manager){
        if($message->getConversations() != null){
        $messageSupp = new Message();
        $messageSupp->setUsers($message->getUsers());
        $messageSupp->setConversations($message->getConversations());
        $messageSupp->setContenu('Message supprimé');

        $manager->persist($messageSupp);

        $conversation = $message->getConversations();
        $conversation->setDernierMess($messageSupp);

        $manager->persist($conversation);
        }

        $message->setUsers(new User());
        $message->setConversations(new Conversation());

        $manager->remove($message);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le message <strong>{$message->getContenu()}</strong> a bien été supprimé !"
        );
        
    return $this->redirectToRoute('admin_messages_index');
}
}

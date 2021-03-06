<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Conversation;
use App\Repository\UserRepository;
use App\Repository\MessageRepository;
use Symfony\Component\Mercure\Update;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ParticipantRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    
    const ATTRIBUTES_TO_SERIALIZE = ['id', 'contenu', 'contDate', 'mine', 'users' => ['filename']];

    private $entityManager;
    
    private $messageRepository;
    
    private $participantRepository;

    private $publisher;

    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager, 
                                MessageRepository $messageRepository,
                                ParticipantRepository $participantRepository,
                                PublisherInterface $publisher,
                                UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->messageRepository = $messageRepository;
        $this->participantRepository = $participantRepository;
        $this->publisher = $publisher;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/messages/{id}", name="messages.getMessages", methods={"GET"})
     */
    public function index(Request $request, Conversation $conversation)
    {
        $this->denyAccessUnLessGranted('view', $conversation);
        
        $messages = $this->messageRepository->findMessageByConversationId(
            $conversation->getId()
        );

        array_map(function($message){
            $message->setMine(
                $message->getUsers()->getId() === $this->getUser()->getId()
                ? true: false
            );
        }, $messages);

        return $this->json($messages, Response::HTTP_OK, [], [
            'attributes' => self::ATTRIBUTES_TO_SERIALIZE
        ]);
    }

    /**
     * @Route("/messages/{id}", name="messages.newMessage", methods={"POST"})
     */
    public function newMessage(Request $request, Conversation $conversation, SerializerInterface $serializer){
        $user = $this->getUser();
        
        $recipient = $this->participantRepository->findParticipantByConversationIdAndUserId(
            $conversation->getId(),
            $user->getId()
        );


        $content = $request->get('contenu', null);

        $message = new Message();
        $message->setContenu($content);
        $message->setUsers($user);
        
        $conversation->addMessage($message);
        $conversation->setDernierMess($message);

        $this->entityManager->getConnection()->beginTransaction();
        try{
            $this->entityManager->persist($message);
            $this->entityManager->persist($conversation);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
        $message->setMine(false);
        
        $messageSerialized = $serializer->serialize($message, 'json', [
            'attributes' => ['id', 'contenu', 'contDate', 'mine', 'conversations' => ['id'], 'users' => ['filename']]
        ]);

        $update = new Update(
            [
                sprintf("/conversations/%s", $conversation->getId()),
                sprintf("/conversations/%s", $recipient->getUsers()->getPseudo()),
            ],
            $messageSerialized,
            true
            // sprintf("/%s", $recipient->getUsers()->getPseudo())
            
        );

        $this->publisher->__invoke($update);

        $message->setMine(true);

        return $this->json($message, Response::HTTP_CREATED, [], [
            'attributes' => self::ATTRIBUTES_TO_SERIALIZE
        ]);
    }
}

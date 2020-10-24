<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Participant;
use App\Entity\Conversation;
use App\Repository\UserRepository;
use Symfony\Component\WebLink\Link;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ParticipantRepository;
use App\Repository\ConversationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConversationController extends AbstractController
{
    private $userRepository;

    private $entityManager;

    private $conversationRepository;

    public function __construct(UserRepository $userRepository, 
                                EntityManagerInterface $entityManager,
                                ConversationRepository $conversationRepository)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->conversationRepository = $conversationRepository;
    }
    /**
     * @Route("/conversations/{slug}", name="newConversations", methods={"GET"})
     */
    public function index(Request $request, $slug)
    {

        $otherUser = $this->userRepository->findOneBySlug($slug);

        if(is_null($otherUser)) {
            throw new \Exception("L'utilisateur n'a pas été trouvé");
        }

        if($otherUser->getId() == $this->getUser()->getId()){
            throw new \Exception("Vous ne pouvez pas créer une conversation avec vous même.");
        }

        $conversation = $this->conversationRepository->findConversationByParticipants(
            $otherUser->getId(),
            $this->getUser()->getId()
        );

        if(count($conversation)){
            return $this->redirectToRoute('message_page');
        }

        $conversation = new Conversation();
        
        $message = new Message();
        $message->setContenu("Début de la conversation");
        $message->setUsers($this->getUser());
        
        $conversation->addMessage($message);
        $conversation->setDernierMess($message);

        $participant = new Participant();
        $participant->setUsers($this->getUser());
        $participant->setConversations($conversation);

        $otherParticipant = new Participant();
        $otherParticipant->setUsers($otherUser);
        $otherParticipant->setConversations($conversation);
        
        $this->entityManager->getConnection()->beginTransaction();
        try{
            $this->entityManager->persist($conversation);
            $this->entityManager->persist($message);
            $this->entityManager->persist($participant);
            $this->entityManager->persist($otherParticipant);

            $this->entityManager->flush();
            $this->entityManager->commit();

        } catch(\Exception $e){
            $this->entityManager->rollback();
            throw $e;
        }

        return $this->redirectToRoute('message_page');
    }

    /**
     * @Route("/conversations", name="getConversations", methods={"GET"})
     */
    public function getConvs(Request $request){
        $conversations = $this->conversationRepository->findConversationsByUser($this->getUser()->getId());

        $hubUrl = $this->getParameter('mercure.default_hub');

        $this->addLink($request, new Link('mercure', $hubUrl));
        return $this->json($conversations);
    }

}

<?php

namespace App\Controller;

use App\Repository\ClubRepository;
use App\Repository\PaysRepository;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Repository\SujetRepository;
use App\Repository\MessageRepository;
use App\Repository\ReponseRepository;
use App\Repository\RencontreRepository;
use App\Repository\ClassementRepository;
use App\Repository\DemandeAmiRepository;
use App\Repository\ChampionnatRepository;
use App\Repository\SignalementRepository;
use App\Repository\ConversationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAllController extends AbstractController
{
    public function indexSignalement(SignalementRepository $repoSignal, Request $request, PaginatorInterface $paginator)
    {
        $signalements = $paginator->paginate(
            $repoSignal->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/signalement/index.html.twig', [
            'signalements' => $signalements,
        ]);
    }

    public function indexRencontre(RencontreRepository $repoRencontre, Request $request, PaginatorInterface $paginator)
    {
        $rencontres = $paginator->paginate(
            $repoRencontre->findBy([], array('matchDate' => 'asc')),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/rencontre/index.html.twig', [
            'rencontres' => $rencontres,
        ]);
    }

    public function indexClassement(ClassementRepository $repoClassement, Request $request, PaginatorInterface $paginator)
    {
        $classements = $paginator->paginate(
            $repoClassement->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/classement/index.html.twig', [
            'classements' => $classements
        ]);
    }

    public function indexRole(RoleRepository $repoRole)
    {
        return $this->render('admin/role/index.html.twig', [
            //'roles' => $repoRole->findBy(array('title' => 'ROLE_ADMIN'), array('title' => 'asc',))
            'moderateurs' => $repoRole->findByTitle('ROLE_MODERATEUR'),
            'admins' => $repoRole->findByTitle('ROLE_ADMIN'),
            'bannis' => $repoRole->findByTitle('ROLE_BANNED')
        ]);
    }

    public function indexChamp(ChampionnatRepository $repoChamp, Request $request, PaginatorInterface $paginator)
    {
        $championnats = $paginator->paginate(
            $repoChamp->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/championnat/index.html.twig', [
            'championnats' => $championnats
        ]);
    }

    public function indexSujet(SujetRepository $repoSujet, Request $request, PaginatorInterface $paginator)
    {
        $sujets = $paginator->paginate(
            $repoSujet->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/sujet/index.html.twig', [
            'sujets' => $sujets
        ]);
    }

    public function indexReponse(ReponseRepository $repoReponse, Request $request, PaginatorInterface $paginator)
    {
        $reponses = $paginator->paginate(
            $repoReponse->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/reponse/index.html.twig', [
            'reponses' => $reponses
        ]);
    }

    public function indexClub(ClubRepository $repoClub, Request $request, PaginatorInterface $paginator)
    {
        $clubs = $paginator->paginate(
            $repoClub->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/club/index.html.twig', [
            'clubs' => $clubs
        ]);
    }

    public function indexUser(UserRepository $repoUser, Request $request, PaginatorInterface $paginator)
    {
        $users = $paginator->paginate(
            $repoUser->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/user/index.html.twig', [
            'users' => $users
        ]);
    }

    public function indexMessage(MessageRepository $repoMessage, Request $request, PaginatorInterface $paginator)
    {
        $messages = $paginator->paginate(
            $repoMessage->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/message/index.html.twig', [
            'messages' => $messages
        ]);
    }

    public function indexConversation(ConversationRepository $repoConvers, Request $request, PaginatorInterface $paginator)
    {
        $conversations = $paginator->paginate(
            $repoConvers->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/conversation/index.html.twig', [
            'conversations' => $conversations
        ]);
    }

    public function indexPays(PaysRepository $repoPays, Request $request, PaginatorInterface $paginator)
    {
        $payss = $paginator->paginate(
            $repoPays->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/pays/index.html.twig', [
            'payss' => $payss
        ]);
    }

    public function indexAmi(DemandeAmiRepository $repoAmi, Request $request, PaginatorInterface $paginator)
    {
        $amis = $paginator->paginate(
            $repoAmi->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/demandeAmi/index.html.twig', [
            'amis' => $amis
        ]);
    }
}

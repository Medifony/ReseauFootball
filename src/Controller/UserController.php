<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\SujetRepository;
use App\Repository\ReponseRepository;
use App\Repository\DemandeAmiRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    public function index(User $user, SujetRepository $repoSujet, ReponseRepository $repoRep, DemandeAmiRepository $repoDem)
    {
        $sujet = $repoSujet->findBy(array('users' => $user), array('sujetDate' => 'desc'));
        $reponse = $repoRep->findBy(array('users' => $user), array('repDate' => 'desc'));

        $totDemandes = $repoDem->findTotal($user->getSlug());
        $totSujet = $repoSujet->totSuj($user->getId());
        $totReponse = $repoRep->totRep($user->getId());

        $total = $totSujet + $totReponse;

        if($this->getUser() != null){
            $checkAmis = $repoDem->findListeAmis($this->getUser()->getSlug());
        }
        else
        {
            $checkAmis = 'aucun';
        }

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'sujets' => $sujet,
            'reponses' => $reponse,
            'total' => $total,
            'checkAmis' => $checkAmis,
            'totDemandes' => $totDemandes
        ]);
    }
}

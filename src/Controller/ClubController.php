<?php

namespace App\Controller;

use App\Repository\ClubRepository;
use App\Repository\SujetRepository;
use App\Repository\ChampionnatRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClubController extends AbstractController
{
    public function index($slugCh, $slugCl, ClubRepository $repoClub, ChampionnatRepository $repoChamp, SujetRepository $repoSujet)
    {
        $champ = $repoChamp->findOneBySlug($slugCh);

        $club = $repoClub->findBy(array('slug' => $slugCl,
                                 'championnats' => $champ));

        $sujet = $repoSujet->findBy(array('championnats' => $champ, 'clubs' => $club[0]), array('dateModif' => 'desc',), 100, 0);

        return $this->render('club/index.html.twig', [
            'clubs' => $club,
            'champ' => $champ,
            'sujets' => $sujet
        ]);
    }
}

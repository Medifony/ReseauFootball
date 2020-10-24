<?php

namespace App\Controller;

use DateTime;
use App\Repository\SujetRepository;
use App\Repository\RencontreRepository;
use App\Repository\ChampionnatRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChampionnatController extends AbstractController
{
    public function index($slug, ChampionnatRepository $repoChamp, SujetRepository $repoSujet, RencontreRepository $repoRenc)
    {
        $champ = $repoChamp->findOneBySlug($slug);
        $sujet = $repoSujet->findBy(array('championnats' => $champ, 'clubs' => null), array('dateModif' => 'desc',), 100, 0);

        $today = new DateTime("today");
        $tomorrow = new DateTime("tomorrow");

        $rencontres = $repoRenc->findMatchsToday($champ->getSlug(), $today->format('Y-m-d H:i:s'), $tomorrow->format('Y-m-d H:i:s'));

        return $this->render('championnat/index.html.twig', [
            'champ' => $champ,
            'sujets' => $sujet,
            'rencontres' => $rencontres
        ]);
    }
}

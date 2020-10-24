<?php

namespace App\Controller;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use App\Repository\SujetRepository;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Symfony\Component\Mercure\Update;
use App\Repository\ChampionnatRepository;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReseauController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(ChampionnatRepository $repoChamp, SujetRepository $repoSujet)
    {
        $liga = $repoChamp->findOneBySlug('la-liga');
        $sujetsLiga = $repoSujet->findBy(array('championnats' => $liga), array('dateModif' => 'desc',), 5, 0);

        $premL = $repoChamp->findOneBySlug('premier-league');
        $sujetsPl = $repoSujet->findBy(array('championnats' => $premL), array('dateModif' => 'desc',), 5, 0);

        $serieA = $repoChamp->findOneBySlug('serie-a');
        $sujetsSerieA = $repoSujet->findBy(array('championnats' => $serieA), array('dateModif' => 'desc',), 5, 0);

        $bundesliga = $repoChamp->findOneBySlug('bundesliga');
        $sujetsBundes = $repoSujet->findBy(array('championnats' => $bundesliga), array('dateModif' => 'desc',), 5, 0);

        $ligue1 = $repoChamp->findOneBySlug('ligue-1');
        $sujetsLigue1 = $repoSujet->findBy(array('championnats' => $ligue1), array('dateModif' => 'desc',), 5, 0);

        return $this->render('reseau/home.html.twig', [
            'liga' => $liga,
            'sujetsLiga' => $sujetsLiga,
            'premL' => $premL,
            'sujetsPl' => $sujetsPl,
            'serieA' => $serieA,
            'sujetsSerieA' => $sujetsSerieA,
            'bundesliga' => $bundesliga,
            'sujetsBundes' => $sujetsBundes,
            'ligueUn' => $ligue1,
            'sujetsLigueUn' => $sujetsLigue1
        ]);


    }
}

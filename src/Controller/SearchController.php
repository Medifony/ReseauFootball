<?php

namespace App\Controller;

use App\Form\SearchUserType;
use App\Form\SearchSujetType;
use App\Repository\UserRepository;
use App\Repository\SujetRepository;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    public function index(EntityManagerInterface $manager, Request $request)
    {
        $formUser = $this->createForm(SearchUserType::class);

        $formUser->handleRequest($request); 

        $formSujet = $this->createForm(SearchSujetType::class);

        $formSujet->handleRequest($request);

        return $this->render('search/index.html.twig', [
            'formUser' => $formUser->createView(),
            'formSujet' => $formSujet->createView()
        ]);
    }

    public function searchUser(Connection $connection, Request $request, UserRepository $repoUsers)
    {
        $formUser = $this->createForm(SearchUserType::class);

        $formUser->handleRequest($request); 

        $data = $formUser->getData();

        $pseudo = $data['pseudo'];
        $club = $data['Clubs'];
        $pays = $data['Pays'];

        $em = $this->getDoctrine()->getManager();

        if($club == null && $pays == null && $pseudo != null)
        {
            $query = 'SELECT * FROM user WHERE pseudo LIKE \'%' . addslashes($data['pseudo']) .'%\' ORDER BY LENGTH(pseudo)';
            $info = "Résultats pour la recherche de l'utilisateur \"" . $data['pseudo'] . "\"";
            
            if($pseudo == ("%") || $pseudo == "_")
                $query = null;
        }
        elseif($pseudo == null && $pays == null && $club != null)
        {
            $query = 'SELECT * FROM user WHERE clubs_id = ' . $data['Clubs']->getId() . ' ORDER BY LENGTH(pseudo)';
            $info = "Utilisateurs qui supportent le club \"" . $data['Clubs']->getNavBar() . "\":";
        }
        elseif($pseudo == null && $club == null && $pays != null)
        {
            $query = 'SELECT * FROM user WHERE payss_id = ' . $data['Pays']->getId() . ' ORDER BY LENGTH(pseudo)';
            $info = "Utilisateurs qui supportent le pays \"" . $data['Pays']->getNomFr() . "\":";
        }
        elseif($pays == null && $club != null && $pseudo != null)
        {
            $query = 'SELECT * FROM user WHERE pseudo LIKE \'%' . addslashes($data['pseudo']) .'%\' AND clubs_id = ' . $data['Clubs']->getId() . ' ORDER BY LENGTH(pseudo)';
            $info = "Utilisateurs  \"" . $data['pseudo'] . "\" qui supportent le club \"" . $data['Clubs']->getNavBar() . "\":";
            
            if($pseudo == ("%") || $pseudo == "_")
                $query = null;
        }
        elseif($club == null && $pseudo != null && $pays != null)
        {
            $query = 'SELECT * FROM user WHERE pseudo LIKE \'%' . addslashes($data['pseudo']) .'%\' AND payss_id = ' . $data['Pays']->getId() . ' ORDER BY LENGTH(pseudo)';
            $info = "Utilisateurs \"" . $data['pseudo'] . "\" qui supportent le pays \"" . $data['Pays']->getNomFr() . "\":";
            
            if($pseudo == ("%") || $pseudo == "_")
                $query = null;
        }
        elseif($pseudo == null && $club != null && $pays != null)
        {
            $query = 'SELECT * FROM user WHERE clubs_id = ' . $data['Clubs']->getId() .' AND payss_id = ' . $data['Pays']->getId() . ' ORDER BY LENGTH(pseudo)';
            $info = "Utilisateurs qui supportent le club \"" . $data['Clubs']->getNavBar() . "\" et le pays \"" . $data['Pays']->getNomFr() . "\":";
        }
        elseif($pseudo != null && $club != null && $pays != null)
        {
            $query = 'SELECT * FROM user WHERE pseudo LIKE \'%' . addslashes($data['pseudo']) .'%\' AND clubs_id = ' . $data['Clubs']->getId() .' AND payss_id = ' . $data['Pays']->getId() . ' ORDER BY LENGTH(pseudo)';
            $info = "Utilisateurs \"" . $data['pseudo'] . "\" qui supportent le club \"" . $data['Clubs']->getNavBar() . "\" et le pays \"" . $data['Pays']->getNomFr() . "\":";
            
            if($pseudo == ("%") || $pseudo == "_")
                $query = null;
        }

        if(!empty($query) && $query != null)
        {
            $statement = $connection->prepare($query);
            $statement->execute();

            $users = $statement->fetchAll();
            $userDef = array();

            foreach($users as $user)
            {
                array_push($userDef, $repoUsers->findOneById($user['id']));
            }
        }
        else
        {
            $userDef = null;
            $info = null;
        }

        
        return $this->render('search/user.html.twig', [
            'users' => $userDef,
            'info' => $info
        ]);  
    }

    public function searchSujet(Connection $connection, Request $request, SujetRepository $repoSujets)
    {
        $formSujet = $this->createForm(SearchSujetType::class);

        $formSujet->handleRequest($request); 

        $data = $formSujet->getData();

        $titre = $data['titre'];

        $em = $this->getDoctrine()->getManager();

        if($titre != null)
        {
            $query = 'SELECT * FROM sujet WHERE titre LIKE \'%' . addslashes($data['titre']) .'%\' ORDER BY LENGTH(titre), date_modif DESC';
            $info = "Résultats pour la recherche du sujet \"" . $data['titre'] . "\":";

            if($titre == ("%") || $titre == "_")
                $query = null;
        }

        if(!empty($query) && $query != null)
        {
            $statement = $connection->prepare($query);
            $statement->execute();

            $sujets = $statement->fetchAll();
            $sujetDef = array();

            foreach($sujets as $sujet)
            {
                array_push($sujetDef, $repoSujets->findOneById($sujet['id']));
            }
        }
        else
        {
            $sujetDef = null;
            $info = null;
        }
        
        return $this->render('search/sujet.html.twig', [
            'sujets' => $sujetDef,
            'info' => $info
        ]); 
    }
}

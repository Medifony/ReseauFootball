<?php

namespace App\Controller;

use App\Entity\Signalement;
use App\Form\SignalementType;
use App\Repository\SujetRepository;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SignalementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SignalementController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     */

    public function sujet(Request $request, $slug, SujetRepository $repoSujet, EntityManagerInterface $manager, SignalementRepository $repoSignalement)
    {
        $sujet = $repoSujet->findOneBySlug($slug);

        $signals = $repoSignalement->findBy(array('users' => $this->getUser(), 'sujets' => $sujet));
        
        if($signals == null)
        {

            $signalement = new Signalement();

            $form = $this->createForm(SignalementType::class, $signalement);

            $form->handleRequest($request); 

            if($form->isSubmitted() && $form->isValid()){ 
                $user = $this->getUser();

                $signalement->setSujets($sujet);
                $signalement->setUsers($user);
                $signalement->setEtat(0);

                $manager->persist($signalement);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Votre signalement a bien été envoyé et sera traité par notre modération !"
                );

                return $this->redirectToRoute('champ_page', ['slug' => '' . $sujet->getChampionnats()->getSlug() . '']);
            }


            return $this->render('signalement/sujet.html.twig', [
                'form' => $form->createView(),
                'sujet' => $sujet
            ]);
        }
        else
        {
            $this->addFlash(
                'danger',
                "Vous avez déjà signalé ce contenu !"
            );

            return $this->redirectToRoute('homepage');
        }
    }

    public function reponse(Request $request, $id, ReponseRepository $repoRep, EntityManagerInterface $manager, SignalementRepository $repoSignalement)
    {
        $reponse = $repoRep->findOneById($id);

        $signals = $repoSignalement->findBy(array('users' => $this->getUser(), 'reponses' => $reponse));

        if($signals == null)
        {
        $signalement = new Signalement();

        $form = $this->createForm(SignalementType::class, $signalement);

        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid()){ 
            $user = $this->getUser();

            $signalement->setReponses($reponse);
            $signalement->setUsers($user);
            $signalement->setEtat(0);

            $manager->persist($signalement);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre signalement a bien été envoyé et sera traité par notre modération !"
            );

            return $this->redirectToRoute('champ_page', ['slug' => '' . $reponse->getSujets()->getChampionnats()->getSlug() . '']);
        }

        return $this->render('signalement/reponse.html.twig', [
            'form' => $form->createView(),
            'reponse' => $reponse
        ]);
        }
        else
        {
            $this->addFlash(
                'danger',
                "Vous avez déjà signalé ce contenu !"
            );

            return $this->redirectToRoute('homepage');
        }
    }
}

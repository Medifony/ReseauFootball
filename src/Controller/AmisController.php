<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\DemandeAmi;
use App\Repository\UserRepository;
use App\Repository\DemandeAmiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AmisController extends AbstractController
{
    /**
     * @Route("/amis", name="amis")
     */
    public function index(Request $request, UserRepository $repoUser, EntityManagerInterface $manager, DemandeAmiRepository $repoAmi)
    {
        $userId = $request->get("userId");

        $user = $repoUser->findOneBySlug($userId);
        $checkAmi = $repoAmi->findIfAmis($user->getSlug(), $this->getUser()->getSlug());

        if($checkAmi == null)
        {
            $demandeAmi = new DemandeAmi();
            $demandeAmi->setEnvoiDate(new DateTime('now'));
            $demandeAmi->setUserAjout($this->getUser());
            $demandeAmi->setUserRec($user);

            $manager->persist($demandeAmi);
            $manager->flush();

            return $this->json('Ami ajouté');
        }
        else
        {
            return $this->json('Demande déjà envoyée');
        }
    }

    /**
     * @Route("/amisAccepte", name="amis_accepte")
     */

    public function accepte(Request $request, DemandeAmiRepository $repoDem, EntityManagerInterface $manager)
    {
        $demandeId = $request->get("demandeId");

        $demandeAmi = $repoDem->findOneById($demandeId);

        $demandeAmi->setAmiStatut(true);
        $demandeAmi->setValidationDate(new DateTime('now'));

        $manager->persist($demandeAmi);
        $manager->flush();

        return $this->json('Ami accepté');
    }

    /**
     * @Route("/amisRefuse", name="amis_refuse")
     */

    public function refuse(Request $request, DemandeAmiRepository $repoDem, EntityManagerInterface $manager)
    {
        $demandeId = $request->get("demandeId");

        $demandeAmi = $repoDem->findOneById($demandeId);

        $manager->remove($demandeAmi);
        $manager->flush();

        return $this->json('Ami refusé');
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    
    public function delete(DemandeAmi $demandeAmi, EntityManagerInterface $manager){
        if($demandeAmi->getUserAjout() == $this->getUser() OR $demandeAmi->getUserRec() == $this->getUser())
        {
            $demandeAmi->setUserAjout(new User());
            $demandeAmi->setUserRec(new User());

            $manager->remove($demandeAmi);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'ami <strong>{$demandeAmi->getId()}</strong> a bien été supprimé !"
            );
            
            return $this->redirectToRoute('account_friends');
        }
        else
        {
            $this->addFlash(
                'danger',
                "L'utilisateur ne fait pas partie de votre liste d'amis !"
            );
            
            return $this->redirectToRoute('account_friends');
        }
    }
}

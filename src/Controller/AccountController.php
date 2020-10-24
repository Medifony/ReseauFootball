<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\User;

use App\Entity\Sujet;
use App\Entity\Reponse;
use App\Form\AccountType;
use Lcobucci\JWT\Builder;
use App\Entity\Championnat;
use Lcobucci\JWT\Signer\Key;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use App\Repository\UserRepository;
use App\Repository\SujetRepository;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use App\Repository\ReponseRepository;
use Symfony\Component\Form\FormError;
use App\Repository\DemandeAmiRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SignalementRepository;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="account_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * @Route("/logout", name="account_logout")
     */
    public function logout(){
        
    }

    /**
     * @Route("/register", name="account_register")
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder){
        $user = new User();
        
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);
            
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre compte a bien été créé ! Vous pouvez maintenant vous connecter !"
            );

            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/account/profile", name="account_profile")
     * @IsGranted("ROLE_USER")
     */
    public function profile(Request $request, EntityManagerInterface $manager){
        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success', 
                "Les données du profil ont été enregistrées avec succès !"
            );
        }

        return $this->render('account/profile.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/account/password-update", name="account_password")
     * @IsGranted("ROLE_USER")
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, 
                                    EntityManagerInterface $manager){
        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();
        
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if(!password_verify($passwordUpdate->getOldPassword(), $user->getHash())){
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel !"));
            } else{
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifié !"
                );

                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/account", name="account_index")
     * @IsGranted("ROLE_USER")
     */

    public function myAccount(SujetRepository $repoSujet, ReponseRepository $repoRep, DemandeAmiRepository $repoDem){
        $sujet = $repoSujet->findBy(array('users' => $this->getUser()), array('sujetDate' => 'desc'));
        $reponse = $repoRep->findBy(array('users' => $this->getUser()), array('repDate' => 'desc'));
        
        $totDemandes = $repoDem->findTotal($this->getUser()->getSlug());
        $totSujet = $repoSujet->totSuj($this->getUser()->getId());
        $totReponse = $repoRep->totRep($this->getUser()->getId());

        $total = $totSujet + $totReponse;

        $checkAmis = $repoDem->findListeAmis($this->getUser()->getSlug());
    

        return $this->render('user/index.html.twig', [
            'user' => $this->getUser(),
            'sujets' => $sujet,
            'reponses' => $reponse,
            'total' => $total,
            'checkAmis' => $checkAmis,
            'totDemandes' => $totDemandes
        ]);
    }

    public function myMessages(){
        $username = $this->getUser()->getPseudo();
        $signer = new Sha256();
        $token = (new Builder())
            ->withClaim('mercure', ['subscribe' => [sprintf("/conversations/%s", $username)]])
            ->getToken(
                $signer,
                new Key($this->getParameter('mercure_secret_key'))
            )
        ;

        $response = $this->render('account/message.html.twig', [
        
        ]);

        $response->headers->setCookie(
            new Cookie(
                'mercureAuthorization',
                $token,
                (new \DateTime())
                ->add(new \DateInterval('PT2H')),
                '/.well-known/mercure',
                null,
                false,
                true,
                false,
                'strict'
            )
        );

        return $response;
    }

    /**
     * @Route("/account/friends", name="account_friends")
     * @IsGranted("ROLE_USER")
     */
    public function amis(DemandeAmiRepository $repoDem)
    {
        $demandeAmis = $repoDem->findBy(array('userRec' => $this->getUser(), 'amiStatut' => false), array('envoiDate' => 'desc'));

        $listeAmis = $repoDem->findListeAmis($this->getUser()->getSlug());

        return $this->render('account/amis.html.twig', [
            'demandes' => $demandeAmis,
            'listeAmis' => $listeAmis
        ]);
    }

    /**
     * @Route("/supprimePhoto", name="account_supprimePhoto")
     */
    public function supprimePhoto(Request $request, UserRepository $repoUser, EntityManagerInterface $manager)
    {
        $userId = $request->get("userId");

        $user = $repoUser->findOneBySlug($userId);

        $user->setFileName(null);

        $manager->persist($user);
        $manager->flush();

        return $this->json('Photo supprimée');
    }

    /**
     * @IsGranted("ROLE_USER")
     */

    public function deleteSujet(Sujet $sujet, EntityManagerInterface $manager, ReponseRepository $repoReponse, SignalementRepository $repoSignal){
        
        if($sujet->getUsers() == $this->getUser())
        {
            $reponses = $repoReponse->findBySujets($sujet);
            $signalements = $repoSignal->findBySujets($sujet);

            foreach($reponses as $reponse)
            {
                $manager->remove($reponse);
            }
            
            foreach($signalements as $signalement)
            {
                $manager->remove($signalement);
            }

            $sujet->setUsers(new User());
            $sujet->setChampionnats(new Championnat());
            $sujet->setClubs(new Club());
            
            $manager->remove($sujet);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le sujet <strong>{$sujet->getTitre()}</strong> a bien été supprimé !"
            );

            return $this->redirectToRoute('homepage');
        }
        else
        {
            $this->addFlash(
                'success',
                "Le sujet <strong>{$sujet->getTitre()}</strong> n'est pas le votre !"
            );

            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    public function deleteReponse(Reponse $reponse, EntityManagerInterface $manager, SignalementRepository $repoSignal){
        if($reponse->getUsers() == $this->getUser())
        {
            $reponse->setSujets(new Sujet());

            $signalements = $repoSignal->findByReponses($reponse);

            foreach($signalements as $signalement)
            {
                $manager->remove($signalement);
            }

            $manager->remove($reponse);
            $manager->flush();

            $this->addFlash(
                'success',
                "La réponse <strong>{$reponse->getId()}</strong> a bien été supprimée !"
            );
            
            return $this->redirectToRoute('homepage');
        }
        else
        {
            $this->addFlash(
                'success',
                "La réponse <strong>{$reponse->getId()}</strong> n'est pas la votre !"
            );

            return $this->redirectToRoute('homepage');
        }
    }
}

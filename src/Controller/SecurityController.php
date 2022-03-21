<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    #[Route('/admin/register', name: 'register')]
    public function register(Request $request, ManagerRegistry $manager, UserPasswordEncoderInterface $encoder): Response
    {
        //Creation d'un nouvel objet User
        $user = new User();
        //Creation du formulaire sur base d'un formulaire crée au préalable 
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        //Vérification de la conformité des données entrées par l'utilisateur
        if ($form->isSubmitted() && $form->isValid()) {
            //Chiffrement du mot de passe selon l'algorytme Bcrypt
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            if ($user->getAccountType() == ""){
                $user->setAccountType("ROLE_ADMIN");
            }
            $manager->getManager()->persist($user);
            //Envoie des données vers la base de données
            $manager->getManager()->flush();

            $this->addFlash("success", "Le compte à bien été créé");
            return $this->redirectToRoute('index');
        }
        
        return $this->render('security/register.html.twig', [
            //Envoie du formulaire à la vue
            'registrationForm' => $form->createView()
        ]);
    }

    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser() != null){
            return $this->redirectToRoute('userprofile', [
            'id' => $this->getUser()->getUsername()
        ]);
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('security/login.html.twig', [
            "error" => $error
        ]);
    }

    #[Route('/autoRedirect', name: 'autoRedirect')]
    public function autoRedirect()
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('error404');
        }

        return $this->redirectToRoute('userprofile', [
            'id' => $this->getUser()->getUsername()
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout()
    {
    }

    #[Route('/error404', name: 'error404')]
    public function error404()
    {
        return $this->render('error/error404.html.twig');
    }

    #[Route('/error403', name: 'error403')]
    public function error403()
    {
        return $this->render('error/error403.html.twig');
    }

    #[Route('/admin/userprofile/{id}', name: 'userprofile')]
    public function userprofile(User $user): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('error404');
        }

        if ($this->getUser()->getUsername() != $user->getId()) {
            return $this->redirectToRoute('error403');
        }
        

        return $this->render('/security/userprofile.html.twig', [
            'user' => $user
        ]);
    }
}
<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    /**
     * @Route("/register")
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                // encodage du mdp, nécessite la config "encoders" dans app/config/security.yml
                // et que la classe User implémente UserInterface
                $passwordEncoder = $this->get('security.password_encoder');
                $user->setPassword(
                    $passwordEncoder->encodePassword($user, $user->getPlainPassword())
                );
                
                // upload de l'image "avatar"
                // nécessite qu'existe le paramètre "upload_dir"
                // et que le répertoire web/upload soit créé
                // Le commentaire qui suit est indicatif,
                // il précise qu'on a dans $avatar une instance de "UploadedFile" ou null
                /** @var Symfony\Component\HttpFondation\UploadedFile|null */
                $avatar = $user->getAvatar();
                
                if (!is_null($avatar)) {
                    // on donne un nom unique au fichier que l'on va enregistrer
                    $fileName = md5(uniqid()) . '.' . $avatar->guessExtension();
                    
                    // gère le move_uploaded_file() vers notre répertoire d'upload
                    $avatar->move(
                        $this->getParameter('upload_dir_avatar'), // répertoire destination
                        $fileName // nom du fichier dans le répertoire destination
                    );
                    
                    // on va stocker le nom du fichier en bdd pour notre User
                    $user->setAvatar($fileName);
                }
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                
                $this->addFlash('success', 'Votre compte est créé');
                
                return $this->redirectToRoute('login');
            } else {
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }
        
        return $this->render(
            'security/register.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
    
    /**
     * @param Request $request
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        
        if($request->isMethod('POST') && is_null($error))
        {
            return $this->redirectToRoute('homepage');
        }
        
        $lastUsername = $authenticationUtils->getLastUserName();
        
        return $this->render('security/login.html.twig', 
        [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
}

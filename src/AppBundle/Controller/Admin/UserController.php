<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/users") 
 */
class UserController extends Controller
{

    /**
     * @Route("/list")
     */
    public function listUsersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findAll();
        
        return $this->render(
            'admin/users/list.html.twig',
            [
                'users' => $users,
            ]
        );
    }
    
    /**
     * @param int $id
     * @Route("/edit/{id}", defaults={"id": null})
     */
    public function editUsersAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        
        
        if (is_null($id)) { // création
            $new = true;
            $user = new User();
        } else { // modification
            $new = false;
            // raccourci pour $em->getRepository('AppBundle:User')->find($id)
            $user = $em->find('AppBundle:User', $id);
            
            // si il n'y a pas de user avec cet id en bdd,
            // on redirige vers la liste
            if (is_null($user)) {
                return $this->redirectToRoute('app_admin_user_listusers');
            }
        }
        
        // Vérification user connecté pour afficher le formulaire complet si il est admin
        $statut = null;
        if ($id == $this->getUser()->getId())
        {
        $statut = 'logged_user';
        }
        
        $roles = $this->getUser()->getRoles();
        $isFormAdmin = $statut != 'logged_user' && in_array('ROLE_ADMIN', $roles);
        $prevAvatar = $user->getAvatar();
        
        $form = $this->createForm(UserType::class, $user, ['role' => $roles, 'statut' => $statut]);
              
        $form->handleRequest($request); // le formulaire analyse la requête HTTP 
        
        if ($form->isSubmitted()) { // si le formulaire a été envoyé
            if ($form->isValid()) { // s'il n'y a pas eu d'erreur de validation du formulaire
                if (!empty($user->getPlainPassword())) {// si le mdp existe: pas besoin de le retaper pour valider le form
                    $passwordEncoder = $this->get('security.password_encoder');
                    $user->setPassword(
                        $passwordEncoder->encodePassword($user, $user->getPlainPassword())
                    );
                }
                /** @var Symfony\Component\HttpFondation\UploadedFile|null */
                $avatar = $user->getAvatar();
                
                if ($avatar instanceof UploadedFile) {
                    if (!is_string($avatar)){// évite guessExtension() error
                    // on donne un nom unique au fichier que l'on va enregistrer
                    $fileName = md5(uniqid()) . '.' . $avatar->guessExtension();
                    
                    // gère le move_uploaded_file() vers notre répertoire d'upload
                    $avatar->move(
                        $this->getParameter('upload_dir'), // répertoire destination
                        $fileName // nom du fichier dans le répertoire destination
                    );
                    
                    // on va stocker le nom du fichier en bdd pour notre User
                    $user->setAvatar($fileName);
                    }
                } else {
                    $user->setAvatar($prevAvatar);
                }
                
                $em->persist($user); //prépare l'enregistrement de l'object en bdd
                $em->flush(); // enregistre en bdd
                
                $msg = ($new)
                    ? 'L\'utilisateur a bien été créé'
                    : 'L\'utilisateur a bien été modifié'
                ;
                $this->addFlash('success', $msg);
                return $this->redirectToRoute('app_admin_user_listusers');
            } else {
                // ajoute un message flash
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }            
        }
        
        return $this->render(
            'admin/users/edit.html.twig',
            [
                'user' => $user,
                'new' => $new,
                'form' => $form->createView(),
                'form_admin' => $isFormAdmin,
            ]
        );
    }
    
    /**
     * @Route("/delete/{id}")
     */
    public function deleteUsersAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->find('AppBundle:User', $id);
        
        if (is_null($user)) {
            return $this->redirectToRoute('app_admin_user_listusers');
        }
        
        $em->remove($user);
        $em->flush();
        $this->addFlash('success', 'La rubrique a bien été supprimée');
        
        return $this->redirectToRoute('app_admin_user_listusers');
    }
}

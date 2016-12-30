<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
            // raccourci pour $em->getRepository('AppBundle:Category')->find($id)
            $user = $em->find('AppBundle:User', $id);
            
            // si il n'y a pas de catégorie avec cet id en bdd,
            // on redirige vers la liste
            if (is_null($user)) {
                return $this->redirectToRoute('app_admin_users_list');
            }
        }
        
        $form = $this->createForm(UserType::class, $user);
        
        $form->handleRequest($request); // le formulaire analyse la requête HTTP 
        
        if ($form->isSubmitted()) { // si le formulaire a été envoyé
            if ($form->isValid()) { // s'il n'y a pas eu d'erreur de validation du formulaire
                $em->persist($user); //prépare l'enregistrement de l'object en bdd
                $em->flush(); // enregistre en bdd
                
                $msg = ($new)
                    ? 'L\'utilisateur a bien été crée'
                    : 'L\'utilisateur a bien été modifié'
                ;
                $this->addFlash('success', $msg);
                return $this->redirectToRoute('app_admin_users_list');
            } else {
                // ajoute un message flash
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
            
        }
        
        return $this->render(
            'admin/users/edit.html.twig',
            [
                'new' => $new,
                'form' => $form->createView(),
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
            return $this->redirectToRoute('app_admin_users_list');
        }
        
        $em->remove($user);
        $em->flush();
        $this->addFlash('success', 'La rubrique a bien été supprimée');
        
        return $this->redirectToRoute('app_admin_users_list');
    }
}

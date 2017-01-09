<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Gestion des posts
 * 
 * @Route("/post")
 */
class PostController extends Controller
{
    /**
     * @Route("/list")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('AppBundle:Post')->findAll();
         
        return $this->render('admin/post/list.html.twig', 
        [
            'posts' => $posts,
        ]);
    }
     
    /**
     * @Route("/edit/{id}", defaults={"id": null})
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
         
        if(is_null($id))
        {
            $new= true;
            $post = new Post(); // On crée un objet de la classe Category du namespace AppBundle\Entity
            $post->setAuthor($this->getUser()); // On passe l'utilisateur connecté
        }
        else
        {
            $new = false;
            $post = $em->find('AppBundle:Post', $id);
             
            if(is_null($post))
            {
                // On redirige vers la route de la liste s'il n'y a pas de categories
                return $this->redirectToRoute('app_admin_post_list');
            }
        }
         
        $form = $this->createForm(PostType::class, $post);
        
        $form->handleRequest($request);
         
        if($form->isSubmitted())
        {
            if($form->isValid())
            {
                $em->persist($post);
                $em->flush();
                
                $msg = ($new)
                        ? 'L\'article a bien été crée'
                        : 'L\'article a bien été modifié'
                      ;
                $this->addFlash('success', $msg);
                return $this->redirectToRoute('app_admin_post_list');
            }
            else
            {
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }        
         
        return $this->render('admin/post/edit.html.twig', 
        [
            'new' => $new,
            'form' => $form->createView(),
        ]);
    }
     
    /**
     * @param int $id
     * @Route("/delete/{id}")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->find('AppBundle:Post', $id);
         
        if(is_null($post))
        {
            // On redirige vers la route de la liste s'il n'y a pas de post
            return $this->redirectToRoute('app_admin_post_list');
        } 
        $em->remove($post);
        $em->flush();
         
        $this->addFlash('success', 'Le post a bien été supprimé');
         
        return $this->redirectToRoute('app_admin_post_list');
    }
}

<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    /**
     * 
     * @param int $id
     * @Route("/article/{id}")
     */
    public function displayAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->find('AppBundle:Post', $id);
        
        if (is_null($post))
        {
            throw $this->createNotFoundException(); //Jette une page 404
        }
        
        $comments = $em->getRepository('AppBundle:Comment')->findAllByPost($post);
        
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment); // création d'un formulaire vide
        
        $form -> handleRequest($request);
        
        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                $comment
                   ->setPost($post)
                   ->setUser($this->getUser())
                ;
                
                $em->persist($comment);
                $em->flush();
                
                $this->addFlash('success', 'Votre commentaire est ajouté');
                // $request->get('_route') permet d'avoir la route courante
                
                return $this->redirectToRoute($request->get('_route'), ['id' => $id]);
                
            } else
            {
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }
        return $this->render(
                    'post/display.html.twig',
                    [
                        'post' => $post,
                        'form' => $form->createView(),
                        'comments' => $comments,
                    ]
                );
    }
}

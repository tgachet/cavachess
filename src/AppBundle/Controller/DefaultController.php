<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        // Requête des trois derniers posts publiés 
        $posts = $em->getRepository('AppBundle:Post')->findLatest(3);
        
        // Requête des utilisateurs connectés sur le site
        $cons = $em->getRepository('AppBundle:User')->getActive();
        
        // Requête du post le plus commenté
        $mostcommented = '';
        $top_article = '';
        $comments = '';
   
        
        $mostcommented = $em->createQuery("SELECT max(c.post) FROM AppBundle\Entity\Comment c")->getResult();
        if (!is_null($mostcommented[0][1]))
        {
            $top_article = $em->getRepository('AppBundle:Post')->find($mostcommented[0][1]);
        
        // Requête des trois derniers commentaires du post le plus commenté
            $comments = $em->getRepository('AppBundle:Comment')->findLatestByPost($top_article, 3);
        }
        
        // Récup compet
        $competitions = $em->getRepository('AppBundle:Competition')->findAll();
        
        return $this->render(
                'default/index.html.twig', 
                [
                    'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
                    'posts' => $posts,
                    'cons' => $cons,
                    'mostcommented' => $mostcommented,
                    'top_article' =>$top_article,
                    'comments' => $comments,
                    'competitions' => $competitions,
   
        ]);
    }
    

}

<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Competition;
use AppBundle\Entity\GamesFinished;
use AppBundle\Entity\Ranking;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Page Jeu front-office
 * @Route("/game")
 */
class GameController extends Controller
{
    /**
     * @Route("/{competition}", requirements={"competition" : "\d+"})
     */
    public function displayGameAction($competition)
    {
        /*
         * Vérifie si l'utilisateur est connecté
         */
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        
        /* Pseudo utilisateur */
        $username = $this->get('security.token_storage')->getToken()->getUser()->getUsername();
        
        /* Id utilisateur */
        $userid = $this->getUser()->getId();
        
        /* Rank de l'utilisateur */
        $em = $this->getDoctrine()->getManager();
        $rank = $em->getRepository('AppBundle:Ranking')->findOneBy(array('user_id' => $userid, 'competition_id' => $competition));
        $compet = $em->getRepository('AppBundle:Competition')->findOneBy(array('id' => $competition));
        $gametime = $compet->getGamemode()->getGametime();
        if(!is_null($rank))
        {
            $points = $rank->getPoints();
        }
        else {
            /* Ajout d'un nouveau rank */  
            $ranking = new Ranking();
            $ranking->setUser_id($this->getUser());
            $ranking->setCompetition_id($compet);
            $ranking->setPoints(1500);
            
            $em->persist($ranking);
            $em->flush();
            
            $points = 1500;
        }
        
        
        /*
         * Variables à passer en js
         */
        
        $vars = array('user' => $username, 'competition' => $competition, 'rank' => $points, 'gametime' => $gametime->format('H:i:s'));
        
        $this->get('app.js_vars')->chartData = $vars;

        return $this->render('game/display.html.twig', 
        [
            'rank' => $points,
            'gametime' => $gametime->format('H:i:s'),
        ]);        
    }

    /**
     * 
     * @Route("/register")
     */
    public function registerGameAction()
    {
        
        $request = $this->container->get('request_stack')->getCurrentRequest();
        
        if($request->isXmlHttpRequest()) 
        {
            /* Get game infos from request */
            $winner = $request->request->get('winner');
            $looser = $request->request->get('looser');
            $gamelength = $request->request->get('gamelength');
            $nbplays = $request->request->get('nbplays');
            $competition = $request->request->get('competition');
            
            /* EM */
            $em = $this->getDoctrine()->getManager();
            /* winner, looser, competition to new Objects */
            $idwinner = $em->getRepository('AppBundle:User')->findOneBy(array('username' => $winner));
            $idlooser = $em->getRepository('AppBundle:User')->findOneBy(array('username' => $looser));
            $compet = $em->getRepository('AppBundle:Competition')->findOneBy(array('id' => $competition));
            
            $hours = floor($gamelength / 3600);
            $mins = floor($gamelength / 60 % 60);
            $secs = floor($gamelength % 60);
            
            /* gamelength to time */
            $now = new \DateTime();
            $now->setTime($hours, $mins, $secs);
            
            /* flush into GamesFinished */
            $gamefinished = new GamesFinished();
            $gamefinished->setNbplays($nbplays);
            $gamefinished->setIdwinner($idwinner);
            $gamefinished->setIdlooser($idlooser);
            $gamefinished->setId_competition($compet);
            $gamefinished->setGamelength($now);

            $em->persist($gamefinished);
            $em->flush();            

            return new Response($gamelength);
        }
        else
        {
            /* Redirect if not AJAX request */
            return $this->redirectToRoute('app_competition_displaycompetitions');
        }
    }
    
}

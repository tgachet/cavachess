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
            $gamelengthwinner = $request->request->get('gamelengthwinner');
            $gamelengthlooser = $request->request->get('gamelengthlooser');
            $nbplays = $request->request->get('nbplays');
            $nbplayswinner = $request->request->get('nbplayswinner');
            $nbplayslooser = $request->request->get('nbplayslooser');
            $competition = $request->request->get('competition');
            
            /* EM */
            $em = $this->getDoctrine()->getManager();
            /* winner, looser, competition to new Objects */
            $idwinner = $em->getRepository('AppBundle:User')->findOneBy(array('username' => $winner));
            $idlooser = $em->getRepository('AppBundle:User')->findOneBy(array('username' => $looser));
            $compet = $em->getRepository('AppBundle:Competition')->findOneBy(array('id' => $competition));
            
//            /* gamelength */
//            $hours = floor($gamelength / 3600);
//            $mins = floor($gamelength / 60 % 60);
//            $secs = floor($gamelength % 60);
//            
//            /* gamelengthwinner */
//            $hours = floor($gamelength / 3600);
//            $mins = floor($gamelength / 60 % 60);
//            $secs = floor($gamelength % 60);
//            
//            /* gamelengthlooser */
//            $hours = floor($gamelength / 3600);
//            $mins = floor($gamelength / 60 % 60);
//            $secs = floor($gamelength % 60);
            
            /* gamelength */
            $ttgl = new \DateTime();
            $ttgl->setTime(floor($gamelength / 3600), floor($gamelength / 60 % 60), floor($gamelength % 60));
            
            /* gamelengthwinner */
            $ttglw = new \DateTime();
            $ttglw->setTime(floor($gamelengthwinner / 3600), floor($gamelengthwinner / 60 % 60), floor($gamelengthwinner % 60));
            
            /* gamelengthlooser */
            $ttgll = new \DateTime();
            $ttgll->setTime(floor($gamelengthlooser / 3600), floor($gamelengthlooser / 60 % 60), floor($gamelengthlooser % 60));
            
            /* flush into GamesFinished */
            $gamefinished = new GamesFinished();
            $gamefinished->setNbplays($nbplays);
            $gamefinished->setIdwinner($idwinner);
            $gamefinished->setIdlooser($idlooser);
            $gamefinished->setId_competition($compet);
            $gamefinished->setGamelength($ttgl);
            $gamefinished->setGamelengthwinner($ttglw);
            $gamefinished->setGamelengthlooser($ttgll);
            $gamefinished->setNbplayswinner($nbplayswinner);
            $gamefinished->setNbplayslooser($nbplayslooser);

            $em->persist($gamefinished);
            $em->flush();            

            return new Response('winner time: '.$gamelengthwinner.' looser time:'.$gamelengthlooser.' winner plays:'.$nbplayswinner.' looser plays:'.$nbplayslooser);
        }
        else
        {
            /* Redirect if not AJAX request */
            return $this->redirectToRoute('app_competition_displaycompetitions');
        }
    }
    
}

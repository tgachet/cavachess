<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/ranking")
 */
class RankingController extends Controller
{
    
    /**
     * LISTE COMPETITIONS
     * @Route("/{id}", defaults={"id": null})
     */
    public function displayRankingAction($id)
    {
        /* ENTITY MANAGER */
        $em = $this->getDoctrine()->getManager();

        $competition = $em->getRepository('AppBundle:Competition')->findOneBy(array('id' => $id));
        if(!$competition){ 
            return $this->redirectToRoute('homepage');
        }       
        $competitionanme = $competition->getName();
        
        /* GET RANKINGS */
        $rankings = $em->getRepository('AppBundle:Ranking')->findBy(array('competition_id' => $id));
        
        /* GET GAMES FINISHED */
        $games = $em->getRepository('AppBundle:GamesFinished')->findBy(array('id_competition' => $id));    
        
        /* GLOBAL STATISTICS */
        $times = [];
        $tabplays = [];
        $nbplays = 0;
        $players = [];
        foreach ($games as $game){
            $times[] = $game->getGamelength()->format('H:i:s');
            $tabplays[] = $game->getNbplays();
            $nbplays += $game->getNbplays() ;
            $players[] = $game->getIdwinner()->getUsername();
            $players[] = $game->getIdlooser()->getUsername();
        }        

        $seconds = 0;
        foreach ($times as $value) {
           list($hour,$minute,$second) = explode(':', $value);
            $seconds += $hour*3600;
            $seconds += $minute*60;
            $seconds += $second;
        }
        $hours = floor($seconds/3600);
        $seconds -= $hours*3600;
        $minutes  = floor($seconds/60);
        $seconds -= $minutes*60;
        $totaltime = sprintf('%02d heure(s) %02d minute(s) %02d seconde(s)', $hours, $minutes, $seconds);
        
        /* DETAILED STATISTICS */
        $nbmaxplays = max($tabplays);
        $maxgamelength = max($times);
        $countPlayers = array_count_values($players);
        $topplayer = array_search(max($countPlayers),$countPlayers); 
        
        return $this->render('/ranking/display.html.twig', 
        [
            'rankings' => $rankings,
            'competition' => $competitionanme,
            'games' => $games,
            'nbplays' => $nbplays,
            'totaltime' => $totaltime,
            'test' => $nbmaxplays,
            'nbmaxplays' => $nbmaxplays,
            'maxgamelength' => $maxgamelength,
            'topplayer' => $topplayer,
        ]);
    }    
}

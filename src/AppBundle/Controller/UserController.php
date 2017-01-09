<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * 
     * @param Request $request
     * @Route("/list")
     */
    public function displayAllAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findAll();
        
        return $this->render(
            'user/list.html.twig',
            [
                'users' => $users,
            ]
        );
    }
    
    /**
     * 
     * @param Request $request
     * @param int $id
     * @Route("/{id}")
     */
    public function displayInfo($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        /* USER INFOS FROM USER */
        $user = $em->find('AppBundle:User', $id);
        
        if(!$user){ 
            return $this->redirectToRoute('app_user_displayall');
        }
        else{
            $gameswon = count($user->getGamewinner());
            $gameslost = count($user->getGamelooser());
            $gamesplayed = $gameswon + $gameslost;

            /* USER POSTS INFO FROM POST */
            $posts = $em->getRepository('AppBundle:Post')->findByAuthor($id);


            /* GET GAMES WON AND GAMES LOST */
            $gamewon = $em->getRepository('AppBundle:GamesFinished')->findBy(array('idwinner' => $id));
            $gamelost = $em->getRepository('AppBundle:GamesFinished')->findBy(array('idlooser' => $id));

            $times = [];
            $opponents = [];
            $competitions = [];
            foreach ($gamewon as $game){
                $times[] = $game->getGamelength()->format('H:i:s');
                $opponents[] = $game->getIdlooser()->getUsername();
                $competitions[] = $game->getId_competition()->getName();
            }
            foreach ($gamelost as $game) {
                $times[] = $game->getGamelength()->format('H:i:s');
                $opponents[] = $game->getIdwinner()->getUsername();
                $competitions[] = $game->getId_competition()->getName();
            }

            /* TIME PLAYED FOR GAMES WON AND LOST */
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
            /* Time played */
            $totaltime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

            /* MOST PLAYED OPPONENT */
            if (!empty($opponents))
            {
                $countPerOpponent = array_count_values($opponents);
                $playermostplayed = array_search(max($countPerOpponent),$countPerOpponent); 
            }
            else
            {
                $playermostplayed ='';
            }
            
            /* MOST PLAYED COMPETITION */
            if (!empty($opponents))
            {
                $countPerCompetition = array_count_values($competitions);
                $competitionmostplayed = array_search(max($countPerCompetition),$countPerCompetition); 
            }
            else
            {
                $competitionmostplayed ='';
            }
            
            /* GET RANKINGS */
            $rankings = $em->getRepository('AppBundle:Ranking')->findBy(array('user_id' => $id));
        }
        /* RENDER */
        return $this->render(
            'user/profile.html.twig',
            [
                'user' => $user,
                'id' => $id,
                'posts' =>$posts,
                'games' => array('played' => $gamesplayed, 'won' => $gameswon, 'lost' => $gameslost, 'timeplayed' => $totaltime, 'playermostplayed' => $playermostplayed, 'competitionmostplayed' => $competitionsmostplayed),
                'rankings' => $rankings,
            ]
        );
    }
    
    /**
     * @param int $id
     * @Route("/delete/{id}")
     */
    public function deleteUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->find('AppBundle:User', $id);
         
        if(is_null($user))
        {
            // On redirige vers la route de la liste s'il n'y a pas de post
            return $this->redirectToRoute('app_admin_user_listusers');
        } 
        $em->remove($user);
        $em->flush();
         
        $this->addFlash('success', 'Le joueur a bien été supprimé');
         
        return $this->redirectToRoute('app_admin_user_listusers');
    }
    
    
//    /**
//     * @param Request $request
//     * 
//     */
//    public function whoIsOnlineAction()
//    {
//        $cons = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->getActive();
// 
//        return $this->render(
//            'default/index.html.twig',
//            [
//                'cons' => $cons,
//            ]
//        );
////        return array('cons' => $cons);
//    }
    
    /**
     * 
     * @param Request $request
     * @param int $id
     * @Route("/add/{id}")
     */
//    public function addFriendAction($id)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $friend = $em->getRepository('AppBundle:User')->find($id);
//        if (!$friend) {
//            throw $this->createNotFoundException(
//                'No user found for id '.$id
//            );
//        }
//        
//        $user = $this->getUser();
//        $friend->setFriendsWithMe($user);
//        $em->persist($friend);
//        $em->flush();
//            
//        return $this->render(
//            'user/profile.html.twig',
//            [
//                'user' => $user,
//            ]
//        );
//        $user = $this->getDoctrine()
//                     ->getRepository('AppBundle:User')
//                     ->findOneById($id);
//        
//        $friends = $user->getMyFriends();
//        $names = array();
//        foreach($friends as $friend)
//        {
//            $names[] = $friend->getName();
//        }
    
}

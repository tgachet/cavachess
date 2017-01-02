<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ranking
 *
 * @ORM\Table(name="ranking")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RankingRepository")
 */
class Ranking
{
    /***** PROPERTIES *****/
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
        
    /**
     *
     * @var int
     * @ORM\Column(type="integer")
     */
    private $points;
    
    /**
     *
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="player")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user_id;
    
    /**
     *
     * @var Competition
     * @ORM\ManyToOne(targetEntity="Competition", inversedBy="ranking")
     * @ORM\JoinColumn(name="competition_id", referencedColumnName="id", nullable=false)
     */
    private $competition_id;

    /***** GETTERS *****/
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function getPoints() {
        return $this->points;
    }

    public function getUser_id() {
        return $this->user_id;
    }

    public function getCompetition_id() {
        return $this->competition_id;
    }

    /***** SETTERS *****/
    
    public function setUser_id(User $user_id) {
        $this->user_id = $user_id;
        return $this;
    }

    public function setCompetition_id(Competition $competition_id) {
        $this->competition_id = $competition_id;
        return $this;
    }
    
    public function setPoints($points) {
        $this->points = $points;
        return $this;
    }
}


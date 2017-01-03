<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Competition
 *
 * @ORM\Table(name="competition")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CompetitionRepository")
 */
class Competition
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
     * @var string
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $name;
    
    /**
     *
     * @var TypeOfGame
     * @ORM\ManyToOne(targetEntity="TypeOfGame", inversedBy="competitions")
     * @ORM\JoinColumn(name="type_of_game_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank()
     */
    private $typeofgame;
    
    /**
     *
     * @var GameMode
     * @ORM\ManyToOne(targetEntity="GameMode", inversedBy="competitions")
     * @ORM\JoinColumn(name="game_mode_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank()
     */
    private $gamemode;
    
    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="GamesFinished", mappedBy="id_competition")
     */
    private $gamefinished;
    
    /**
     *
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Ranking", mappedBy="competition_id")
     */
    private $ranking;
    
    /***** CONSTRUCT *****/
    public function __construct()
    {
        $this->gamefinished = new ArrayCollection();
        $this->ranking = new ArrayCollection();
    }      
    
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

    public function getName() {
        return $this->name;
    }

    public function getTypeofgame() {
        return $this->typeofgame;
    }

    public function getGamemode() {
        return $this->gamemode;
    }
    
    public function getGamefinished() {
        return $this->gamefinished;
    }

    public function getRanking() {
        return $this->ranking;
    }    
    
    /***** SETTERS *****/


    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setTypeofgame(TypeOfGame $typeofgame) {
        $this->typeofgame = $typeofgame;
        return $this;
    }

    public function setGamemode(GameMode $gamemode) {
        $this->gamemode = $gamemode;
        return $this;
    }

}


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
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     * 
     */
    private $id;

    /**
     *
     * @var TypeOfGame
     * @ORM\ManyToOne(targetEntity="TypeOfGame", inversedBy="competitions")
     * @ORM\JoinColumn(name="type_of_game_id", referencedColumnName="id", nullable=false)
     */
    private $type_of_game_id;
    
    /**
     *
     * @var GameMode
     * @ORM\ManyToOne(targetEntity="GameMode", inversedBy="gamemode")
     * @ORM\JoinColumn(name="game_mode_id", referencedColumnName="id", nullable=false)
     */
    private $game_mode_id;
    
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
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}


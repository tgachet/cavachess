<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\OneToMany(targetEntity="Ranking", mappedBy="competition_id")
     * @ORM\OneToMany(targetEntity="GamesFinished", mappedBy="id_competition")
     */
    private $id;

    /**
     *
     * @var TypeOfGame
     * @ORM\ManyToOne(targetEntity="TypeOfGame", inversedBy="id")
     * @ORM\JoinColumn(name="type_of_game_id", referencedColumnName="id", nullable=false)
     */
    private $type_of_game_id;
    
    /**
     *
     * @var GameMode
     * @ORM\ManyToOne(targetEntity="GameMode", inversedBy="id")
     * @ORM\JoinColumn(name="game_mode_id", referencedColumnName="id", nullable=false)
     */
    private $game_mode_id;
    
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


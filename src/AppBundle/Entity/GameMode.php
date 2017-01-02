<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * GameMode
 *
 * @ORM\Table(name="game_mode")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameModeRepository")
 */
class GameMode
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
     * @var arrayCollection 
     * @ORM\OneToMany(targetEntity="Competition", mappedBy="game_mode_id")
     */
    private $gamemode;
    
    /***** CONSTRUCT *****/
    public function __construct()
    {
        $this->gamemode = new ArrayCollection();
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

    public function getGamemode() {
        return $this->gamemode;
    }

    /***** SETTERS *****/
    public function setName($name) {
        $this->name = $name;
        return $this;
    }


}


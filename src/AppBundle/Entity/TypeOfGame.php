<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeOfGame
 *
 * @ORM\Table(name="type_of_game")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TypeOfGameRepository")
 */
class TypeOfGame
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $name;

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
    
     public function setName($name) {
        $this->name = $name;
        return $this;
    }
}


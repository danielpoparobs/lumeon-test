<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="hospital")
 */

class Hospital
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
	private $name;

    /**
     * @ORM\OneToMany(targetEntity="Patient", mappedBy="hospital")
     */
    private $patients;

    /**
     * @ORM\OneToMany(targetEntity="Doctor", mappedBy="hospital")
     */
    private $doctors;

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $id
	 * @return Hospital
	 */
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param mixed $name
	 * @return Hospital
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

}
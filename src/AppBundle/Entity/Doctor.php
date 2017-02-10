<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Patient;

/**
 * @ORM\Entity
 * @ORM\Table(name="doctor")
 */

class Doctor
{
	const GENDER_MALE = 1;
	const GENDER_FEMALE = 2;
	const GENDER_OTHER = 3;

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
     * @ORM\Column(type="string", length=100)
     */
	private $specialty;

    /**
     * @ORM\ManyToOne(targetEntity="Hospital", inversedBy="doctors")
     * @ORM\JoinColumn(name="hospital_id", referencedColumnName="id")
     */
	private $hospital;

    /**
     * @ORM\OneToMany(targetEntity="Patient", mappedBy="doctor")
     */
    private $patients;

    public function __construct()
    {
        $this->patients = new ArrayCollection();
    }

    /**
     * @param Patient $patient
     * @return Doctor
     */
    public function addPatient(Patient $patient) {
        $patient->setDoctor($this); // Call Patient's setter here
        $this->patients[] = $patient; // Add patient to the collection
        return $this;
    }

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return Patient
	 */
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return Patient
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

    /**
     * @return string
     */
    public function getSpeciality()
    {
        return $this->specialty;
    }

    /**
     * @param string $specialty
     * @return Patient
     */
    public function setSpeciality($specialty)
    {
        $this->specialty = $specialty;
        return $this;
    }

	/**
	 * @return Hospital
	 */
	public function getHospital()
	{
		return $this->hospital;
	}

	/**
	 * @param Hospital $hospital
	 * @return Doctor
	 */
	public function setHospital($hospital)
	{
		$this->hospital = $hospital;
		return $this;
	}

    /**
     * @return Patient
     */
    public function getPatients()
    {
        return $this->patients;
    }
}
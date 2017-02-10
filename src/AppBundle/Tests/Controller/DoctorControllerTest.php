<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Entity\Doctor;
use AppBundle\Entity\Hospital;
use AppBundle\Entity\Patient;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ObjectManager;

class DoctorControllerTest extends WebTestCase
{
    public function testAddPatient() {

        // First, mock the object to be used in the test
        $hopsital = $this->createMock(Hospital::class);
        $hopsital->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(1));
        $hopsital->expects($this->once())->method('getName')
            ->will($this->returnValue('Emergency Hospital'));

        $doctor = $this->createMock(Doctor::class);
        $doctor->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('Dr. Eliah Anderson'));
        $doctor->expects($this->once())->method('getId')
            ->will($this->returnValue(1));
        $doctor->expects($this->once())->method('getHospital')
            ->will($this->returnValue($hopsital));
        $doctor->expects($this->once()) ->method('getSpeciality')
            ->will($this->returnValue('General Surgery'));

        // Now, mock the repository so it returns the mock of the hospital
        $hospitalRepository = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $hospitalRepository->expects($this->once())
            ->method('find')
            ->will($this->returnValue($hopsital));

        // Now, mock the repository so it returns the mock of the doctor
        $doctorRepository = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctorRepository->expects($this->once())
            ->method('find')
            ->will($this->returnValue($doctor));

        $client = static::createClient();
        $client->request(
            'POST',
            '/doctor/add-patient',
            array(
                "hospital_id"      => $hopsital->getId(),
                "doctor_id"        => $doctor->getId(),
                "name"             => "John Doe",
                "gender"           => Patient::GENDER_MALE,
                "dob"              => '1980-02-03 09:30:00'
            ),
            array(),
            array());

        $response = $client->getResponse();
        // Test if response is OK
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        // Test if Content-Type is valid application/json
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        // Test that response is not empty
        $this->assertNotEmpty($client->getResponse()->getContent());
    }
}
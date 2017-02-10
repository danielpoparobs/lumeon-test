<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Patient;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;


class DoctorController extends Controller
{
     /**
     * @Route("doctor/add-patient")
     * @Method("POST")
     */
    public function addPatient(Request $request)
    {
        //validation snippet
        $constraint = new Collection(array(
            'name' => new NotBlank(),
            'gender' => new NotBlank(),
            'doctor_id' => new NotBlank(),
            'hospital_id' => new NotBlank(),
            'dob' => new DateTime(),
        ));

        $violationList = $this->get('validator')->validate($request->request->all(), $constraint);

        $errors = array();
        foreach ($violationList as $violation){
            $field = preg_replace('/\[|\]/', "", $violation->getPropertyPath());
            $error = $violation->getMessage();
            $errors[$field] = $error;
        }

        if (!empty($errors)) {
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'msg' => 'Errors',
                'errors' => $errors
            ));
        }

        $doctor = $this->getDoctrine()->getRepository('AppBundle:Doctor')->find($request->get('doctor_id'));
        if (empty($doctor)) {
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'msg' => 'No doctor information found'
            ));
        }

        $hospital = $this->getDoctrine()->getRepository('AppBundle:Hospital')->find($request->get('hospital_id'));
        if (empty($hospital)) {
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'msg' => 'No hospital information found'
            ));
        }

        //save patient against doctor and hospital
        $patient = new Patient();
        $patient->setName($request->get('name'));
        $patient->setGender($request->get('gender'));
        $patient->setDob(new \DateTime($request->get('dob')));
        $patient->setDoctor($doctor);
        $patient->setHospital($hospital);

        $em = $this->getDoctrine()->getManager();
        $em->persist($patient);
        $em->flush();

        $patients = $doctor->getPatients();
        $patients_array = array();
        $gender_options = Patient::genderOptions();
        if (!empty($patients)) {
            foreach ($patients as $patient) {
                $patients_array[] = array(
                    'name' => $patient->getName(),
                    'gender' => isset($gender_options[$patient->getGender()]) ? $gender_options[$patient->getGender()] : $patient->getGender(),
                    'dob' => $patient->getDob()->format('d.m.Y H:i:s'),
                    'hospital' =>$patient->getHospital()->getName(),
                );
            }
        }

        // Return a list of patients along with the original doctor and a message showing success
        return new \Symfony\Component\HttpFoundation\JsonResponse(array(
            'doctor' => array(
                'name' => $doctor->getName(),
                'speciality' => $doctor->getSpeciality(),
                'patients' => $patients_array
            ),

        ));
    }
}
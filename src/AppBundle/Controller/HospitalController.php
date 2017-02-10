<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HospitalController extends Controller
{
    /**
     * @Route("hospital_patients/{hospitalId}", name="hospitalId")
     */
    public function indexAction($hospitalId)
    {
        if (empty($hospitalId)) {
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'msg' => 'No hospital information received'
            ));
        }

        $hospitalRepository = new \AppBundle\Repository\HospitalRepository();
        $patientRepository = new \AppBundle\Repository\PatientRepository();

        $hospital = $hospitalRepository->selectById($hospitalId);
        $patients = $patientRepository->selectByHospital($hospital);

        // Return a list of patients along with the original hospital and a message showing success
        return new \Symfony\Component\HttpFoundation\JsonResponse(array(
            'patients' => $patients,
            'hospital' => $hospital,
            'msg' => 'Here are the patients for '.$hospital->getName()
        ));
    }
}
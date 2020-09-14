<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Status;
use AppBundle\Entity\Company;
use AppBundle\Entity\Payment;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Payment_Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class PaymentController extends FOSRestController
{
    /**
     * @Route("/payments", name="create_payments")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $company = $this->getDoctrine()->getRepository(Company::class)->findOneBy(['name' => $request->get("company")]);
        if ($company == NULL) {
            $response = [
                "Code" => 400,
                "Message" => "Error validating fields: Company does not exist"
            ];
            return new JsonResponse($response, 400);
        }

        $payment_method = $this->getDoctrine()->getRepository(Payment_Method::class)->findOneBy(['name' => $request->get("payment_method")]);
        if ($payment_method == NULL) {
            $response = [
                "Code" => 400,
                "Message" => "Error validating fields: payment_method does not exist"
            ];
            return new JsonResponse($response, 400);
        }
        
        $status = $this->getDoctrine()->getRepository(Status::class)->findOneBy(['name' => $request->get("status")]);
        if ($status == NULL) {
            $response = [
                "Code" => 400,
                "Message" => "Error validating fields: status does not exist"
            ];
            return new JsonResponse($response, 400);
        }

        try {
            $payment_date = new \DateTime($request->get("payment_date"));
        } catch (\Exception $e) {
            $response = [
                "Code" => 400,
                "Message" => "Error validating fields: payment_date is invalid"
            ];
            return new JsonResponse($response, 400);
        }
        
        $payment = new Payment(); 
        $payment->setPaymentDate($payment_date);
        $payment->setCompanyId($company->getId());
        $payment->setAmount($request->get("amount"));
        $payment->setPaymentMethodId($payment_method->getId());
        $payment->setExternalReference($request->get("external_reference"));
        $payment->setTerminal($request->get("terminal"));
        $payment->setStatusId($status->getId());
        $payment->setReference($request->get("reference"));

        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($payment);
            $em->flush();

            $date = $payment->getPaymentDate();

            $response = [
                "id" => $payment->getId(),
                "payment_date" => $date->format('Y-m-d\TH:i:sP'),
                "company" => $company->getName(),
                "amount" => $payment->getAmount(),
                "payment_method" => $payment_method->getName(),
                "external_reference" => $payment->getExternalReference(),
                "terminal" => $payment->getTerminal(),
                "status" => $status->getName(),
                "reference" => $payment->getReference()
            ];

            return new JsonResponse($response, 200);
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $ex) {
            $response = [
                "Code" => 400,
                "Message" => "Error validating fields: external_reference already exists"
            ];
            return new JsonResponse($response, 400);
        } catch (\Doctrine\DBAL\Exception\NotNullConstraintViolationException $ex) {
            $response = [
                "Code" => 400,
                "Message" => "Error validating fields: all fields are obligatories"
            ];
            return new JsonResponse($response, 400);
        }         
        die();
    }

    /**
     * @Route("/payments/{id}", name="update_payments")
     * @Method("PATCH")
     */
    public function updateAction($id,Request $request){
        $status = $this->getDoctrine()->getRepository(Status::class)->findOneBy(['name' => $request->get("status")]);
        if ($status == NULL) {
            $response = [
                "Code" => 400,
                "Message" => "Error validating fields: status does not exist"
            ];
            return new JsonResponse($response, 400);
        }

        $em = $this->getDoctrine()->getManager();
        $payment = $this->getDoctrine()->getRepository(Payment::class)->find($id);
        if ($payment == NULL) {
            $response = [
                "Code" => 400,
                "Message" => "Error validating fields: payment does not exist"
            ];
            return new JsonResponse($response, 400);
        }
        $payment->setStatusId($status->getId());
        $em->flush();

        $payment_method = $this->getDoctrine()->getRepository(Payment_Method::class)->findOneBy(['id' => $payment->getPaymentMethodId()]);
        $company = $this->getDoctrine()->getRepository(Company::class)->findOneBy(['id' => $payment->getCompanyId()]);
        $date = $payment->getPaymentDate();

            $response = [
                "id" => $payment->getId(),
                "payment_date" => $date->format('Y-m-d\TH:i:sP'),
                "company" => $company->getName(),
                "amount" => $payment->getAmount(),
                "payment_method" => $payment_method->getName(),
                "external_reference" => $payment->getExternalReference(),
                "terminal" => $payment->getTerminal(),
                "status" => $status->getName(),
                "reference" => $payment->getReference()
            ];

            return new JsonResponse($response, 200);        
    }

    /**
     * @Route("/payments", name="get_payments")
     * @Method("GET")
     */
    public function getAction(Request $request)
    {
        if (null !== $request->get("payment_method")) {    
            $payment_method = $this->getDoctrine()->getRepository(Payment_Method::class)->findOneBy(['name' => $request->get("payment_method")]);
            if ($payment_method == NULL) {
                $response = [
                    "Code" => 400,
                    "Message" => "Error validating fields: payment_method does not exist"
                ];
                return new JsonResponse($response, 400);
            }
            $payment_method_id = $payment_method->getId();
        } else {
            $payment_method_id = null;
        }

        if (null !== $request->get("company")) {
            $company = $this->getDoctrine()->getRepository(Company::class)->findOneBy(['name' => $request->get("company")]);
            if ($company == NULL) {
                $response = [
                    "Code" => 400,
                    "Message" => "Error validating fields: Company does not exist"
                ];
                return new JsonResponse($response, 400);
            }
            $company_id = $company->getId();
        } else {
            $company_id = null;
        }
        
        if (null !== $request->get("payment_date_from")) {
            try {
                $payment_date_from = new \DateTime($request->get("payment_date_from"));
                $payment_date_from = $payment_date_from->format('Y-m-d\TH:i:sP');
            } catch (\Exception $e) {
                $response = [
                    "Code" => 400,
                    "Message" => "Error validating fields: payment_date is invalid"
                ];
                return new JsonResponse($response, 400);
            }
        } else {
            $payment_date_from = null;
        }
        

        if (null !== $request->get("payment_date_until")) {
            try {
                $payment_date_until = new \DateTime($request->get("payment_date_until"));
                $payment_date_until = $payment_date_until->format('Y-m-d\TH:i:sP');
            } catch (\Exception $e) {
                $response = [
                    "Code" => 400,
                    "Message" => "Error validating fields: payment_date is invalid"
                ];
                return new JsonResponse($response, 400);
            }
        } else {
            $payment_date_until = null;
        }

        $payments = $this->getDoctrine()->getRepository(Payment::class);
        $payments = $payments->findWithAllFilters($payment_method_id, $company_id, $payment_date_from, $payment_date_until);

        $data = array();

        foreach ($payments as $payment) {
            $date = $payment->getPaymentDate();
            $status = $this->getDoctrine()->getRepository(Status::class)->findOneBy(['id' => $payment->getStatusId()]);
            $company = $this->getDoctrine()->getRepository(Company::class)->findOneBy(['id' => $payment->getCompanyId()]);
            $payment_method = $this->getDoctrine()->getRepository(Payment_Method::class)->findOneBy(['id' => $payment->getPaymentMethodId()]);
            $pago = array(
                "id" => $payment->getId(),
                "payment_date" => $date->format('Y-m-d\TH:i:sP'),
                "company" => $company->getName(),
                "amount" => $payment->getAmount(),
                "payment_method" => $payment_method->getName(),
                "external_reference" => $payment->getExternalReference(),
                "terminal" => $payment->getTerminal(),
                "status" => $status->getName(),
                "reference" => $payment->getReference()
            );
            array_push($data, $pago);
        }

        $response = [
            "total_items" => 1,
            "data" => $data
        ];

        return new JsonResponse($response, 200);
    }

    
}

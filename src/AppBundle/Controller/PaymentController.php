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
     * @Route("/payments", name="get_payments")
     * @Method("GET")
     */
    public function getAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Payment::class);
        $payments = $repository->findAll();

        if (!$payments) {
            return new Response('No hay pagos', 400);
        } else {

            $pagos = array();
            foreach ($payments as $payment) {
                $pagos[] = $payment->getExternalReference();
            }

            return new JsonResponse($pagos, 200);
        }
        
        die();
    }

    
}

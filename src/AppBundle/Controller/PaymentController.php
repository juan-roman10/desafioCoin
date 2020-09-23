<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Status;
use AppBundle\Entity\Company;
use AppBundle\Entity\Payment;
use FOS\RestBundle\View\View;
use AppBundle\Form\PaymentType;
use AppBundle\Entity\Payment_Method;
use JMS\Serializer\SerializerBuilder;
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
        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(PaymentType::class);
        $form->submit($data);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $payment = $form->getData();

            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($payment);
                $em->flush();
    
                $date = $payment->getPaymentDate();
    
                $response = [
                    "id" => $payment->getId(),
                    "payment_date" => $date->format('Y-m-d\TH:i:sP'),
                    "company" => $payment->getCompany()->getName(),
                    "amount" => $payment->getAmount(),
                    "payment_method" => $payment->getPaymentMethod()->getName(),
                    "external_reference" => $payment->getExternalReference(),
                    "terminal" => $payment->getTerminal(),
                    "status" => $payment->getStatus()->getName(),
                    "reference" => $payment->getReference()
                ];

                $serializer = SerializerBuilder::create()->build();
                $jsonContent = $serializer->serialize($response, 'json');
    
                return new Response($jsonContent, 200);
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

        } else {
            $string = (string) $form->getErrors(true, false);
            dump($string);
        }
        die();
    }

    /**
     * @Route("/payments/{id}", name="update_payments")
     * @Method("PATCH")
     */
    public function updateAction($id, Request $request){
        
        $em = $this->getDoctrine()->getManager();
        $payment = $em->getRepository(Payment::class)->find($id);
        $status = $this->getDoctrine()->getRepository(Status::class)->findOneBy(['name' => $request->get("status")]);
        $form = $this->createForm(PaymentType::class, $payment);
        $form->submit($payment);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $payment->setStatus($status);
            $em->flush();
            $date = $payment->getPaymentDate();
    
            $response = [
                "id" => $payment->getId(),
                "payment_date" => $date->format('Y-m-d\TH:i:sP'),
                "company" => $payment->getCompany()->getName(),
                "amount" => $payment->getAmount(),
                "payment_method" => $payment->getPaymentMethod()->getName(),
                "external_reference" => $payment->getExternalReference(),
                "terminal" => $payment->getTerminal(),
                "status" => $payment->getStatus()->getName(),
                "reference" => $payment->getReference()
            ];

            $serializer = SerializerBuilder::create()->build();
            $jsonContent = $serializer->serialize($response, 'json');

            return new Response($jsonContent, 200);
        } else {
            $string = (string) $form->getErrors(true, false);
            dump($string);
        }

        die();  
    }

    /**
     * @Route("/payments", name="get_payments")
     * @Method("GET")
     */
    public function getAction(Request $request)
    {
        if (null !== $request->get("payment_method")) {    
            $paymentMethod = $this->getDoctrine()->getRepository(Payment_Method::class)->findOneBy(['name' => $request->get("payment_method")]);
            $paymentMethod = $paymentMethod->getId();
        } else {
            $paymentMethod = null;
        }

        if (null !== $request->get("company")) {
            $company = $this->getDoctrine()->getRepository(Company::class)->findOneBy(['name' => $request->get("company")]);
            $company = $company->getId();
        } else {
            $company = null;
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
        $payments = $payments->findWithAllFilters($paymentMethod, $company, $payment_date_from, $payment_date_until);

        $data = array();

        foreach ($payments as $payment) {
            $date = $payment->getPaymentDate();

            $pago = array(
                "id" => $payment->getId(),
                "payment_date" => $date->format('Y-m-d\TH:i:sP'),
                "company" => $payment->getCompany()->getName(),
                "amount" => $payment->getAmount(),
                "payment_method" => $payment->getPaymentMethod()->getName(),
                "external_reference" => $payment->getExternalReference(),
                "terminal" => $payment->getTerminal(),
                "status" => $payment->getStatus()->getName(),
                "reference" => $payment->getReference()
            );
            array_push($data, $pago);
        }

        $response = [
            "total_items" => 1,
            "data" => $data
        ];

        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($response, 'json');

        return new Response($jsonContent, 200);
    }

    /**
     * @Route("/payments/all", name="getAll_payments")
     * @Method("GET")
     */
    public function getAllAction(Request $request)
    {

        $pagos = $this->getDoctrine()
        ->getRepository(Payment::class)
        ->findAll();
        $data = array();

        foreach ($pagos as $pago) {

            $pay = array(
                "id" => $pago->getId(),
                "company" => $pago->getCompany()->getName(),
                "payment_method" => $pago->getPaymentMethod()->getName(),
                "status" => $pago->getStatus()->getName()
            );
            array_push($data, $pay);
        }

        $response = [
            "total_items" => 1,
            "data" => $data
        ];

        return new JsonResponse($response, 200);
    }
}

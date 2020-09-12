<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Payment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class PaymentController extends Controller
{
    /**
     * @Route("/payments", name="create_payments")
     * @Method("GET")
     */
    public function createAction(Request $request)
    {
        $payment = new Payment(); 
        $payment->setPaymentDate('2011-06-01T15:03:01-03:00');
        $payment->setCompanyId(1);
        $payment->setAmount(20000);
        $payment->setPaymentMethodId(2);
        $payment->setExternalReference('a28fkeuyhd94kj');
        $payment->setTerminal(2849);
        $payment->setStatusId(3);
        $payment->setReference('Black Jeans small size”');

        $em = $this->getDoctrine()->getManager();
        $em->persist($payment);
        $flush=$em->flush();

        if($flush == null){
            return new Response('El pago se creo correctamente', 200);
        } else {
            return new Response('Error validating fields: payment_method does not exist”', 400);
        }
        
        die();
    }
}

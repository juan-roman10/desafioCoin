<?php

namespace AppBundle\Form\DataTransformer;

use AppBundle\Entity\Payment_Method;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class PaymentMethodToStringTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    /**
     * Transforms an object (paymentMethod) to a string (name).
     *
     * @param  Payment_Method|null $paymentMethod
     * @return string
     */
    public function transform($paymentMethod)
    {
        if (null === $paymentMethod) {
            return '';
        }

        return $paymentMethod->getName();
    }
    /**
     * Transforms a string (name) to an object (paymentMethod).
     *
     * @param  string $name
     * @return Payment_Method|null
     * @throws TransformationFailedException if object (paymentMethod) is not found.
     */
    public function reverseTransform($name)
    {

        if (!$name) {
            return;
        }

        $paymentMethod = $this->manager
            ->getRepository('AppBundle:Payment_Method')
            // query for the issue with this id
            ->findOneBy(array(
                'name' => $name
            ))
        ;

        if (null === $name) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'A paymentMethod with name "%s" does not exist!',
                $name
            ));
        }

        return $paymentMethod;
    }
}
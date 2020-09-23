<?php

namespace AppBundle\Form\DataTransformer;

use AppBundle\Entity\Status;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class StatusToStringTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    /**
     * Transforms an object (status) to a string (name).
     *
     * @param  Status|null $status
     * @return string
     */
    public function transform($status)
    {
        if (null === $status) {
            return '';
        }

        return $status->getName();
    }
    /**
     * Transforms a string (name) to an object (status).
     *
     * @param  string $name
     * @return Status|null
     * @throws TransformationFailedException if object (status) is not found.
     */
    public function reverseTransform($name)
    {

        if (!$name) {
            return;
        }

        $status = $this->manager
            ->getRepository('AppBundle:Status')
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
                'A status with name "%s" does not exist!',
                $name
            ));
        }

        return $status;
    }
}
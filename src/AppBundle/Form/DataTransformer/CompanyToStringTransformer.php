<?php

namespace AppBundle\Form\DataTransformer;

use AppBundle\Entity\Company;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CompanyToStringTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    /**
     * Transforms an object (company) to a string (name).
     *
     * @param  Company|null $company
     * @return string
     */
    public function transform($company)
    {
        if (null === $company) {
            return '';
        }

        return $company->getName();
    }
    /**
     * Transforms a string (name) to an object (company).
     *
     * @param  string $name
     * @return Company|null
     * @throws TransformationFailedException if object (company) is not found.
     */
    public function reverseTransform($name)
    {

        if (!$name) {
            return;
        }

        $company = $this->manager
            ->getRepository('AppBundle:Company')
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
                'A company with name "%s" does not exist!',
                $name
            ));
        }

        return $company;
    }
}
<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use AppBundle\Form\DataTransformer\StatusToStringTransformer;
use AppBundle\Form\DataTransformer\CompanyToStringTransformer;
use AppBundle\Form\DataTransformer\PaymentMethodToStringTransformer;

class PaymentType extends AbstractType{

    private $manager;
    
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('payment_date', DateType::class, array(
                'widget' => 'single_text',
                'format' => "yyyy-MM-dd'T'HH:mm:ssZZZ",
                'property_path' => 'paymentDate'
            ))
            ->add('company', TextType::class, array(
                'property_path' => 'company'
            ))
            ->add('amount', TextType::class, array(
                'property_path' => 'amount'
            ))
            ->add('payment_method', TextType::class, array(
                'property_path' => 'payment_Method'
            ))
            ->add('external_reference', TextType::class, array(
                'property_path' => 'externalReference'
            ))
            ->add('terminal', TextType::class, array(
                'property_path' => 'terminal'
            ))
            ->add('status', TextType::class, array(
                'property_path' => 'status'
            ))
            ->add('reference', TextType::class, array(
                'property_path' => 'reference'
            ));

        $builder->get('payment_method')->addModelTransformer(
            new PaymentMethodToStringTransformer($this->manager));
        $builder->get('status')->addModelTransformer(
            new StatusToStringTransformer($this->manager));
        $builder->get('company')->addModelTransformer(
            new CompanyToStringTransformer($this->manager));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Payment',
            'csrf_protection' => false,
        ));
    }
}

?>
<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as Types;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalesOrderType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', Types\DateTimeType::class, ['data' => new \DateTime('now', new \DateTimeZone('Asia/Colombo'))])
            ->add('customerId', Types\TextType::class, ['mapped' => false, 'required' => true, 'attr' => ['readOnly' => true]])
            ->add('itemsIds', Types\TextType::class, ['mapped' => false, 'required' => true, 'attr' => ['readOnly' => true]])
            ->add('salesClerkId', Types\TextType::class, ['mapped' => false, 'required' => true, 'attr' => ['readOnly' => true]])
            ->add('submit', Types\SubmitType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\SalesOrder'
        ));
    }
}

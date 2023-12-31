<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as Types;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepairingItemType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', Types\TextType::class)
            ->add('description', Types\TextareaType::class)
            ->add('due', Types\DateTimeType::class)
            //->add('isRepaired', Types\CheckboxType::class)
            ->add('price', Types\MoneyType::class, ['currency' => 'LKR'])
            ->add('submit', Types\SubmitType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\RepairingItem'
        ));
    }
}

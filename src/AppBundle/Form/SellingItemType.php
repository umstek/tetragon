<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as Types;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SellingItemType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', Types\TextType::class)
            ->add('warrantyPeriod', Types\TextType::class)
            ->add('name', Types\TextType::class)
            ->add('brand', Types\TextType::class)
            ->add('model', Types\TextType::class)
            ->add('serial', Types\TextType::class)
            ->add('description', Types\TextareaType::class)
//            ->add('isSold', Types\HiddenType::class, ['data' => false])
//            ->add('isWarrantyClaimed', Types\HiddenType::class, ['data' => false])
//            ->add('warrantyExpiration', Types\DateTimeType::class, ['data' => new \DateTime('now', new \DateTimeZone('Asia/Colombo'))])
            ->add('price', Types\MoneyType::class)
            ->add('submit', Types\SubmitType::class);

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\SellingItem'
        ));
    }
}

<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as Types;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Types\HiddenType::class, ['disabled' => true])
            ->add('name')
            ->add('role', Types\ChoiceType::class,
                [
                    'mapped' => false,
                    'choices' => [
                        'Administrators' => [
                            'Manager' => 'manager'
                        ],
                        'Users' => [
                            'Sales Clerk' => 'sales_clerk',
                            'Technician' => 'technician'
                        ]
                    ]
                ]
            )
            ->add('address')
            ->add('phone')
            ->add('email', Types\EmailType::class)
            ->add('nic')
            ->add('sysUser', UserType::class, ['label' => 'User account options'])
            ->add('submit', Types\SubmitType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Employee'
        ));
    }
}

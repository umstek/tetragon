<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as Types;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('enabled', Types\HiddenType::class, ['data' => true])
            ->add('plain_password', Types\PasswordType::class, ['label' => 'Password', 'required' => false])
            ->add('confirm_password', Types\PasswordType::class, ['mapped' => false, 'required' => false]);
        // We're manually checking passwords. FOSUserBundle only enables html5 validation.
        // Also, when modifying the user account, we need this to be empty.
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }
}

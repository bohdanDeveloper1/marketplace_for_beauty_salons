<?php

namespace App\Form;

use App\Entity\Salon;
use App\Entity\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('photo')
            ->add('salonAdress', ChoiceType::class, [
                'mapped' => false,
                'choices' => array_combine($options['salonAdresses'], $options['salonAdresses']),
                'placeholder' => 'Select a salon by address',
                'required' => true,
    ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
            'salonAdresses' => [],
        ]);
    }
}

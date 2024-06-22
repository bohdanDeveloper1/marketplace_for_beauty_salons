<?php

namespace App\Form;

use App\Entity\Salon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;

class SalonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('adress', TextType::class, [
                'required' => true,
                'label' => 'Street and house number'
            ])
            ->add('description')
            ->add('salonOwnerEmail', TextType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Email(['message' => 'Invalid email address']),
                ]
            ])
            ->add('belongToCity', ChoiceType::class, [
                'mapped' => false,
                'required' => true,
                'placeholder' => 'Select a city for a new salon',
                //ChoiceType очікує, що значення буде асоціативним масивом
                // (де ключі будуть значеннями, які будуть відображатися в полі вибору).
                // ['city1' => 'city1', 'city2' => 'city2']
                // array_combine створює асоціативний масив
                'choices' => array_combine($options['avaliableCities'], $options['avaliableCities']),
            ])
            ->add('photo', FileType::class, [
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '100M',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'You should upload only photo there'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Salon::class,
            'avaliableCities' => [],
        ]);
    }
}

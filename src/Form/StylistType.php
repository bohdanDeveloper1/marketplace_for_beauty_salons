<?php

namespace App\Form;

use App\Entity\Stylist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class StylistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('instagram_account')
            ->add('description')
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
            ->add('category', ChoiceType::class, [
                'mapped' => false,
                'required' => true,
                'placeholder' => 'Select a category for a new stylist',
                //ChoiceType очікує, що значення буде асоціативним масивом
                // (де ключі будуть значеннями, які будуть відображатися в полі вибору).
                // ['city1' => 'city1', 'city2' => 'city2']
                // array_combine створює асоціативний масив
                'choices' => array_combine($options['categoriesInCurrentSalon'], $options['categoriesInCurrentSalon']),
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stylist::class,
            'categoriesInCurrentSalon' => [],
        ]);
    }
}

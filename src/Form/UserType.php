<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Webmozart\Assert\Tests\StaticAnalysis\false;
use function Webmozart\Assert\Tests\StaticAnalysis\true;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('password', PasswordType::class, [
                'empty_data' => '', // Пусте значення поля
                'required' => false, // Поле не обов'язкове для заповнення
                'attr' => [
                    'placeholder' => $options['placeholder'], // Додайте підказку
                ],
            ])
            ->add('fullName')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    'ROLE_SALON_OWNER' => 'ROLE_SALON_OWNER',
                ],
                'multiple' => true, // Заборонити вибір більше одного значення
                'expanded' => true,  // Показати як радіокнопки
                'data' => ['ROLE_SALON_OWNER'], // Значення за замовчуванням
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'placeholder' => 'Change password',
        ]);



    }
}

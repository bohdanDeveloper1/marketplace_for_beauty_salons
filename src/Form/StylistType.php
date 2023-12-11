<?php

namespace App\Form;

use App\Entity\Stylist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StylistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('instagram_account')
            ->add('description')
            ->add('photo')
            //знайти всі доступні категорії в салоні і дати користувачу обрати потрібну категорію
           // ->add('category')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stylist::class,
        ]);
    }
}

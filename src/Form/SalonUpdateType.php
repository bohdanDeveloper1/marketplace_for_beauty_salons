<?php

namespace App\Form;

use App\Entity\Salon;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;
use function Webmozart\Assert\Tests\StaticAnalysis\string;
// TODO обслужить проблему з неправильно введеним мейлом (треба додати пошук мейлів)  додати можливість змінювати місто
class SalonUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('adress')
            ->add('description')
            ->add('salonOwnerEmail', TextType::class, [
                'data' => $options['ownerEmail'],
                'mapped' => false,
                'required' => false,
//                'data' => , // Значення за замовчуванням
                'constraints' => [
                    new Email(['message' => 'Invalid email address']),
                ]
            ])
            ->add('belongToCity', ChoiceType::class, [
                'mapped' => false,
                'required' => false,
                'data' => $options['salonCityName'],
                'placeholder' => $options['salonCityName'],
                //ChoiceType очікує, що значення буде асоціативним масивом
                // (де ключі будуть значеннями, які будуть відображатися в полі вибору).
                // ['city1' => 'city1', 'city2' => 'city2']
                // array_combine створює асоціативний масив
                'choices' => array_combine($options['avaliableCities'], $options['avaliableCities']),
            ])
            ->add('file', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '100M',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'You can upload only photo type there'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Salon::class,
            'ownerEmail' => String_::class,
            'avaliableCities' => [],
            'salonCityName' => String_::class,
        ]);
    }
}

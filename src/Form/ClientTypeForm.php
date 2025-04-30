<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class ClientTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Имя'
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Фамилия'
            ])
            ->add('phoneNumber', TelType::class, [
                'label' => 'Номер телефона (только российский)',
                'constraints' => [
                    new Regex([
                            'pattern' => '/^(\+7|8)\d{10}$/',
                            'message' => 'Введите правильный российский номер телефона'
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Э-почта'
            ])
            ->add('education', ChoiceType::class, [
                'label' => 'Образование',
                'choices' => [
                    'Среднее образование' => 'Среднее',
                    'Специальное образование' => 'Специальное',
                    'Высшее образование' => 'Высшее'
                ],
            ])
            ->add('giveAgreement', CheckboxType::class, [
                'required' => false,
                'label' => 'Я даю согласие на обработку моих личных данных'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Зарегистрировать'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}

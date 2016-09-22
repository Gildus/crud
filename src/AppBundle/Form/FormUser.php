<?php
/**
 * Created by PhpStorm.
 * User: denis.arosquipa
 * Date: 19/09/2016
 * Time: 12:51 PM
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class FormUser extends AbstractType
{

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        /*$resolver->setDefaults(array(
            'csrf_protection' => false,
            'data_class' => 'AppBundle\Entity\User',
        ));*/
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('POST')
            ->add('id', HiddenType::class,[
                'attr' => [
                    'ng-model' => 'formData.id'
                ]
            ])
            ->add('names', TextType::class, [
                'label' => 'Nombres Apellidos',
                'required' => true,
                'attr' => [
                    'ng-model' => 'formData.names'
                ]

            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => [
                    'ng-model' => 'formData.email'
                ]
            ])
            ->add('username', TextType::class, [
                'label' => 'Username',
                'required' => true,
                'attr' => [
                    'ng-model' => 'formData.username'
                ]

            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'required' => true,
                'attr' => [
                    'ng-model' => 'formData.password'
                ]
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Estado',
                'required' => true,
                'choices' => ['Activo' => true, 'NoActivo' => false],
                'attr' => [
                    'ng-model' => 'formData.status'
                ]
            ])->add('started', DateType::class, [
                'input' => 'string',
                'widget' => 'single_text',
                'html5' => false,
                'label' => 'Fecha Inicio',
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'class' => 'js-datepicker',
                    'ng-model' => 'formData.started',
                    'placeholder' => date('Y-m-d'),
                ]
            ])
            ->add('btnAdd', SubmitType::class,[
                'label' => 'Guardar',
                'attr' => [
                    'class' => 'ui button'
                ]
            ])
            ->add('btnCancel', ButtonType::class, [
                'label' => 'Cancelar',
                'attr' => [
                    'class' => 'ui button',
                    'onclick' => 'javascritp:location.href="/crud"',
                ]

            ])
        ;



    }

}
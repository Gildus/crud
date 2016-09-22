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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class FormUserDelete extends AbstractType
{

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('POST')
            ->add('id', HiddenType::class, [
                'required' => true,
                'attr' => [
                    'ng-model' => 'formData.id'
                ]
            ])
            ->add('btnSubmit', SubmitType::class, [
                'label' => 'Eliminar',
                'attr' => [
                    'class' => 'btn-default btn',
                ]
            ])
            ->add('btnCancel', ButtonType::class, [
                'label' => 'Cancelar',
                'attr' => [
                    'class' => 'btn-default btn',
                    'onclick' => 'javascript:location.href="/crud";'
                ]
            ])
        ;
    }

}
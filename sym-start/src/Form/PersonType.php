<?php

namespace App\Form;


use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;//$this->createFormBuilderと一緒のようなもの
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PersonType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name', TextType::class,  array('required' => false))
      ->add('mail', TextType::class,  array('required' => false))
      ->add('age', TextType::class,  array('required' => false))
      ->add('save', SubmitType::class, array('label' => 'Click'));
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    //フォームにPersonクラスをセットする。これでcontrollerでインスタンスを作ってセットする必要がなくなる。
    $resolver->setDefaults(array(
      'data_class' => Person::class,
    ));
  }
}

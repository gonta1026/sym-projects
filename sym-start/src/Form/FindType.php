<?php

namespace App\Form;


use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface; //$this->createFormBuilderと一緒のようなもの
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FindType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder //フォームビルダーインスタンス
      ->add('find', TextType::class)
      ->add('save', SubmitType::class, array('label' => 'Click'));
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    //フォームにPersonクラスをセットする。これでcontrollerでインスタンスを作ってセットする必要がなくなる。
    $resolver->setDefaults(array(
      'data_class' => FindForm::class,
    ));
  }
}

class FindForm//検索用のフォームの作成に必要なクラス
{
    private $find;

    public function getFind() //おそらく名前は規則的になっているので注意（get　＋　クラス名）
    {
        return $this->find;
    }
    public function setFind($find)//おそらく名前は規則的になっているので注意（set　＋　クラス名）
    {
        $this->find = $find;
    }
}
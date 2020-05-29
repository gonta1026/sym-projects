<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class HelloController extends AbstractController
{
    /**
     * @Route("/hello", name="hello")
     */
    public function index(Request $request)
    {
        $person = new Person();
        $person->setName('taro')
            ->setAge(36)
            ->setMail('taro@yamada.kun');


        $form = $this->createFormBuilder($person)
            ->add('name', TextType::class)
            ->add('age', IntegerType::class)
            ->add('mail', EmailType::class)
            ->add('save', SubmitType::class, ['label' => 'Click'])
            ->getForm();


        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $obj = $form->getData();
            $msg = 'Name: ' . $obj->getName() . '<br>'
                . 'Age: ' . $obj->getAge() . '<br>'
                . 'Mail: ' . $obj->getMail();
        } else {
            $msg = 'お名前をどうぞ！';
        }
        return $this->render('hello/index.html.twig', [
            'title' => 'Hello',
            'message' => $msg,
            'form' => $form->createView(),
        ]);
    }
}


// データクラス
class Person
{
    protected $name;
    protected $age;
    protected $mail;


    public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }


    public function getAge()
    {
        return $this->age;
    }
    public function setAge($age)
    {
        $this->age = $age;
        return $this;
    }


    public function getMail()
    {
        return $this->mail;
    }
    public function setMail($mail)
    {
        $this->mail = $mail;
        return $this;
    }
}

<?php

namespace App\Controller;


// 基本クラス
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//エンティティクラス
use App\Entity\Person;
// 基本クラス（たくさんのメソッドにアクセスできる）
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
#アノテーション
use Symfony\Component\Routing\Annotation\Route;
// フォームパーツ作成に必要
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class HelloController extends AbstractController
{
    /**
     * @Route("/hello", name="hello")
     */
    public function index(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Person::class);/* リポジトリーを取得 */
        $data = $repository->findall();
        return $this->render('hello/index.html.twig', [
            'title' => 'Hello',
            'data' => $data,
        ]);
    }

    /**
     * @Route("/find", name="find")
     */
    public function find(Request $request) //検索用のアクション
    {
        $formobj = new FindForm();
        $form = $this->createFormBuilder($formobj) /* フォームを作成 */
            ->add('find', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Click'))
            ->getForm();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);//フォームの情報を$formに結びつける
            $findstr = $form->getData()->getFind();/* 検索した文字列を取得 */
            $repository = $this->getDoctrine()->getRepository(Person::class);/* リポジトリーを取得 */
            $result = $repository->find($findstr);/* 結果を取得 */
        } else {
            $result = null;
        }
        return $this->render("hello/find.html.twig", [
            'title' => 'Hello',
            'form' => $form->createView(),
            'data' => $result,
        ]);
    }
    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {
        $person = new Person();
        $form = $this->createFormBuilder($person)
            ->add('name', TextType::class)
            ->add('mail', TextType::class)
            ->add('age', IntegerType::class)
            ->add('save', SubmitType::class, array('label' => 'Click'))
            ->getForm();


        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $person = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($person);
            $manager->flush();
            return $this->redirect('/hello');
        } else {
            return $this->render('hello/create.html.twig', [
                'title' => 'Hello',
                'message' => 'Create Entity',
                'form' => $form->createView(),
            ]);
        }
    }

}

//フォームの作成に必要なクラスを作成
class FindForm
{
    private $find;

    public function getFind() //おそらく名前は規則的になっているので注意
    {
        return $this->find;
    }
    public function setFind($find)
    {
        $this->find = $find;
    }
}
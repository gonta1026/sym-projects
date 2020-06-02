<?php

namespace App\Controller;


// 基本クラス
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//エンティティクラス
use App\Entity\Person;
// 基本クラス（たくさんのメソッドにアクセスできる）
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

//リクエスト、レスポンスクラス
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
#アノテーション
use Symfony\Component\Routing\Annotation\Route;
// フォームパーツ作成に必要
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use App\Form\PersonType;
// use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;//これいらないかも

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
            $find_id = $form->getData()->getFind();/* 検索した文字列を取得 */
            $repository = $this->getDoctrine()->getRepository(Person::class);/* リポジトリーを取得 */
            $result = $repository->find($find_id);/* 結果を取得 */
        } else {
            $result = null;
        }
        return $this->render("hello/find.html.twig", [
            'title' => 'Hello',
            'form' => $form->createView(),//ビューにフォームを作って渡す
            'data' => $result,
        ]);
    }

  /**
 * @Route("/create", name="create")
 */
public function create(Request $request)
{
    //$person = new Person();//インスタンスを作ってフォームに渡していたがconfigureOptionsであらかじめに設定していたら不要になる。
    //PersonType::classを呼びだすことであらかじめにフォームが作られたものを渡す。
    $form = $this->createForm(PersonType::class);
    $form->handleRequest($request);
    if ($request->getMethod() == 'POST'){
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
 
    /**
     * @Route("/update/{id}", name="update")
     */
    public function update(Request $request, Person $person)//createとほとんど変わらない
    {
        $form = $this->createForm(PersonType::class, $person);
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);//ハンドリング
            $manager = $this->getDoctrine()->getManager();//バインドされたフォームの値を取得
            $manager->flush();//Symfonyは取得されたエンティティの内容が変更される操作をすベてチェックしているため更新時はpersistは不要らしい。
            return $this->redirect('/hello');
        } else {
            return $this->render('hello/create.html.twig', [
                'title' => 'Hello',
                'message' => 'Update Entity id=' . $person->getId(),
                'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Request $request, Person $person)
    {
        $manager = $this->getDoctrine()->getManager();//マネージャーを作成
        $manager->remove($person);//削除準備
        $manager->flush();//DBへ永続化
        return $this->redirect('/hello');
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
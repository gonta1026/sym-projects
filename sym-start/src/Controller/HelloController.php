<?php

namespace App\Controller;


// 基本クラス
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//エンティティクラス
use App\Entity\Person;
use App\Entity\Message;
// 基本クラス（たくさんのメソッドにアクセスできる）
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

//リクエスト、レスポンスクラス
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
#アノテーション
use Symfony\Component\Routing\Annotation\Route;
// フォームパーツ作成に必要
use App\Form\PersonType;
use App\Form\FindType;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class HelloController extends AbstractController
{
    /**
     * @Route("/hello", name="hello")
     */
    public function index(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Person::class);/* リポジトリーを取得 */
        $messages = $this->getDoctrine()->getRepository(message::class);/* リポジトリーを取得 */
        $data = $repository->findall();
        $data02 = $messages->findall();
        return $this->render('hello/index.html.twig', [
            'title' => 'Hello',
            'data' => $data,
            'data02' => $data02,
        ]);
    }

    /**
     * @Route("/find", name="find")
     */
    public function find(Request $request)
    {
        $form = $this->createForm(FindType::class); //
        $repository = $this->getDoctrine() //Personリポジトリーを取得
            ->getRepository(Person::class);
        $manager = $this->getDoctrine()->getManager();
        $mapping = new ResultSetMappingBuilder($manager);//エンティティマネージャーを用意
        $mapping->addRootEntityFromClassMetadata('App\Entity\Person', 'p');//Personエンティティをセット。『p』は使っていない。
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $findstr = $form->getData()->getFind();//検索する文字列を取得
            $arr = explode(',', $findstr);//配列にする。
            $query = $manager->createNativeQuery(//第一引数にSQLを直接かける。第二引数にマッピングを入れる。
                'SELECT * FROM person WHERE age between ?1 AND ?2',
                $mapping
            )
                ->setParameters(array(1 => $arr[0], 2 => $arr[1]));//値をセットする。
            $result = $query->getResult();
        } else {
            $query = $manager->createNativeQuery(
                'SELECT * FROM person',
                $mapping
            );
            $result = $query->getResult();
        }
        return $this->render('hello/find.html.twig', [
            'title' => 'Hello',
            'form' => $form->createView(),
            'data' => $result,
        ]);
    }

    /**
     * @Route("/find02", name="find02")
     */
    public function find02(Request $request)
    {
        $form = $this->createForm(FindType::class);//
        $repository = $this->getDoctrine()
            ->getRepository(Person::class);
            
            if ($request->getMethod() == 'POST') {
            $manager = $this->getDoctrine()->getManager();
            $form->handleRequest($request);
            $findstr = $form->getData()->getFind();
            $query = $manager->createQuery(//DQLを使ったパターン
                "SELECT p FROM App\Entity\Person p 
            WHERE p.name = '{$findstr}'"
            );
            $result = $query->getResult();
        } else {
            $result = $repository->findAllwithSort();
        }
        return $this->render('hello/find.html.twig', [
            'title' => 'Hello',
            'form' => $form->createView(),
            'data' => $result,
        ]);
    }
    // $query = $manager->createQuery("SELECT p FROM App\Entity\Person pWHERE p.name = '{$findstr}' ");//DQLを使ったパターン
    // $query = $manager->createQuery("SELECT p FROM App\Entity\Person p");//全件取得

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request, ValidatorInterface $validator)
    {
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
                'form' => $form->createView()
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




// $result = $repository->findBy(['name' => $findstr]);

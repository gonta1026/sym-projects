<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class HogeController extends Controller
{
  /**
   * @Route("/hoge", name="hoge")
   */
  public function index()
  {
    return $this->render('hoge/index.html.twig', [
      'controller_name' => 'hoge',
    ]);
  }
}

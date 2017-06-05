<?php

namespace Louvre\ResaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class aProposController extends Controller
{
  public function vueAproposAction() {

    return $this->render('LouvreResaBundle:aPropos:aPropos.html.twig');
  }
}
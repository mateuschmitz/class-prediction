<?php

namespace Application\Controller;

class IndexController extends \Phalcon\Mvc\Controller
{
    public function index()
    {
//        var_dump(\Application\Model\AnoEscolar::find()->toArray());
//        var_dump(\Application\Model\DiaExtraLetivo::find()->toArray());
//        var_dump(\Application\Model\Disciplina::find()->toArray());
//        var_dump(\Application\Model\Feriado::find()->toArray());
//        var_dump(\Application\Model\PeriodoAnual::find()->toArray());
//        var_dump(\Application\Model\Turma::find()->toArray());
//        var_dump(\Application\Model\TurmaDisciplina::find()->toArray());
//        var_dump(\Application\Model\Usuario::find()->toArray());
//        exit;

        return $this->view->render('index', 'index');
    }
}
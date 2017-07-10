<?php

namespace Application\Controller;

class RelatoriosController extends BaseController
{
    public function aulasPorTrimestreAction()
    {
        if ($this->request->isPost()) {

            $dataPOST    = $this->request->getPost();
            $turmas      = array();
            $trimestre   = $dataPOST['trimestre'];

            foreach ($dataPOST['turmas'] as $turma) {

                $turma = \Application\Model\Turma::findFirst($turma);
                $turmas[$turma->getNome()] = array();

                foreach ($dataPOST['disciplinas'] as $disciplina) {
                    
                    $disciplina = \Application\Model\Disciplina::findFirst($disciplina);
                    $turmas[$turma->getNome()][$disciplina->getNome()] = array();

                    $horarios = \Application\Model\TurmaDisciplina::findFirst(
                        "turmaId = {$turma->getId()} AND disciplinaId = {$disciplina->getId()} AND periodoAnualId = {$trimestre}"
                    );

                    if ($horarios instanceOf \Application\Model\TurmaDisciplina) {
                        $turmas[$turma->getNome()][$disciplina->getNome()] = array(
                            1 => $horarios->getSegunda(), 2 => $horarios->getTerca(),
                            3 => $horarios->getQuarta(), 4 => $horarios->getQuinta(),
                            5 => $horarios->getSexta(),
                        );
                    }
                }                
            }
            
            $feriados = array();
            foreach (\Application\Model\Feriado::find()->toArray() as $feriado) {
                $feriados[] = $feriado['data'];
            }

            // $saturdays   = array('2017-03-25' => 5, '2017-05-13' => 5, '2017-05-20' => 5, '2017-06-03' => 5);
            $sabados = array();
            foreach (\Application\Model\DiaExtraLetivo::find()->toArray() as $sabado) {
                $sabados[$sabado['data']] = $sabado['diaSubstituido'];
            }

            $this->view->dados = $sabados;
            return $this->view->pick('relatorios/relatorioAulasPorTrimestre');
        }
    }
}
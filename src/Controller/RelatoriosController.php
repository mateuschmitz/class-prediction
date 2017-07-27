<?php

namespace Application\Controller;

class RelatoriosController extends BaseController
{
    public function aulasPorTrimestreAction()
    {
        if ($this->request->isPost()) {

            $dataPOST    = $this->request->getPost();
            $turmas      = array();
            $trimestre   = \Application\Model\PeriodoAnual::findFirst($dataPOST['trimestre']);

            foreach ($dataPOST['turmas'] as $turma) {

                $turma = \Application\Model\Turma::findFirst($turma);
                $turmas[$turma->getNome()] = array();

                foreach ($dataPOST['disciplinas'] as $disciplina) {
                    
                    $disciplina = \Application\Model\Disciplina::findFirst($disciplina);
                    $turmas[$turma->getNome()][$disciplina->getNome()] = array();

                    $horarios = \Application\Model\TurmaDisciplina::findFirst(
                        "turmaId = {$turma->getId()} AND disciplinaId = {$disciplina->getId()} AND periodoAnualId = {$trimestre->getId()}"
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

            $result = array();
            foreach ($turmas as $class => $subjectConfigs) {
                foreach ($subjectConfigs as $subject => $lessons) {

                    $date             = new \DateTime($trimestre->getInicio());
                    $result[$class][$subject] = array();

                    while ($date <= (new \DateTime($trimestre->getFim()))) {
                        if (isset($lessons[$date->format('N')]) && !in_array($date->format('Y-m-d'), $feriados)) {
                            for ($i = 1; $i <= $lessons[$date->format('N')]; $i++) {
                                $result[$class][$subject][strftime("%B", $date->getTimestamp())][] = $date->format('d');
                            }
                        }

                        // caso seja sábado letivo
                        if (isset($sabados[$date->format('Y-m-d')])) {
                            if (isset($lessons[$sabados[$date->format('Y-m-d')]])) {
                                for ($i = 1; $i <= $lessons[$sabados[$date->format('Y-m-d')]]; $i++) {
                                    $result[$class][$subject][strftime("%B", $date->getTimestamp())][] = $date->format('d');
                                }
                            } else {
                                $result[$class][$subject][strftime("%B", $date->getTimestamp())][] = $date->format('d');
                            }
                        }

                        $date->add(new \DateInterval('P1D'));
                    }
                }
            }

            $this->view->dados = $this->mountBody($result);
            return $this->view->pick('relatorios/relatorioAulasPorTrimestre');
        }
    }

    private function mountBody($result)
    {
        $html = "";
        foreach ($result as $class => $disciplina) {

            $html .= "\n+-----------+\n";
            $html .= "| Turma: {$class} |\n";
            $html .= "+-----------+\n";

            foreach ($disciplina as $subject => $months) {
                $html .= "\n{$subject}\n";
                $html .= str_repeat("-", strlen($subject)) . "\n";
                $total = 0;
                foreach ($months as $month => $days) {
                    $html .= utf8_encode($month) . " : " . implode(', ', $days) . "\n";
                    $total += count($days);
                }
                $html .= "TOTAL DE AULAS: {$total}\n";
            }
        }

        return $html;
    }
}
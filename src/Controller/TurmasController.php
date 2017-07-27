<?php

namespace Application\Controller;

use Application\Model\BaseModel;

class TurmasController extends BaseController
{
    public function indexAction()
    {}

    public function adicionarAction()
    {
        if ($this->request->isPost()) {

            try {
                $dados = $this->request->getPost();
                if (empty($dados['serie']) || empty($dados['nome'])) {
                    $this->flashSession->error("Por favor, preencha todos os campos!");
                    return $this->response->redirect($this->request->getHTTPReferer());
                }

                $turma = (new \Application\Model\Turma())
                    ->setSerie($dados['serie'])
                    ->setNome($dados['nome'])
                    ->setCreated(date('Y-m-d H:i:s'));

                if ($turma->create() == false) {
                    throw new \UnexpectedValueException("Não foi possível salvar a turma!");
                }
            } catch (\Exception $e) {
                $this->flashSession->error($e->getMessage());
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            $this->flashSession->success("Turma salva com sucesso!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }
    }

    public function editarAction($id)
    {
        $turma = \Application\Model\Turma::findFirst($id);
        if (!$turma) {
            $this->flashSession->error("Turma não encontrada!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        if ($this->request->isPost()) {

            try {
                $dados = $this->request->getPost();
                if (empty($dados['serie']) || empty($dados['nome'])) {
                    $this->flashSession->error("Por favor, preencha todos os campos!");
                    return $this->response->redirect($this->request->getHTTPReferer());
                }

                $turma->setSerie($dados['serie'])->setNome($dados['nome']);

                if ($turma->update() == false) {
                    throw new \UnexpectedValueException("Não foi possível alterar a turma!");
                }
            } catch (\Exception $e) {
                $this->flashSession->error($e->getMessage());
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            $this->flashSession->success("Turma alterada com sucesso!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        $this->view->turma = $turma;
    }

    public function excluirAction($id)
    {
        $turma = \Application\Model\Turma::findFirst($id);
        if (!$turma) {
            $this->flashSession->error("Turma não encontrada!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        try {
            if ($turma->delete() == false) {
                throw new \UnexpectedValueException("Não foi possível deletar a turma!");
            }
        } catch (\Exception $e){
            $this->flashSession->error($e->getMessage());
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        $this->flashSession->success("Turma deletada com sucesso!");
    }

    public function configurarAulasAction($turma, $trimestre)
    {
        $turma = \Application\Model\Turma::findFirst($turma);
        if (!$turma) {
            $this->flashSession->error("Turma não encontrada!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        $trimestre = \Application\Model\PeriodoAnual::findFirst($trimestre);
        if (!$trimestre) {
            $this->flashSession->error("Trimestre não encontrado!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        $this->view->turma = $turma;
        $this->view->trimestre  = $trimestre;
    }

    public function horariosAction($id)
    {
        $turma = \Application\Model\Turma::findFirst($id);
        if (!$turma) {
            $this->flashSession->error("Turma não encontrada!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        if (empty($this->request->getPost('disciplinas'))) {
            $this->flashSession->error("Dados não encontrados!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        $message = 'Horários atualizados com sucesso!';
        foreach ($this->request->getPost('disciplinas') as $idDiciplina => $dias) {
            try {
                $disciplina = \Application\Model\Disciplina::findFirst($idDiciplina);
                if (!$disciplina) {
                    throw new \UnexpectedValueException("Disciplina não encontrada: {$idDiciplina}");
                }

                $trimestre = \Application\Model\PeriodoAnual::findFirst($this->request->getPost('trimestre', 'int', 0));
                if (!$trimestre) {
                    throw new \UnexpectedValueException("Trimestre letivo não encontrado");
                }

                $horarios = \Application\Model\TurmaDisciplina::findFirst(
                    "turmaId = {$turma->getId()} AND disciplinaId = {$disciplina->getId()} AND periodoAnualId = {$trimestre->getId()}"
                );

                if (!$horarios) {
                    $horarios = (new \Application\Model\TurmaDisciplina)
                        ->setTurmaId($turma->getId())
                        ->setDisciplinaId($disciplina->getId())
                        ->setPeriodoAnualId($trimestre->getId())
                        ->setCreated(date('Y-m-d H:i:s'));
                }

                $horarios->setSegunda($dias['segunda'])
                    ->setTerca($dias['terca'])
                    ->setQuarta($dias['quarta'])
                    ->setQuinta($dias['quinta'])
                    ->setSexta($dias['sexta']);

                if ($horarios->save() == false) {
                    throw new \UnexpectedValueException("Não foi possível salvar os horários da turma!");
                }

            } catch (\Exception $e) {
                $this->flashSession->error($e->getMessage());
                continue;
            }

        }

        $this->flashSession->success($message);
        return $this->response->redirect($this->request->getHTTPReferer());
    }
}
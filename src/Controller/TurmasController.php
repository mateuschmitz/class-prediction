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

    public function horariosAction($id)
    {
        $turma = \Application\Model\Turma::findFirst($id);
        if (!$turma) {
            $this->flashSession->error("Turma não encontrada!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        $disciplina = \Application\Model\Disciplina::findFirst($this->request->getPost('disciplina', 'int', 0));
        if (!$disciplina) {
            $this->flashSession->error("Disciplina não encontrada!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        $trimestre = \Application\Model\PeriodoAnual::findFirst($this->request->getPost('trimestre', 'int', 0));
        if (!$trimestre) {
            $this->flashSession->error("Trimestre Letivo não encontrado!");
            return $this->response->redirect($this->request->getHTTPReferer());
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

        $horarios->setSegunda($this->request->getPost('segunda', 'int', 0))
            ->setTerca($this->request->getPost('terca', 'int', 0))
            ->setQuarta($this->request->getPost('quarta', 'int', 0))
            ->setQuinta($this->request->getPost('quinta', 'int', 0))
            ->setSexta($this->request->getPost('sexta', 'int', 0));

        try {
            if ($horarios->save() == false) {
                throw new \UnexpectedValueException("Não foi possível salvar os horários da turma!");
            }
        } catch (\Exception $e) {
            $this->flashSession->error($e->getMessage());
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        $this->flashSession->success("Horários atualizados com sucesso!");
        return $this->response->redirect($this->request->getHTTPReferer());
    }
}
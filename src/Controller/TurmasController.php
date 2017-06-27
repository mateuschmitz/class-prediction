<?php

namespace Application\Controller;

class TurmasController extends \Phalcon\Mvc\Controller
{
    public function index()
    {
        return $this->view->render('turmas', 'index');
    }

    public function adicionar()
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

        return $this->view->render('turmas', 'adicionar');
    }

    public function editar($id)
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
        return $this->view->render('turmas', 'editar');
    }

    public function excluir($id)
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
        return $this->response->redirect($this->request->getHTTPReferer());
    }
}
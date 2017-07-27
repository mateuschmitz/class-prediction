<?php

namespace Application\Controller;

class DisciplinasController extends BaseController
{
    public function indexAction()
    {}

    public function adicionarAction()
    {
        if ($this->request->isPost()) {

            try {
                $dados = $this->request->getPost();
                if (empty($dados['nome'])) {
                    $this->flashSession->error("Por favor, informe o nome da disciplina!");
                    return $this->response->redirect($this->request->getHTTPReferer());
                }

                $disciplina = (new \Application\Model\Disciplina())
                    ->setNome($dados['nome'])
                    ->setCreated(date('Y-m-d H:i:s'));

                if ($disciplina->create() == false) {
                    throw new \UnexpectedValueException("Não foi possível salvar a disciplina!");
                }
            } catch (\Exception $e) {
                $this->flashSession->error($e->getMessage());
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            $this->flashSession->success("Disciplina salva com sucesso!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }
    }

    public function editarAction($id)
    {
        $disciplina = \Application\Model\Disciplina::findFirst($id);
        if (!$disciplina) {
            $this->flashSession->error("Disciplina não encontrada!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        if ($this->request->isPost()) {

            try {
                $dados = $this->request->getPost();
                if (empty($dados['nome'])) {
                    $this->flashSession->error("Por favor, informe o nome da disciplina!");
                    return $this->response->redirect($this->request->getHTTPReferer());
                }

                $disciplina->setNome($dados['nome'])
                    ->setCreated(date('Y-m-d H:i:s'));

                if ($disciplina->update() == false) {
                    throw new \UnexpectedValueException("Não foi possível alterar a disciplina!");
                }
            } catch (\Exception $e) {
                $this->flashSession->error($e->getMessage());
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            $this->flashSession->success("Disciplina alterada com sucesso!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        $this->view->disciplina = $disciplina;
    }

    public function excluirAction($id)
    {
        $disciplina = \Application\Model\Disciplina::findFirst($id);
        if (!$disciplina) {
            $this->flashSession->error("Disciplina não encontrado!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        try {
            if ($disciplina->delete() == false) {
                throw new \UnexpectedValueException("Não foi possível deletar a disciplina!");
            }
        } catch (\Exception $e){
            $this->flashSession->error($e->getMessage());
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        $this->flashSession->success("Disciplina deletada com sucesso!");
    }
}
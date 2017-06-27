<?php

namespace Application\Controller;

class FeriadosController extends \Phalcon\Mvc\Controller
{
    public function index()
    {
        return $this->view->render('feriados', 'index');
    }

    public function adicionar()
    {
        if ($this->request->isPost()) {

            try {
                $dados = $this->request->getPost();
                if (empty($dados['data'])) {
                    $this->flashSession->error("Por favor, informe a data correta!");
                    return $this->response->redirect($this->request->getHTTPReferer());
                }

                $data = \DateTime::createFromFormat('d/m/Y', $dados['data']);
                $feriado = (new \Application\Model\Feriado())
                    ->setData($data->format('Y-m-d'))
                    ->setCreated(date('Y-m-d H:i:s'));

                if ($feriado->create() == false) {
                    throw new \UnexpectedValueException("Não foi possível salvar o feriado!");
                }
            } catch (\Exception $e) {
                $this->flashSession->error($e->getMessage());
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            $this->flashSession->success("Feriado salvo com sucesso!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        return $this->view->render('feriados', 'adicionar');
    }

    public function editar($id)
    {
        $feriado = \Application\Model\Feriado::findFirst($id);
        if (!$feriado) {
            $this->flashSession->error("Feriado não encontrado!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        if ($this->request->isPost()) {

            try {
                $dados = $this->request->getPost();
                if (empty($dados['data'])) {
                    $this->flashSession->error("Por favor, informe a data correta!");
                    return $this->response->redirect($this->request->getHTTPReferer());
                }

                $data = \DateTime::createFromFormat('d/m/Y', $dados['data']);
                $feriado->setData($data->format('Y-m-d'));

                if ($feriado->update() == false) {
                    throw new \UnexpectedValueException("Não foi possível alterar o feriado!");
                }
            } catch (\Exception $e) {
                $this->flashSession->error($e->getMessage());
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            $this->flashSession->success("Feriado alterado com sucesso!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        $this->view->feriado = $feriado;
        return $this->view->render('feriados', 'editar');
    }

    public function excluir($id)
    {
        $feriado = \Application\Model\Feriado::findFirst($id);
        if (!$feriado) {
            $this->flashSession->error("Feriado não encontrado!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        try {
            if ($feriado->delete() == false) {
                throw new \UnexpectedValueException("Não foi possível deletar o feriado!");
            }
        } catch (\Exception $e){
            $this->flashSession->error($e->getMessage());
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        $this->flashSession->success("Feriado deletado com sucesso!");
        return $this->response->redirect($this->request->getHTTPReferer());
    }
}
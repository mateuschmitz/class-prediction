<?php

namespace Application\Controller;

class TrimestresLetivosController extends BaseController
{
    public function index()
    {}

    public function adicionarAction()
    {
        if ($this->request->isPost()) {

            try {
                $dados = $this->request->getPost();
                if (empty($dados['ano']) || empty($dados['nome'])
                    || empty($dados['inicio']) || empty($dados['fim'])) {
                    $this->flashSession->error("Por favor, preencha todos os campos!");
                    return $this->response->redirect($this->request->getHTTPReferer());
                }

                $ano = \Application\Model\AnoEscolar::findFirst($dados['ano']);
                if (!$ano) {
                    $this->flashSession->error("Ano Escolar não encontrado!");
                    return $this->response->redirect($this->request->getHTTPReferer());
                }

                $inicio = \DateTime::createFromFormat('d/m/Y', $dados['inicio']);
                $fim    = \DateTime::createFromFormat('d/m/Y', $dados['fim']);

                $trimestre = (new \Application\Model\PeriodoAnual())
                    ->setAnoEscolarId($ano->getId())
                    ->setNome($dados['nome'])
                    ->setinicio($inicio->format('Y-m-d'))
                    ->setFim($fim->format('Y-m-d'))
                    ->setCreated(date('Y-m-d H:i:s'));

                if ($trimestre->create() == false) {
                    throw new \UnexpectedValueException("Não foi possível salvar o Trimestre Letivo!");
                }
            } catch (\Exception $e) {
                $this->flashSession->error($e->getMessage());
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            $this->flashSession->success("Trimestre Letivo salvo com sucesso!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }
    }

    public function editarAction($id)
    {
        $trimestre = \Application\Model\PeriodoAnual::findFirst($id);
        if (!$trimestre) {
            $this->flashSession->error("Trimestre Letivo não encontrado!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        if ($this->request->isPost()) {

            try {
                $dados = $this->request->getPost();
                if (empty($dados['ano']) || empty($dados['nome'])
                    || empty($dados['inicio']) || empty($dados['fim'])) {
                    $this->flashSession->error("Por favor, preencha todos os campos!");
                    return $this->response->redirect($this->request->getHTTPReferer());
                }

                $ano = \Application\Model\AnoEscolar::findFirst($dados['ano']);
                if (!$ano) {
                    $this->flashSession->error("Ano Escolar não encontrado!");
                    return $this->response->redirect($this->request->getHTTPReferer());
                }

                $inicio = \DateTime::createFromFormat('d/m/Y', $dados['inicio']);
                $fim    = \DateTime::createFromFormat('d/m/Y', $dados['fim']);

                $trimestre->setAnoEscolarId($ano->getId())
                    ->setNome($dados['nome'])
                    ->setinicio($inicio->format('Y-m-d'))
                    ->setFim($fim->format('Y-m-d'));

                if ($trimestre->update() == false) {
                    throw new \UnexpectedValueException("Não foi possível alterar o Trimestre Letivo!");
                }
            } catch (\Exception $e) {
                $this->flashSession->error($e->getMessage());
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            $this->flashSession->success("Trimestre Letivo alterado com sucesso!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        $this->view->trimestre = $trimestre;
    }

    public function excluirAction($id)
    {
        $trimestre = \Application\Model\PeriodoAnual::findFirst($id);
        if (!$trimestre) {
            $this->flashSession->error("Trimestre Letivo não encontrado!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        try {
            if ($trimestre->delete() == false) {
                throw new \UnexpectedValueException("Não foi possível deletar o Trimestre Letivo!");
            }
        } catch (\Exception $e){
            $this->flashSession->error($e->getMessage());
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        $this->flashSession->success("Trimestre Letivo deletado com sucesso!");
    }
}
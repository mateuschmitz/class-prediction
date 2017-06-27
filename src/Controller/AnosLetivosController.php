<?php

namespace Application\Controller;

class AnosLetivosController extends \Phalcon\Mvc\Controller
{
    public function index()
    {
        return $this->view->render('anos-letivos', 'index');
    }

    public function adicionar()
    {
        if ($this->request->isPost()) {

            try {
                $dados = $this->request->getPost();
                if (!isset($dados['ano']) || empty($dados['ano'])) {
                    $this->flashSession->error("Por favor, selecione o ano!");
                    return $this->response->redirect($this->request->getHTTPReferer());
                }

                $inicio = null;
                $fim = null;
                if (isset($dados['inicio']) && !empty($dados['inicio'])) {
                    $inicio = \DateTime::createFromFormat('d/m/Y', $dados['inicio']);
                }

                if (isset($dados['fim']) && !empty($dados['fim'])) {
                    $fim = \DateTime::createFromFormat('d/m/Y', $dados['fim']);
                }

                $anoLetivo = (new \Application\Model\AnoEscolar())
                    ->setAno($dados['ano'])
                    ->setinicio(($inicio instanceof \DateTime) ? $inicio->format('Y-m-d') : null)
                    ->setFim(($fim instanceof \DateTime) ? $fim->format('Y-m-d') : null)
                    ->setCreated(date('Y-m-d H:i:s'));

                if ($anoLetivo->create() == false) {
                    throw new \UnexpectedValueException("Não foi possível salvar o Ano Letivo!");
                }
            } catch (\Exception $e) {
                $this->flashSession->error($e->getMessage());
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            $this->flashSession->success("Ano Letivo salvo com sucesso!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        return $this->view->render('anos-letivos', 'adicionar');
    }

    public function editar($id)
    {
        $anoLetivo = \Application\Model\AnoEscolar::findFirst($id);
        if (!$anoLetivo) {
            $this->flashSession->error("Ano Letivo não encontrado!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        if ($this->request->isPost()) {

            try {
                $dados = $this->request->getPost();
                if (!isset($dados['ano']) || empty($dados['ano'])) {
                    $this->flashSession->error("Por favor, selecione o ano!");
                    return $this->response->redirect($this->request->getHTTPReferer());
                }

                $inicio = null;
                $fim    = null;
                if (isset($dados['inicio']) && !empty($dados['inicio'])) {
                    $inicio = \DateTime::createFromFormat('d/m/Y', $dados['inicio']);
                }

                if (isset($dados['fim']) && !empty($dados['fim'])) {
                    $fim = \DateTime::createFromFormat('d/m/Y', $dados['fim']);
                }

                $anoLetivo->setAno($dados['ano'])
                    ->setinicio(($inicio instanceof \DateTime) ? $inicio->format('Y-m-d') : null)
                    ->setFim(($fim instanceof \DateTime) ? $fim->format('Y-m-d') : null);

                if ($anoLetivo->update() == false) {
                    throw new \UnexpectedValueException("Não foi possível atualiza o Ano Letivo!");
                }
            } catch (\Exception $e) {
                $this->flashSession->error($e->getMessage());
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            $this->flashSession->success("Ano Letivo atualizado com sucesso!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        $this->view->anoLetivo = $anoLetivo;
        return $this->view->render('anos-letivos', 'editar');
    }

    public function excluir($id)
    {
        $anoLetivo = \Application\Model\AnoEscolar::findFirst($id);
        if (!$anoLetivo) {
            $this->flashSession->error("Ano Letivo não encontrado!");
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        try {
            if ($anoLetivo->delete() == false) {
                throw new \UnexpectedValueException("Não foi possível deletar o Ano Letivo!");
            }
        } catch (\Exception $e){
            $this->flashSession->error($e->getMessage());
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        $this->flashSession->success("Ano Letivo deletado com sucesso!");
        return $this->response->redirect($this->request->getHTTPReferer());
    }
}
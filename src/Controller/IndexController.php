<?php

namespace Application\Controller;

class IndexController extends BaseController
{
    public function indexAction()
    {
        return $this->response->redirect('/turmas');
    }
}
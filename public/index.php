<?php

define('DS', DIRECTORY_SEPARATOR);
date_default_timezone_set('America/Sao_Paulo');

$app = new \Phalcon\Mvc\Micro();
$app['db'] = function() {
    return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
        "host"     => "localhost",
        "username" => "root",
        "password" => "",
        "dbname"   => "class-prediction",
        "charset"  => "utf8"
    ));
};

$app['view'] = function() {
    $view = new \Phalcon\Mvc\View();
    $view->setViewsDir('..' . DS . 'src' . DS . 'View' . DS);
    return $view;
};

$app['session'] = function() {
    $session = new \Phalcon\Session\Adapter\Files();
    $session->start();
    return $session;
};

$app['flashSession'] = function() {
    $flash = new \Phalcon\Flash\Session(
        array(
            'error'   => 'alert alert-danger',
            'success' => 'alert alert-success',
            'notice'  => 'alert alert-info',
            'warning' => 'alert alert-warning'
        )
    );
    return $flash;
};

$loader = new \Phalcon\Loader();
$loader->registerDirs(array(
    '..' . DS . 'src' . DS . 'Model' . DS
))->register();

$loader->registerNamespaces(array(
        'Application' => ".." . DS . "src" . DS,
));

/**
 * INDEX
 */
$index = new \Phalcon\Mvc\Micro\Collection();
$index->setHandler('Application\Controller\IndexController', true);
$index->get('/', 'index');

/**
 * ANOS
 */
$anosLetivos = new \Phalcon\Mvc\Micro\Collection();
$anosLetivos->setHandler('Application\Controller\AnosLetivosController', true);
$anosLetivos->setPrefix('/anos-letivos');
$anosLetivos->get('/', 'index');
$anosLetivos->get('/adicionar', 'adicionar');
$anosLetivos->post('/adicionar', 'adicionar');
$anosLetivos->get('/{id}/editar', 'editar');
$anosLetivos->post('/{id}/editar', 'editar');
$anosLetivos->get('/{id}/excluir', 'excluir');

/**
 * TRIMESTRES
 */
$trimestresLetivos = new \Phalcon\Mvc\Micro\Collection();
$trimestresLetivos->setHandler('Application\Controller\TrimestresLetivosController', true);
$trimestresLetivos->setPrefix('/trimestres-letivos');
$trimestresLetivos->get('/', 'index');
$trimestresLetivos->get('/adicionar', 'adicionar');
$trimestresLetivos->post('/adicionar', 'adicionar');
$trimestresLetivos->get('/{id}/editar', 'editar');
$trimestresLetivos->post('/{id}/editar', 'editar');
$trimestresLetivos->get('/{id}/excluir', 'excluir');

/**
 * TURMAS
 */
$turmas = new \Phalcon\Mvc\Micro\Collection();
$turmas->setHandler('Application\Controller\TurmasController', true);
$turmas->setPrefix('/turmas');
$turmas->get('/', 'index');
$turmas->get('/adicionar', 'adicionar');
$turmas->post('/adicionar', 'adicionar');
$turmas->get('/{id}/editar', 'editar');
$turmas->post('/{id}/editar', 'editar');
$turmas->get('/{id}/excluir', 'excluir');

/**
 * DISCIPLINAS
 */
$disciplinas = new \Phalcon\Mvc\Micro\Collection();
$disciplinas->setHandler('Application\Controller\DisciplinasController', true);
$disciplinas->setPrefix('/disciplinas');
$disciplinas->get('/', 'index');
$disciplinas->get('/adicionar', 'adicionar');
$disciplinas->post('/adicionar', 'adicionar');
$disciplinas->get('/{id}/editar', 'editar');
$disciplinas->post('/{id}/editar', 'editar');
$disciplinas->get('/{id}/excluir', 'excluir');

$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo '<center><h1>Desculpe, esta página não existe</h1></center>';
});


$app->mount($index);
$app->mount($anosLetivos);
$app->mount($trimestresLetivos);
$app->mount($turmas);
$app->mount($disciplinas);
$app->handle();
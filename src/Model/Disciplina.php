<?php

namespace Application\Model;

class Disciplina extends BaseModel
{
    protected $id;

    protected $nome;

    protected $domingo;

    protected $segunda;

    protected $terca;

    protected $quarta;

    protected $quinta;

    protected $sexta;

    protected $sabado;

    protected $created;

    protected $updated;

    public function initialize()
    {
        $this->setSource('disciplinas');
        $this->hasManyToMany(
            "id", __NAMESPACE__ . "\\TurmaDisciplina",
            "disciplinaId", "turmaId",
            __NAMESPACE__ . "\\Turmas", "id",
            array('alias' => 'turmas')
        );
        $this->hasManyToMany(
            "id", __NAMESPACE__ . "\\TurmaDisciplina",
            "disciplinaId", "periodoAnualId",
            __NAMESPACE__ . "\\PeriodoAnual", "id",
            array('alias' => 'periodosAnuais')
        );
    }

    public function validation()
    {
        return $this->validationHasFailed() != true;
    }
}
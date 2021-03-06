<?php

namespace Application\Model;

class Turma extends BaseModel
{
    protected $id;

    protected $nome;

    protected $serie;

    protected $created;

    protected $updated;

    public function initialize()
    {
        $this->setSource('turmas');
        $this->hasManyToMany(
            "id", __NAMESPACE__ . "\\TurmaDisciplina",
            "turmaId", "disciplinaId",
            __NAMESPACE__ . "\\Disciplina", "id",
            array('alias' => 'disciplinas')
        );
        $this->hasManyToMany(
            "id", __NAMESPACE__ . "\\TurmaDisciplina",
            "turmaId", "periodoAnualId",
            __NAMESPACE__ . "\\PeriodoAnual", "id",
            array('alias' => 'periodosAnuais')
        );
    }

    public function validation()
    {
        return $this->validationHasFailed() != true;
    }
}
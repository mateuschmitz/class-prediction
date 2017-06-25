<?php

namespace Application\Model;

class Disciplina extends BaseModel
{
    protected $id;

    protected $periodoAnualId;

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
        $this->belongsTo('periodoAnualId', __NAMESPACE__ . '\\PeriodosAnuais', 'id', array('alias' => 'periodoAnual'));
        $this->hasManyToMany(
            "id", __NAMESPACE__ . "\\TurmasDisciplinas",
            "disciplinaId", "turmaId",
            __NAMESPACE__ . "\\Turmas", "id",
            array('alias' => 'turmasDisciplinas')
        );
    }

    public function validation()
    {
        return $this->validationHasFailed() != true;
    }
}
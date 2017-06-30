<?php

namespace Application\Model;

class TurmaDisciplina extends BaseModel
{
    protected $turmaId;

    protected $disciplinaId;

    protected $periodoAnualId;

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
        $this->setSource('turmas_disciplinas');
        $this->belongsTo('turmaId', __NAMESPACE__ . '\\Turma', 'id', array('alias' => 'turma'));
        $this->belongsTo('disciplinaId', __NAMESPACE__ . '\\Disciplina', 'id', array('alias' => 'disciplina'));
        $this->belongsTo('periodoAnualId', __NAMESPACE__ . '\\PeriodoAnual', 'id', array('alias' => 'periodoAnual'));
    }

    public function validation()
    {
        return $this->validationHasFailed() != true;
    }
}
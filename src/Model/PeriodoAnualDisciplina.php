<?php

namespace Application\Model;

class PeriodoAnualDisciplina extends BaseModel
{
    protected $periodoAnualId;

    protected $disciplinaId;

    public function initialize()
    {
        $this->setSource('periodos_anuais_disciplinas');
        $this->belongsTo('periodoAnualId', __NAMESPACE__ . '\\PeriodoAnual', 'id', array('alias' => 'periodoAnual'));
        $this->belongsTo('disciplinaId', __NAMESPACE__ . '\\Disciplina', 'id', array('alias' => 'disciplina'));
    }

    public function validation()
    {
        return $this->validationHasFailed() != true;
    }
}
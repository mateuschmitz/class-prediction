<?php

namespace Application\Model;

class PeriodoAnual extends BaseModel
{
    protected $id;

    protected $anoEscolarId;

    protected $nome;

    protected $inicio;

    protected $fim;

    protected $created;

    protected $updated;

    public function initialize()
    {
        $this->setSource('periodos_anuais');
        $this->belongsTo('anoEscolarId', __NAMESPACE__ . '\\AnoEscolar', 'id', array('alias' => 'anoEscolar'));
        $this->hasManyToMany(
            "id", __NAMESPACE__ . "\\TurmaDisciplina",
            "periodoAnualId", "disciplinaId",
            __NAMESPACE__ . "\\Disciplina", "id",
            array('alias' => 'disciplinas')
        );
        $this->hasManyToMany(
            "id", __NAMESPACE__ . "\\TurmaDisciplina",
            "periodoAnualId", "turmaId",
            __NAMESPACE__ . "\\Turmas", "id",
            array('alias' => 'turmas')
        );
    }

    public function validation()
    {
        return $this->validationHasFailed() != true;
    }
}
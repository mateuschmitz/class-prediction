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
            "id", __NAMESPACE__ . "\\PeriodoAnualDisciplina",
            "periodoAnualId", "disciplinaId",
            __NAMESPACE__ . "\\Disciplina", "id",
            array('alias' => 'disciplinas')
        );
    }

    public function validation()
    {
        return $this->validationHasFailed() != true;
    }
}
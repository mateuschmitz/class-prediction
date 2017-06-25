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
        $this->belongsTo('anoEscolarId', __NAMESPACE__ . '\\AnosEscolares', 'id', array('alias' => 'anoEscolar'));
        $this->hasMany("id", __NAMESPACE__ . "\\Disciplinas", "periodoAnualId", array('alias' => 'disciplinas'));
    }

    public function validation()
    {
        return $this->validationHasFailed() != true;
    }
}
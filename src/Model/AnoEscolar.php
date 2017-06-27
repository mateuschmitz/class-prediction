<?php

namespace Application\Model;

class AnoEscolar extends BaseModel
{
    protected $id;

    protected $ano;

    protected $inicio;

    protected $fim;

    protected $created;

    protected $updated;

    public function initialize()
    {
        $this->setSource('anos_escolares');
        $this->hasMany("id", __NAMESPACE__ . "\\PeriodoAnual", "anoEscolarId", array('alias' => 'periodosAnuais'));
    }

    public function validation()
    {
        return $this->validationHasFailed() != true;
    }
}
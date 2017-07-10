<?php

namespace Application\Model;

class DiaExtraLetivo extends BaseModel
{
    protected $id;

    protected $data;

    protected $diaSubstituido;

    protected $created;

    protected $updated;

    public function initialize()
    {
        $this->setSource('dias_extras_letivos');
    }

    public function validation()
    {
        return $this->validationHasFailed() != true;
    }
}
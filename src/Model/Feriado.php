<?php

namespace Application\Model;

class Feriado extends BaseModel
{
    protected $id;

    protected $data;

    protected $created;

    protected $updated;

    public function initialize()
    {
        $this->setSource('feriados');
    }

    public function validation()
    {
        return $this->validationHasFailed() != true;
    }
}
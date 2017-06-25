<?php

namespace Application\Model;

class Usuario extends BaseModel
{
    protected $id;

    protected $nome;

    protected $senha;

    protected $email;

    protected $created;

    protected $updated;

    public function initialize()
    {
        $this->setSource('usuarios');
    }

    public function validation()
    {
        return $this->validationHasFailed() != true;
    }
}
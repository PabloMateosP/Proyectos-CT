<?php

class classProjectEmployee
{
    public $id_employee;
    public $id_project;

    function __construct(

        $id_employee = null,
        $id_project = null

    ) {

        $this->id_employee = $id_employee;
        $this->id_project = $id_project;
        
    }

}

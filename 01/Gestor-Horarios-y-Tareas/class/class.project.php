<?php

class classProject
{

    public $id;
    public $project;
    public $description;       
    public $id_projectManager;
    public $id_customer;
    public $created_at;
    public $finish_date;
    public $update_at;

    function __construct(

        $id = null,
        $project = null,
        $description = null,
        $id_projectManager = null,
        $id_customer = null,
        $created_at = null,
        $finish_date = null,
        $update_at = null,

    ) {

        $this->id=$id;
        $this->project= $project;
        $this->description= $description;
        $this->id_projectManager= $id_projectManager;
        $this->id_customer= $id_customer;
        $this->created_at= $created_at;
        $this->finish_date= $finish_date;
        $this->update_at= $update_at;

    }

}

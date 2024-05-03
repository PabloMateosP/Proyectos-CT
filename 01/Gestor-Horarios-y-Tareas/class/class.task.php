<?php

class classTask
{

    public $id;
    public $task;
    public $description;
    public $id_project;
    public $created_at;
    public $update_at;


    function __construct(

        $id = null,
        $task = null,
        $description = null,
        $id_project = null,
        $created_at = null,
        $update_at = null,

    ) {

        $this->id = $id;
        $this->task = $task;
        $this->description = $description;
        $this->id_project = $id_project;
        $this->created_at = $created_at;
        $this->update_at = $update_at;

    }

}
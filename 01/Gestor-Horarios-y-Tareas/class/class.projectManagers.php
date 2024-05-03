<?php

class classProjectManagers
{
    public $id;
    public $last_name;
    public $name;
    public $created_at;
    public $update_at;

    function __construct(

        $id = null,
        $last_name = null,
        $name = null,
        $created_at = null,
        $update_at = null,

    ){

        $this->id = $id;
        $this->last_name = $last_name;
        $this->name = $name;
        $this->created_at = $created_at;
        $this->update_at = $update_at;

    }

}

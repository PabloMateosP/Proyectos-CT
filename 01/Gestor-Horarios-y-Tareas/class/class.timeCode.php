<?php

class classTimeCodes
{
    public $id;
    public $time_code;
    public $description;
    public $created_at;
    public $update_at;

    function __construct(

        $id = null,
        $time_code = null,
        $description = null,
        $created_at = null,
        $update_at = null,

    ){

        $this->id = $id;
        $this->time_code = $time_code;
        $this->description = $description;
        $this->created_at = $created_at;
        $this->update_at = $update_at;

    }
}

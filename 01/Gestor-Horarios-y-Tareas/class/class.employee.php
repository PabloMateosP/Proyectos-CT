<?php

class classEmployee
{

    public $id;
    public $identification;
    public $last_name;
    public $name;       
    public $phone;
    public $city;
    public $dni;
    public $email;
    public $total_hours;
    public $create_at;
    public $update_at;
    

    function __construct(

        $id = null,
        $identification = null,
        $last_name = null,
        $name = null,
        $phone = null,
        $city = null,
        $dni = null,
        $email = null,
        $total_hours = null,
        $id_user = null,
        $create_at = null,
        $update_at = null,

    ) {

        $this->id=$id;
        $this->identification=$identification;
        $this->name=$name;
        $this->last_name= $last_name;
        $this->phone= $phone;
        $this->city= $city;
        $this->dni= $dni;
        $this->email=$email;
        $this->total_hours= $total_hours;
        $this->id_user= $id_user;
        $this->create_at= $create_at;
        $this->update_at= $update_at;

    }

}

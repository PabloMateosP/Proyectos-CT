<?php

class classCustomer
{

    public $id;
    public $name;
    public $phone;       
    public $city;
    public $address;
    public $email;
    public $created_at;
    public $update_at;

    function __construct(

        $id = null,
        $name = null,
        $phone = null,
        $city = null,
        $address = null,
        $email = null,
        $created_at = null,
        $update_at = null

    ) {

        $this->id=$id;
        $this->name= $name;
        $this->phone= $phone;
        $this->city= $city;
        $this->address= $address;
        $this->email= $email;
        $this->created_at= $created_at;
        $this->update_at= $update_at;

    }

}

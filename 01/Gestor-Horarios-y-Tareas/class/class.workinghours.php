<?php

class classWorkingHours
{

    public $id;
    public $id_employee;
    public $id_time_code;
    public $id_project;
    public $id_task;
    public $description;
    public $duration;
    public $date_worked;
    public $created_at;
    public $update_at;

    public function __construct(

        $id = null,
        $id_employee = null,
        $id_time_code = null,
        $id_project = null,
        $id_task = null,
        $description = null,
        $duration = null,
        $date_worked = null,
        $created_at = null,
        $update_at = null

    ) {
        $this->id = $id;

        $this->id_employee = $id_employee;
        $this->id_time_code = $id_time_code;
        $this->id_project = $id_project;
        $this->id_task = $id_task;
        $this->description = $description;
        $this->duration = $duration;
        $this->date_worked = $date_worked;
        $this->created_at = $created_at;
        $this->update_at = $update_at;
    }


}

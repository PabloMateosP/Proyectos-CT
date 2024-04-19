<?php
class classUser
{

    public $id;
    public $name;
    public $email;
    public $password;
    public $create_at;
    public $update_at;

    public function __construct(
        $id = null,
        $name = null,
        $email = null,
        $password = null,
        $create_at = null,
        $update_at = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->create_at= $create_at;
        $this->update_at= $update_at;
    }


}

<?php

namespace App\Src\Entity;
class User
{
    public $id;
    public $firstname;
    public $lastname;
    public $email;

    public function __construct($id, $firstname, $lastname, $email)
    {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
    }

    public static function getMixedAttributes() {
        return array(
            'first_name' => '[user:first_name]'
        );
    }
}

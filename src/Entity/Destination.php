<?php

namespace App\Src\Entity;

use App\Src\Repository\DestinationRepository;

class Destination
{
    public $id;
    public $countryName;
    public $conjunction;
    public $name;
    public $computerName;

    public function __construct($id, $countryName, $conjunction, $computerName)
    {
        $this->id = $id;
        $this->countryName = $countryName;
        $this->conjunction = $conjunction;
        $this->computerName = $computerName;
    }

    public static function setDestination($text, $quoteAttributes, $destinationId) {
        if(strpos($text, $quoteAttributes['destination_link'])){
            return DestinationRepository::getInstance()->getById($destinationId);
        }
    }
}

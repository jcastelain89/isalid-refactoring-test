<?php

namespace App\Src\Entity;

class Text
{
    public $text;

    public function __construct($quoteDependencies, $quoteAttributes)
    {
        if (isset($quoteDependencies['destination']))
        {
            return $this->text = str_replace($quoteAttributes['destination_link'], $quoteDependencies['usefulObject']->url . '/' . $quoteDependencies['destination']->countryName . '/quote/' . $quoteDependencies['_quoteFromRepository']->id, $quoteDependencies['text']);
        }
        return $this->text = str_replace($quoteAttributes['destination_link'], '', $quoteDependencies['text']);
    }

    public static function setUserAttributes($text, $userAttributes, $_user)
    {
        if ($_user and (strpos($text, $userAttributes['first_name']))) {
             return str_replace($userAttributes['first_name'], ucfirst(mb_strtolower($_user->firstname)), $text);
        }
    }
}

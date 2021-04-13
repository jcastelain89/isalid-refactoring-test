<?php

namespace App\Example;
include('./src/Entity/Template.php');
include('./src/Entity/Quote.php');
include('./src/TemplateManager.php');
require_once __DIR__ . '/../vendor/autoload.php';

use App\Src\Entity\Quote;
use App\Src\Entity\Template;
use App\Src\TemplateManager;


$faker = \Faker\Factory::create();

$template = new Template(
    1,
    'Votre livraison à [quote:destination_name]',
    "
Bonjour [user:first_name],

Merci de nous avoir contacté pour votre livraison à [quote:destination_name].

Bien cordialement,

L'équipe de Shipper
");
$templateManager = new TemplateManager();

$message = $templateManager->getTemplateComputed(
    $template,
    [
        'quote' => new Quote($faker->randomNumber(), $faker->randomNumber(), $faker->randomNumber(), $faker->date())
    ]
);

echo $message->subject . "\n" . $message->content;

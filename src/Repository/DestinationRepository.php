<?php

namespace App\Src\Repository;

include('./src/Entity/Destination.php');
use App\Src\Entity\Destination;
use App\Src\Helper\SingletonTrait;

class DestinationRepository implements Repository
{
    use SingletonTrait;
    /**
     * @param int $id
     *
     * @return Destination
     */
    public function getById($id)
    {
        // DO NOT MODIFY THIS METHOD
        $generator    = \Faker\Factory::create();
        $generator->seed($id);

        return new Destination(
            $id,
            $generator->country,
            'en',
            $generator->slug()
        );
    }
}

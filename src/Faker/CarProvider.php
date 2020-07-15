<?php

namespace App\Faker;


use Faker\Provider\Base;

class CarProvider extends Base{
    const CARBURANT = [
        'electrique',
        'diesel',
        'essence'
    ];

    const COLOR = [
        'noir',
        'bleu',
        'vert'
    ];
    public function carCarburant(){
        return self::randomElement(self::CARBURANT);
    }
}
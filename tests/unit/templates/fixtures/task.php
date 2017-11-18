<?php
/**
 * User: demius
 * Date: 18.11.17
 * Time: 23:07
 */


/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'caption' => $faker->sentence(),
    'description' => $faker->text(),
    // треть толькочто открытые, остальные с разным прогрессом c шагом 10
    'progress' => ($faker->numberBetween(0,2))?0:$faker->numberBetween(0, 10)*10,
];
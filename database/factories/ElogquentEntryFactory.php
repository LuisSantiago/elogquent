<?php

namespace Elogquent\Database\Factories;

use Elogquent\Models\ElogquentEntry;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ElogquentEntryFactory extends Factory
{
    public function modelName(): string
    {
        return ElogquentEntry::class;
    }

    public function definition(): array
    {
        $faker = FakerFactory::create();

        return [
            'model_id' => $faker->numerify('##'),
            'model_type' => Str::camel(implode(' ', $faker->words())),
            'column' => $faker->word(),
            'value' => $faker->word(),
            'created_at' => $faker->dateTime(),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => 'default',
            'phone' => '01129282822',
            'tax_number' => '2376232983712',
            'address' => 'cairo, egypt',
            'email' => 'default@paymenthub.com',
            'status' => 1
        ];
    }
}

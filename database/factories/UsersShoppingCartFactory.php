<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UsersShoppingCart>
 */
class UsersShoppingCartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'=>3,
            'product_id'=>2,
            'name'=>'褲子',
            'price'=>200,
            'quantity'=>2,
        ];
    }
}

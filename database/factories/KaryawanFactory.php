<?php

namespace Database\Factories;

use App\Enums\RolePosition;
use App\Models\Karyawan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Karyawan>
 */
class KaryawanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'alamat' => fake()->address(),
            'noTelepon' => fake()->phoneNumber(),
            'jabatan' => fake()->randomElement(RolePosition::cases())->value,
            'username' => fake()->userName(),
            'password' => '123456789'
        ];
    }
}

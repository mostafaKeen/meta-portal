<?php

namespace Modules\Company\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Company\Models\WhatsappNumber;
use Modules\Company\Models\Company;

class WhatsappNumberFactory extends Factory
{
    protected $model = WhatsappNumber::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'type' => 'qr',
            'phone_number' => $this->faker->phoneNumber,
            'status' => 'active',
        ];
    }

    public function api(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'api',
            'app_name' => $this->faker->word,
            'app_id' => $this->faker->uuid,
            'app_token' => $this->faker->sha256,
        ]);
    }
}

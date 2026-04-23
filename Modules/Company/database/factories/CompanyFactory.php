<?php

namespace Modules\Company\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Company\Models\Company;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'domain_slug' => $this->faker->unique()->slug,
            'email' => $this->faker->unique()->companyEmail,
            'status' => 'active',
        ];
    }
}

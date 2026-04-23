<?php

namespace Modules\Company\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Company\Models\Company;
use Modules\Plans\Models\Plan;
use Modules\Plans\Models\Subscription;
use Modules\Company\Models\TelegramBot;

class TelegramBotLimitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_checks_if_telegram_bot_limit_is_reached()
    {
        $company = Company::create([
            'name' => 'Test Company',
            'email' => 'test@example.com',
            'domain_slug' => 'test-company',
            'status' => 'active',
        ]);
        $plan = Plan::create([
            'name' => 'Basic Plan',
            'price' => 10,
            'max_telegram_bots' => 2,
        ]);

        Subscription::create([
            'company_id' => $company->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
        ]);

        $this->assertFalse($company->hasReachedTelegramLimit());

        TelegramBot::create([
            'company_id' => $company->id,
            'name' => 'Bot 1',
            'token' => 'token1',
        ]);

        $this->assertFalse($company->hasReachedTelegramLimit());

        TelegramBot::create([
            'company_id' => $company->id,
            'name' => 'Bot 2',
            'token' => 'token2',
        ]);

        $this->assertTrue($company->hasReachedTelegramLimit());
    }

    /** @test */
    public function it_allows_unlimited_telegram_bots_when_limit_is_minus_one()
    {
        $company = Company::create([
            'name' => 'Test Company Unlimited',
            'email' => 'test_unlimited@example.com',
            'domain_slug' => 'test-company-unlimited',
            'status' => 'active',
        ]);
        $plan = Plan::create([
            'name' => 'Unlimited Plan',
            'price' => 50,
            'max_telegram_bots' => -1,
        ]);

        Subscription::create([
            'company_id' => $company->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
        ]);

        for ($i = 0; $i < 10; $i++) {
            TelegramBot::create([
                'company_id' => $company->id,
                'name' => "Bot $i",
                'token' => "token$i",
            ]);
        }

        $this->assertFalse($company->hasReachedTelegramLimit());
    }
}

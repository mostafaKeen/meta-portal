<?php

namespace Modules\Company\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_super_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('company::super_admin.dashboard');
    }

    public function test_company_admin_can_access_company_admin_dashboard(): void
    {
        $company = \Modules\Company\Models\Company::factory()->create();
        $user = User::factory()->create([
            'role' => 'company_admin',
            'company_id' => $company->id
        ]);

        $response = $this->actingAs($user)->get(route('company.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('company::company_admin.dashboard');
    }

    public function test_agent_can_access_agent_dashboard(): void
    {
        $company = \Modules\Company\Models\Company::factory()->create();
        $user = User::factory()->create([
            'role' => 'agent',
            'company_id' => $company->id
        ]);

        $response = $this->actingAs($user)->get(route('agent.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('company::agent.dashboard');
    }

    public function test_unauthorized_users_cannot_access_other_dashboards(): void
    {
        $company = \Modules\Company\Models\Company::factory()->create();
        $agent = User::factory()->create([
            'role' => 'agent',
            'company_id' => $company->id
        ]);

        // Agent trying to access super admin dashboard
        $response = $this->actingAs($agent)->get(route('admin.dashboard'));
        $response->assertStatus(403);

        // Agent trying to access company admin dashboard
        $response = $this->actingAs($agent)->get(route('company.dashboard'));
        $response->assertStatus(403);
    }
}

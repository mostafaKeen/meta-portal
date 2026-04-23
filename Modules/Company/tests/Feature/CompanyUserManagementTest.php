<?php

namespace Modules\Company\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Company\Models\Company;
use Modules\Company\Models\WhatsappNumber;
use Tests\TestCase;

class CompanyUserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->company = Company::factory()->create();
        $this->admin = User::factory()->create([
            'company_id' => $this->company->id,
            'role' => 'company_admin'
        ]);
    }

    public function test_company_admin_can_list_users(): void
    {
        User::factory()->count(2)->create(['company_id' => $this->company->id]);
        
        $response = $this->actingAs($this->admin)->get(route('company.users.index'));

        $response->assertStatus(200);
        $response->assertViewHas('users');
        $this->assertEquals(3, $response->viewData('users')->total()); // 2 + admin
    }

    public function test_company_admin_can_create_agent(): void
    {
        $userData = [
            'name' => 'New Agent',
            'email' => 'agent@company.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'agent',
        ];

        $response = $this->actingAs($this->admin)->post(route('company.users.store'), $userData);

        $response->assertRedirect(route('company.users.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'New Agent',
            'email' => 'agent@company.com',
            'company_id' => $this->company->id,
            'role' => 'agent'
        ]);
    }

    public function test_company_admin_can_update_user_and_assign_whatsapp(): void
    {
        $agent = User::factory()->create([
            'company_id' => $this->company->id,
            'role' => 'agent'
        ]);
        $number = WhatsappNumber::factory()->create(['company_id' => $this->company->id]);

        $updateData = [
            'name' => 'Updated Agent Name',
            'email' => 'updated-agent@company.com',
            'role' => 'agent',
            'whatsapp_numbers' => [
                $number->id => [
                    'assigned' => '1',
                    'access_type' => 'view'
                ]
            ]
        ];

        $response = $this->actingAs($this->admin)->put(route('company.users.update', $agent), $updateData);

        $response->assertRedirect(route('company.users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $agent->id,
            'name' => 'Updated Agent Name'
        ]);
        
        $this->assertDatabaseHas('whatsapp_number_user', [
            'user_id' => $agent->id,
            'whatsapp_number_id' => $number->id,
            'access_type' => 'view'
        ]);
    }

    public function test_company_admin_cannot_delete_themselves(): void
    {
        $response = $this->actingAs($this->admin)->delete(route('company.users.destroy', $this->admin));

        $response->assertRedirect(route('company.users.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    public function test_company_admin_can_delete_agent(): void
    {
        $agent = User::factory()->create([
            'company_id' => $this->company->id,
            'role' => 'agent'
        ]);

        $response = $this->actingAs($this->admin)->delete(route('company.users.destroy', $agent));

        $response->assertRedirect(route('company.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $agent->id]);
    }
}

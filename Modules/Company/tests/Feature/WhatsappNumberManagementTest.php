<?php

namespace Modules\Company\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Company\Models\Company;
use Modules\Company\Models\WhatsappNumber;
use Tests\TestCase;

class WhatsappNumberManagementTest extends TestCase
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

    public function test_company_admin_can_list_whatsapp_numbers(): void
    {
        WhatsappNumber::factory()->count(2)->create(['company_id' => $this->company->id]);
        
        $response = $this->actingAs($this->admin)->get(route('company.whatsapp.index'));

        $response->assertStatus(200);
        $response->assertViewHas('numbers');
        $this->assertCount(2, $response->viewData('numbers'));
    }

    public function test_company_admin_can_add_qr_whatsapp_number(): void
    {
        $numberData = [
            'type' => 'qr',
            'phone_number' => '+1234567890',
            'session_name' => 'Test Session',
            'status' => 'active',
        ];

        $response = $this->actingAs($this->admin)->post(route('company.whatsapp.store'), $numberData);

        $response->assertRedirect(route('company.whatsapp.index'));
        $this->assertDatabaseHas('whatsapp_numbers', [
            'company_id' => $this->company->id,
            'type' => 'qr',
            'phone_number' => '+1234567890'
        ]);
    }

    public function test_company_admin_can_add_api_whatsapp_number(): void
    {
        $numberData = [
            'type' => 'api',
            'phone_number' => '+0987654321',
            'app_name' => 'My WA App',
            'app_id' => 'app-123',
            'app_token' => 'token-456',
            'status' => 'active',
        ];

        $response = $this->actingAs($this->admin)->post(route('company.whatsapp.store'), $numberData);

        $response->assertRedirect(route('company.whatsapp.index'));
        $this->assertDatabaseHas('whatsapp_numbers', [
            'type' => 'api',
            'app_id' => 'app-123'
        ]);
    }

    public function test_company_admin_cannot_edit_other_company_number(): void
    {
        $otherCompany = Company::factory()->create();
        $otherNumber = WhatsappNumber::factory()->create(['company_id' => $otherCompany->id]);

        $response = $this->actingAs($this->admin)->get(route('company.whatsapp.edit', $otherNumber));

        $response->assertStatus(403);
    }

    public function test_company_admin_can_update_whatsapp_number(): void
    {
        $number = WhatsappNumber::factory()->create(['company_id' => $this->company->id]);

        $updateData = [
            'type' => 'qr',
            'phone_number' => '+111222333',
            'session_name' => 'Updated Session',
            'status' => 'active',
        ];

        $response = $this->actingAs($this->admin)->put(route('company.whatsapp.update', $number), $updateData);

        $response->assertRedirect(route('company.whatsapp.index'));
        $this->assertDatabaseHas('whatsapp_numbers', [
            'id' => $number->id,
            'phone_number' => '+111222333'
        ]);
    }

    public function test_company_admin_can_delete_whatsapp_number(): void
    {
        $number = WhatsappNumber::factory()->create(['company_id' => $this->company->id]);

        $response = $this->actingAs($this->admin)->delete(route('company.whatsapp.destroy', $number));

        $response->assertRedirect(route('company.whatsapp.index'));
        $this->assertDatabaseMissing('whatsapp_numbers', ['id' => $number->id]);
    }
}

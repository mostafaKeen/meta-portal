<?php

namespace Modules\Company\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Company\Models\Company;
use Modules\Company\Models\WhatsappNumber;
use Tests\TestCase;

class AgentWhatsappTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->company = Company::factory()->create();
        $this->agent = User::factory()->create([
            'company_id' => $this->company->id,
            'role' => 'agent'
        ]);
    }

    public function test_agent_can_view_assigned_whatsapp_numbers(): void
    {
        $number1 = WhatsappNumber::factory()->create(['company_id' => $this->company->id]);
        $number2 = WhatsappNumber::factory()->create(['company_id' => $this->company->id]);
        
        $this->agent->whatsappNumbers()->attach($number1, ['access_type' => 'view']);

        $response = $this->actingAs($this->agent)->get(route('agent.whatsapp.index'));

        $response->assertStatus(200);
        $response->assertViewHas('assignedNumbers');
        
        $assigned = $response->viewData('assignedNumbers');
        $this->assertCount(1, $assigned);
        $this->assertTrue($assigned->contains($number1));
        $this->assertFalse($assigned->contains($number2));
    }

    public function test_non_agent_cannot_access_agent_whatsapp_index(): void
    {
        $admin = User::factory()->create([
            'company_id' => $this->company->id,
            'role' => 'company_admin'
        ]);

        $response = $this->actingAs($admin)->get(route('agent.whatsapp.index'));
        
        $response->assertStatus(403);
    }
}

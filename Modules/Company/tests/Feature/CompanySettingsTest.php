<?php

namespace Modules\Company\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Company\Models\Company;
use Tests\TestCase;

class CompanySettingsTest extends TestCase
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

    public function test_company_admin_can_view_settings(): void
    {
        $response = $this->actingAs($this->admin)->get(route('company.settings.edit'));

        $response->assertStatus(200);
        $response->assertViewIs('company::settings.edit');
        $response->assertViewHas('company', $this->company);
    }

    public function test_company_admin_can_update_settings(): void
    {
        Storage::fake('public');

        $updateData = [
            'name' => 'Updated Brand Name',
            'email' => 'support@brand.com',
            'phone' => '123456789',
            'address' => '123 Street',
            'website' => 'https://brand.com',

            'logo' => UploadedFile::fake()->image('new-logo.png'),
        ];

        $response = $this->actingAs($this->admin)->put(route('company.settings.update'), $updateData);

        $response->assertRedirect(route('company.settings.edit'));
        $this->assertDatabaseHas('companies', [
            'id' => $this->company->id,
            'name' => 'Updated Brand Name',
            'email' => 'support@brand.com'
        ]);

        $this->company->refresh();
        $this->assertNotNull($this->company->logo);
        Storage::disk('public')->assertExists($this->company->logo);
    }

    public function test_non_admin_cannot_access_settings(): void
    {
        $agent = User::factory()->create([
            'company_id' => $this->company->id,
            'role' => 'agent'
        ]);

        $response = $this->actingAs($agent)->get(route('company.settings.edit'));
        $response->assertStatus(403);
    }
}

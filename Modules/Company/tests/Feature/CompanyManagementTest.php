<?php

namespace Modules\Company\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Company\Models\Company;
use Tests\TestCase;

class CompanyManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->superAdmin = User::factory()->create(['role' => 'super_admin']);
    }

    public function test_super_admin_can_list_companies(): void
    {
        Company::factory()->count(3)->create();

        $response = $this->actingAs($this->superAdmin)->get(route('company.index'));

        $response->assertStatus(200);
        $response->assertViewHas('companies');
    }

    public function test_super_admin_can_create_company_with_admin(): void
    {
        Storage::fake('public');

        $companyData = [
            'name' => 'New Company',
            'domain_slug' => 'new-company',
            'email' => 'contact@newcompany.com',
            'status' => 'active',
            'admin_name' => 'Company Admin',
            'admin_email' => 'admin@newcompany.com',
            'admin_password' => 'password123',
            'admin_password_confirmation' => 'password123',
            'logo' => UploadedFile::fake()->image('logo.png'),
        ];

        $response = $this->actingAs($this->superAdmin)->post(route('company.store'), $companyData);

        $response->assertRedirect(route('company.index'));
        $this->assertDatabaseHas('companies', ['name' => 'New Company']);
        $this->assertDatabaseHas('users', [
            'email' => 'admin@newcompany.com',
            'role' => 'company_admin'
        ]);
        
        $company = Company::where('name', 'New Company')->first();
        $this->assertNotNull($company->logo);
        Storage::disk('public')->assertExists($company->logo);
    }

    public function test_super_admin_can_update_company(): void
    {
        $company = Company::factory()->create();
        $admin = User::factory()->create([
            'company_id' => $company->id,
            'role' => 'company_admin'
        ]);

        $updateData = [
            'name' => 'Updated Company Name',
            'domain_slug' => $company->domain_slug,
            'email' => 'updated@company.com',
            'status' => 'inactive',
            'admin_name' => 'Updated Admin Name',
            'admin_email' => 'updated-admin@company.com',
        ];

        $response = $this->actingAs($this->superAdmin)->put(route('company.update', $company), $updateData);

        $response->assertRedirect(route('company.index'));
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'Updated Company Name',
            'status' => 'inactive'
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'name' => 'Updated Admin Name',
            'email' => 'updated-admin@company.com'
        ]);
    }

    public function test_super_admin_can_delete_company(): void
    {
        $company = Company::factory()->create();

        $response = $this->actingAs($this->superAdmin)->delete(route('company.destroy', $company));

        $response->assertRedirect(route('company.index'));
        $this->assertSoftDeleted('companies', ['id' => $company->id]);
    }
}

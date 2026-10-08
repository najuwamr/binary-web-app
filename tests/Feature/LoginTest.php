<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_login_is_only_available_at_the_keyed_path(): void
    {
        $key = config('fortify.prefix');

        $this->assertNotSame('', $key);

        $this->get('/login')->assertNotFound();
        $this->post('/login', ['email' => 'admin@example.com', 'password' => 'password'])->assertNotFound();
        $this->get('/wrong-key/login')->assertNotFound();

        $this->get(route('login'))->assertOk()->assertSee('Masuk Admin');
    }

    public function test_a_user_can_log_in_with_valid_credentials(): void
    {
        $this->migrateTestDatabase();

        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'correct-password',
        ]);

        $this->post(route('login.store'), [
            'email' => 'admin@example.com',
            'password' => 'correct-password',
        ])->assertRedirect('/admin/dashboard');

        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_credentials_do_not_authenticate_the_user(): void
    {
        $this->migrateTestDatabase();

        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'correct-password',
        ]);

        $this->post(route('login.store'), [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ])->assertRedirect();

        $this->assertGuest();
    }

    public function test_admin_pages_require_authentication(): void
    {
        foreach ([
            'admin.dashboard',
            'admin.berita.index',
            'admin.buletin.index',
            'admin.arsip.index',
        ] as $route) {
            $this->get(route($route))
                ->assertRedirect(route('login'));
        }
    }

    public function test_authenticated_users_can_access_admin_pages(): void
    {
        $this->migrateTestDatabase();

        $this->actingAs(User::factory()->create());

        $this->get(route('admin.dashboard'))->assertOk()->assertSee('Dashboard Admin');
        $this->get(route('admin.berita.index'))->assertOk()->assertSee('Manajemen Berita');
        $this->get(route('admin.buletin.index'))->assertOk()->assertSee('Manajemen Buletin');
        $this->get(route('admin.arsip.index'))->assertOk()->assertSee('Belum ada arsip');
    }

    private function migrateTestDatabase(): void
    {
        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('The pdo_sqlite PHP extension is required for database-backed login tests.');
        }

        $this->artisan('migrate:fresh')->assertExitCode(0);
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRegistrationTest  extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_delete_itself()
    {
        $admin = User::factory()->create(['role' => 'admin', 'password' => bcrypt('password')]);

        $this->actingAs($admin);

        $response = $this->delete("/profile/destroy/{$admin->id}", [
            'password' => 'password',
        ]);

        $response->assertRedirect('/'); // después de borrarse redirige
        $this->assertGuest(); // ya no está autenticado
        $this->assertDatabaseMissing('users', ['id' => $admin->id]);
    }

    /** @test */
    public function admin_cannot_delete_other_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $otherAdmin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin);

        $response = $this->delete("/profile/destroy/{$otherAdmin->id}", [
            'password' => 'password',
        ]);

        $response->assertStatus(403); // prohibido
        $this->assertDatabaseHas('users', ['id' => $otherAdmin->id]); // sigue existiendo
    }

    /** @test */
    public function admin_can_delete_normal_users()
    {
        $admin = User::factory()->create(['role' => 'admin', 'password' => bcrypt('password')]);
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($admin);

        $response = $this->delete("/profile/destroy/{$user->id}", [
            'password' => 'password',
        ]);

        $response->assertRedirect('/users'); // ajusta según tu controlador
        $this->assertDatabaseMissing('users', ['id' => $user->id]); // eliminado
    }
}

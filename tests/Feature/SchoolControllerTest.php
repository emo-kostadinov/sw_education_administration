<?php

namespace Tests\Feature;

use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchoolControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_schools()
    {
        School::factory(3)->create();

        $response = $this->getJson('/api/schools');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_create_school()
    {
        $data = ['name' => 'TUES', 'address' => 'Mladost', 'principal_name' => 'Stela'];

        $response = $this->postJson('/api/schools', $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('schools', $data);
    }

    public function test_can_show_school()
    {
        $school = School::factory()->create();

        $response = $this->getJson("/api/schools/{$school->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => $school->name]);
    }

    public function test_can_update_school()
    {
        $school = School::factory()->create();
        $data = ['name' => 'TU', 'address' => 'Studentski', 'principal_name' => 'Veselka'];

        $response = $this->putJson("/api/schools/{$school->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('schools', $data);
    }

    public function test_can_delete_school()
    {
        $school = School::factory()->create();

        $response = $this->deleteJson("/api/schools/{$school->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('schools', ['id' => $school->id]);
    }
}

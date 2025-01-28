<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClassroomControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_classrooms()
    {
        $school = School::factory()->create();
        Classroom::factory(3)->create(['school_id' => $school->id]);

        $response = $this->getJson("/api/schools/{$school->id}/classrooms");

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_create_classroom()
    {
        $school = School::factory()->create();
        $data = ['room_number' => '402', 'grade' => '11'];

        $response = $this->postJson("/api/schools/{$school->id}/classrooms", $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('classrooms', $data);
    }

    public function test_can_show_classroom()
    {
        $school = School::factory()->create();
        $classroom = Classroom::factory()->create(['school_id' => $school->id]);

        $response = $this->getJson("/api/schools/{$school->id}/classrooms/{$classroom->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['room_number' => $classroom->room_number]);
    }

    public function test_can_update_classroom()
    {
        $school = School::factory()->create();
        $classroom = Classroom::factory()->create(['school_id' => $school->id]);
        $data = ['room_number' => '403', 'grade' => '12'];

        $response = $this->putJson("/api/schools/{$school->id}/classrooms/{$classroom->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('classrooms', $data);
    }

    public function test_can_delete_classroom()
    {
        $school = School::factory()->create();
        $classroom = Classroom::factory()->create(['school_id' => $school->id]);

        $response = $this->deleteJson("/api/schools/{$school->id}/classrooms/{$classroom->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('classrooms', ['id' => $classroom->id]);
    }
}

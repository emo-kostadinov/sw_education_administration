<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\School;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_students()
    {
        $school = School::factory()->create();
        $classroom = Classroom::factory()->create(['school_id' => $school->id]);
        Student::factory(3)->create(['classroom_id' => $classroom->id]);

        $response = $this->getJson("/api/schools/{$school->id}/classrooms/{$classroom->id}/students");

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_create_student()
    {
        $school = School::factory()->create();
        $classroom = Classroom::factory()->create(['school_id' => $school->id]);
        $data = ['name' => 'Emil', 'age' => 18];

        $response = $this->postJson("/api/schools/{$school->id}/classrooms/{$classroom->id}/students", $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('students', $data);
    }

    public function test_can_show_student()
    {
        $school = School::factory()->create();
        $classroom = Classroom::factory()->create(['school_id' => $school->id]);
        $student = Student::factory()->create(['classroom_id' => $classroom->id]);

        $response = $this->getJson("/api/schools/{$school->id}/classrooms/{$classroom->id}/students/{$student->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => $student->name]);
    }

    public function test_can_update_student()
    {
        $school = School::factory()->create();
        $classroom = Classroom::factory()->create(['school_id' => $school->id]);
        $student = Student::factory()->create(['classroom_id' => $classroom->id]);
        $data = ['name' => 'Ivan', 'age' => '19'];

        $response = $this->putJson("/api/schools/{$school->id}/classrooms/{$classroom->id}/students/{$student->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('students', $data);
    }

    public function test_can_delete_student()
    {
        $school = School::factory()->create();
        $classroom = Classroom::factory()->create(['school_id' => $school->id]);
        $student = Student::factory()->create(['classroom_id' => $classroom->id]);

        $response = $this->deleteJson("/api/schools/{$school->id}/classrooms/{$classroom->id}/students/{$student->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }
}
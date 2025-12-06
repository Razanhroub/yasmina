<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\Classroom;
use App\Models\Student;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //for using the custom seeder i made 
        $this->call(RolesSeeder::class);

    // Create Admin user as seeder
        $adminRole = Role::where('name', 'admin')->first();

        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'razanse.b.alhroub@gmail.com',
            'password' => Hash::make('Raz_2001'),
        ])->assignRole('admin'); 
         // Create Teacher user
        $teacherRole = Role::where('name', 'teacher')->first();
        $teacher = User::factory()->create([
            'name' => 'John Teacher',
            'email' => 'teachers@example.com',
            'password' => Hash::make('password'),
        ])->assignRole('teacher');

        // Create a classroom for the teacher
        $classroom = Classroom::create([
            'name' => 'Class 101',
            'description' => 'Sample classroom for testing',
            'teacher_id' => $teacher->id,
        ]);

        // Create 2 students
        $studentRole = Role::where('name', 'student')->first();

        $student1 = User::factory()->create([
            'name' => 'Student One',
            'email' => 'studenst1@example.com',
            'password' => Hash::make('password'),

        ])->assignRole('student');

        $student2 = User::factory()->create([
            'name' => 'Student Two',
            'email' => 'students2@example.com',
            'password' => Hash::make('password'),
        ])->assignRole('student');

        // Link students to the classroom
        Student::create([
            'student_id' => $student1->id,
            'class_id' => $classroom->id,
            'birth_of_date' => '2010-01-01',
            'country' => 'Jordan',
        ]);

        Student::create([
            'student_id' => $student2->id,
            'class_id' => $classroom->id,
            'birth_of_date' => '2011-02-02',
            'country' => 'Jordan',
        ]);
    }
        
}

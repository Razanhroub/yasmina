<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            //the student id is in table users 
            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete(); 
            

            $table->foreignId('class_id')
                ->nullable()
                ->constrained('classrooms')
                ->cascadeOnDelete();

            $table->date('birth_of_date')->nullable();
            $table->string('country')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

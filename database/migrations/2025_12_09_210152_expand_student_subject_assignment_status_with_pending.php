<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE student_subject_assignments 
        MODIFY status ENUM('Enrolled', 'Dropped', 'Completed', 'Pending Enrollment') 
        DEFAULT 'Enrolled'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE student_subject_assignments 
        MODIFY status ENUM('Enrolled', 'Dropped', 'Completed') 
        DEFAULT 'Enrolled'");
    }
};

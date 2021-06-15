<?php

use App\Models\Course;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->bigInteger('category_id')->unsigned()->index();
            $table->bigInteger('instructor_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('updated_by')->unsigned();
            $table->string('status')->default(Course::DRAFT);
            $table->softDeletes();
            $table->timestamps();

            // $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            // $table->foreign('instructor_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}

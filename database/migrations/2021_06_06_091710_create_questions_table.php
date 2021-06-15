<?php

use App\Models\Question;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('question');
            $table->bigInteger('type_id')->unsigned()->index();
            $table->bigInteger('quiz_id')->unsigned()->index();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('updated_by')->unsigned();
            $table->string('status')->default(Question::DRAFT);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url',1000);
            $table->unsignedSmallInteger('status')->default(0);
/*          1 = scrape_success
            2 = scrape_failed
            3 = summarization_success
            4 = summarization_fail
            5 = published
*/
            $table->string('title',500)->nullable();
            $table->smallInteger('grade')->nullable();
            $table->string('subject')->nullable();
            $table->mediumText('scraped_text')->nullable();
            $table->mediumText('summarized_text')->nullable();
            $table->string('added_by')->nullable();
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
        Schema::dropIfExists('notes');
    }
}

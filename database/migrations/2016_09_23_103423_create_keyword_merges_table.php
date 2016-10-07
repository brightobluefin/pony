<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeywordMergesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keyword_merges', function (Blueprint $table) {
            $table->increments('id');
            $table->string('adwords_id');
            $table->string('gemini_id');
            $table->string('advertiser_id');
            $table->string('adgroup_gemini_id');
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
        Schema::dropIfExists('keyword_merges');
    }
}

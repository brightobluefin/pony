<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdGroupMergesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_group_merges', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('adwords_id');
            $table->integer('gemini_id');
            $table->integer('advertiser_id');
            $table->integer('campaign_gemini_id');
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
        Schema::dropIfExists('ad_group_merges');
    }
}

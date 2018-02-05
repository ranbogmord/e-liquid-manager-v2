<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLiquidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liquids', function (Blueprint $table) {
            $table->increments('id');
            $table->string("old_id")->nullable();

            $table->string("name");
            $table->float('base_nic_strength');
            $table->float("target_pg_percentage");
            $table->float("target_vg_percentage");
            $table->float("target_nic_strength");

            $table->integer("author_id")->nullable();
            $table->integer("next_version_id")->nullable();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('flavour_liquid', function (Blueprint $table) {
            $table->integer('flavour_id');
            $table->integer('liquid_id');
            $table->float('percent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('liquids');
        Schema::dropIfExists('flavour_liquid');
    }
}

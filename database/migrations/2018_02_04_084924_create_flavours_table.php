<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlavoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flavours', function (Blueprint $table) {
            $table->increments('id');
            $table->string("old_id")->nullable();

            $table->string("name");
            $table->boolean("is_vg");
            $table->float("base_percent")->default(0);

            $table->integer("vendor_id")->nullable();
            $table->integer("author_id")->nullable();

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
        Schema::dropIfExists('flavours');
    }
}

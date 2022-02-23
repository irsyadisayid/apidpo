<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDaftarpoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daftarpo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_user');
            $table->string('nama');
            $table->string('ttl');
            $table->string('jekel');
            $table->string('tb');
            $table->string('rambut');
            $table->string('kulit');
            $table->string('mata');
            $table->string('cirik');
            $table->string('tglhilang');
            $table->string('infot');
            $table->string('photo');
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
        Schema::dropIfExists('daftarpo');
    }
}

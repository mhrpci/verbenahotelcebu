<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ht_taxes', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->float('percentage', 8, 6)->nullable();
            $table->integer('priority')->nullable();
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::table('ht_rooms', function (Blueprint $table) {
            $table->integer('tax_id')->unsigned()->nullable();
        });

        Schema::table('ht_bookings', function (Blueprint $table) {
            $table->decimal('tax_amount', 15, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ht_bookings', function (Blueprint $table) {
            $table->dropColumn('tax_amount');
        });

        Schema::table('ht_rooms', function (Blueprint $table) {
            $table->dropColumn('tax_id');
        });

        Schema::dropIfExists('ht_taxes');
    }
}

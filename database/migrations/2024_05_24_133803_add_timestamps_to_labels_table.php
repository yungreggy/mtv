<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsToLabelsTable extends Migration
{
    public function up()
    {
        Schema::table('labels', function (Blueprint $table) {
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('labels', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('aircraft', function (Blueprint $table) {
            $table->unsignedInteger('airline_id')->nullable()->after('subfleet_id');
        });
    }

    public function down()
    {
        Schema::table('aircraft', function (Blueprint $table) {
            $table->dropColumn('airline_id');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddEndDateToTontinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tontines', function (Blueprint $table) {
            $table->date('end_date')->nullable()->after('start_date');
        });

        // Mettre à jour les tontines existantes avec une date de fin (45 jours après le début)
        DB::table('tontines')->whereNull('end_date')->update([
            'end_date' => DB::raw('DATE_ADD(start_date, INTERVAL 45 DAY)')
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tontines', function (Blueprint $table) {
            $table->dropColumn('end_date');
        });
    }
}

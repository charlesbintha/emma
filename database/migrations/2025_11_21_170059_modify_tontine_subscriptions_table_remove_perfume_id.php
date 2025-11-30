<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTontineSubscriptionsTableRemovePerfumeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tontine_subscriptions', function (Blueprint $table) {
            $table->dropForeign(['perfume_id']);
            $table->dropColumn('perfume_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tontine_subscriptions', function (Blueprint $table) {
            $table->foreignId('perfume_id')->after('user_id')->constrained()->onDelete('restrict');
        });
    }
}

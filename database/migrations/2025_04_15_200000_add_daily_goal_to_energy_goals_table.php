<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDailyGoalToEnergyGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('energy_goals') && !Schema::hasColumn('energy_goals', 'daily_goal')) {
            Schema::table('energy_goals', function (Blueprint $table) {
                $table->float('daily_goal')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('energy_goals') && Schema::hasColumn('energy_goals', 'daily_goal')) {
            Schema::table('energy_goals', function (Blueprint $table) {
                $table->dropColumn('daily_goal');
            });
        }
    }
}

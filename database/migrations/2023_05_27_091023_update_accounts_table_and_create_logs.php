<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migration.
     *
     * @return void
     */
    public function up()
    {

        $currentMonth = date('m');
        $currentYear = date('Y');

        $accounts = DB::table('accounts')->whereNotNull('balance')->get();

        foreach ($accounts as $account) {
            DB::table('account_logs')->insert([
                'account_id' => $account->id,
                'balance' => $account->balance,
                'month' => "$currentYear-$currentMonth-01",
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('accounts')->where('id', $account->id)->update([
                'last_month_balance' => $account->balance,
            ]);
        }
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('last_month_balance');
        });

        DB::table('account_logs')->truncate();
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $events = DB::table('events')->get();

        foreach ($events as $event) {
            $dates = json_decode($event->dates, true);
            if (is_array($dates)) {
                foreach ($dates as $date) {
                    DB::table('event_dates')->insert([
                        'event_id' => $event->id,
                        'date' => $date,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('dates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->json('dates')->nullable();
        });

        $events = DB::table('events')->get();
        foreach ($events as $event) {
            $dates = DB::table('event_dates')->where('event_id', $event->id)->pluck('date')->toArray();
            DB::table('events')->where('id', $event->id)->update(['dates' => json_encode($dates)]);
        }
    }
};

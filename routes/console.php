<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('kamis:scrape')->dailyAt('06:00');
Schedule::command('notifications:weekly-summary')->weeklyOn(1, '08:00');

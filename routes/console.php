<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('kamis:scrape')->dailyAt('06:00');

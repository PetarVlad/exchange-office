<?php

use App\Console\Commands\CurrencyUpdateCommand;

Schedule::command(CurrencyUpdateCommand::class)->daily();

<?php

namespace SMSkin\LaravelSupport;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class BaseJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    abstract public function handle(): void;
}

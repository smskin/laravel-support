<?php

namespace SMSkin\LaravelSupport\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Horizon\Contracts\JobRepository;

class ClearHorizonFailedJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'horizon:clear-failed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear horizon failed jobs';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        config(['horizon.trim.failed' => 1]);

        $jobRepository = resolve(JobRepository::class);
        $jobRepository->trimFailedJobs();
        return 0;
    }
}

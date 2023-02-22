<?php

namespace SMSkin\LaravelSupport\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Laravel\Horizon\Contracts\JobRepository;
use Laravel\Horizon\Jobs\RetryFailedJob;

class RetryHorizonFailedJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'horizon:retry-failed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retry horizon failed jobs';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $repository = app(JobRepository::class);
        $total = $repository->totalFailed();
        $limit = 50;
        $pagesCount = ceil($total / $limit);
        $this->info('Total elements: ' . $total);
        $this->info('Elements on page: ' . $limit);
        $this->info('Pages count: ' . $pagesCount);
        for ($i = 1; $i <= $pagesCount; $i++) {
            $offset = $i * $limit - $limit;
            $failedJobs = $repository->getFailed($offset);
            foreach ($failedJobs as $failedJob) {
                dispatch(new RetryFailedJob($failedJob->id));
                DB::table('failed_jobs')->where('uuid', $failedJob->id)->delete();
                $this->info('Job #' . $failedJob->id . ' dispatched');
            }
        }
        return 0;
    }
}

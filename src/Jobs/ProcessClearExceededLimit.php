<?php

namespace Elogquent\Jobs;

use Elogquent\Contracts\ElogquentRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessClearExceededLimit implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private string $modelClass,
        private int $modelKey,
        private int $limit,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(ElogquentRepositoryInterface $repository): void
    {
        $repository->removeExceededLimit(
            modelClass: $this->modelClass,
            modelKey: $this->modelKey,
            limit: $this->limit,
        );

    }
}

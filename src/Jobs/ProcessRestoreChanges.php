<?php

namespace Elogquent\Jobs;

use Elogquent\Contracts\ElogquentRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Queue\Queueable;

class ProcessRestoreChanges implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Model $model,
        private array $changes,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(ElogquentRepositoryInterface $repository): void
    {
        $repository->restoreChanges(
            model: $this->model,
            changes: $this->changes,
        );
    }
}

<?php

namespace Elogquent\Jobs;

use Elogquent\Contracts\ElogquentRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessRemoveDuplicates implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private string $modelClass,
        private string $modelKey,
        private array $changes,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(ElogquentRepositoryInterface $repository): void
    {
        $repository->removeChanges(
            modelClass: $this->modelClass,
            modelKey: $this->modelKey,
            changes: $this->changes,
        );
    }
}

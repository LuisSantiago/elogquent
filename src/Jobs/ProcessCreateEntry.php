<?php

namespace Elogquent\Jobs;

use Elogquent\Contracts\ElogquentRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessCreateEntry implements ShouldQueue
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
        $userId = config('elogquent.store_user_id') ? auth()->user()?->getAuthIdentifier() : null;

        $repository->create(
            modelClass: $this->modelClass,
            modelKey: $this->modelKey,
            changes: $this->changes,
            userId: $userId
        );
    }
}

<?php

namespace App\Console\Commands;

use App\Services\RemoteCatalog\RemoteCatalogSyncer;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'storefront:sync-remote')]
class SyncRemoteCatalog extends Command
{
    protected $signature = 'storefront:sync-remote';

    protected $description = 'Synchronise products from the remote catalog JSON feed into the local storefront.';

    public function handle(RemoteCatalogSyncer $syncer): int
    {
        $results = $syncer->sync(function (string $message) {
            $this->line($message);
        });

        $this->info(sprintf(
            'Sync complete: %s created, %s updated, %s skipped.',
            $results['created'] ?? 0,
            $results['updated'] ?? 0,
            $results['skipped'] ?? 0
        ));

        if (! empty($results['errors'])) {
            $this->warn('Some products failed to sync:');
            foreach ($results['errors'] as $error) {
                $this->warn(sprintf('- %s: %s', $error['product'] ?? 'unknown', $error['message']));
            }
        }

        return self::SUCCESS;
    }
}

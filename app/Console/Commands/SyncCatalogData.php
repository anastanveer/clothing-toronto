<?php

namespace App\Console\Commands;

use App\Services\CatalogSyncService;
use Illuminate\Console\Command;

class SyncCatalogData extends Command
{
    protected $signature = 'catalog:sync {--brand=}';
    protected $description = 'Sync products and collections from catalog brand JSON feeds';

    public function handle(CatalogSyncService $service): int
    {
        $this->info('Starting catalog sync...');

        $brand = $this->option('brand');
        $summary = $service->sync($brand ? (string) $brand : null);

        $this->table(
            ['Collections', 'Products', 'Variants', 'Images', 'Collection Links'],
            [[
                $summary['collections'] ?? 0,
                $summary['products'] ?? 0,
                $summary['variants'] ?? 0,
                $summary['images'] ?? 0,
                $summary['collection_links'] ?? 0,
            ]]
        );

        $this->info('Catalog sync completed.');

        return self::SUCCESS;
    }
}

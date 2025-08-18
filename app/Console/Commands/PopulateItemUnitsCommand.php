<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Item;
use App\Models\ItemUnit;

class PopulateItemUnitsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'items:populate-units';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate item_units table based on items quantity';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $items = Item::all();

        foreach ($items as $item) {
            $existingUnitsCount = $item->units()->count();
            $unitsToCreate = $item->quantity - $existingUnitsCount;

            if ($unitsToCreate > 0) {
                for ($i = $existingUnitsCount + 1; $i <= $item->quantity; $i++) {
                    ItemUnit::create([
                        'item_id' => $item->id,
                        'unit_number' => $i,
                        'last_checked_at' => null,
                    ]);
                }
                $this->info("Created {$unitsToCreate} units for item ID {$item->id}");
            } else {
                $this->info("Item ID {$item->id} already has sufficient units");
            }
        }

        $this->info('Item units population completed.');
    }
}

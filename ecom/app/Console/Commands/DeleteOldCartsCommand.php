<?php

namespace App\Console\Commands;

use App\Jobs\DeleteOldCarts;
use Illuminate\Console\Command;

class DeleteOldCartsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:delete-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old carts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DeleteOldCarts::dispatch();

        $this->info("Deleting old carts job dispatched.");
    }
}
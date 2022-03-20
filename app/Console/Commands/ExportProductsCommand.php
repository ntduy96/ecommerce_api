<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class ExportProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export products to CSV';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $headers = ['id', 'name', 'price', 'quantity', 'store_id', 'created_at', 'updated_at'];
        $data = Product::all($headers);

        // If csv folder does not exist, create it.
        if (!file_exists('csv/')) {
            mkdir('csv');
        }

        // Output csv
        $handle = fopen('csv/export.csv', 'w');
        fputcsv($handle, $headers, ',');
        foreach ($data as $row) {
            fputcsv($handle, $row->toArray(), ',');
        }
        fclose($handle);

        // DONE
        $this->info('🎉 Exported All Products to CSV successfully!');
    }
}

<?php

namespace App\Console\Commands;

use App\Services\OperatingTimeCSVImport;
use Illuminate\Console\Command;

/**
 * Class CSVImportServiceSave
 * @package App\Console\Commands
 */
class CSVImportServiceSave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv-import {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command CSV import service';

    /**
     * @var OperatingTimeCSVImport
     */
    private $operatingTimeCSVImport;

    /**
     * CSVImportServiceSave constructor.
     * @param OperatingTimeCSVImport $CSVImportService
     */
    public function __construct(OperatingTimeCSVImport $CSVImportService)
    {
        parent::__construct();
        $this->operatingTimeCSVImport = $CSVImportService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = $this->argument('path');
        try {
            $this->operatingTimeCSVImport->import($path);
        } catch (\Throwable $exception) {
        }
    }
}

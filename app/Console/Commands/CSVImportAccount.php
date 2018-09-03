<?php

namespace App\Console\Commands;

use App\Services\AccountCSVImport;
use Illuminate\Console\Command;

/**
 * Class CSVImportAccount
 * @package App\Console\Commands
 */
class CSVImportAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv-import-account {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import account from csv';

    /**
     * @var AccountCSVImport
     */
    private $accountCSVImport;

    /**
     * CSVImportAccount constructor.
     * @param AccountCSVImport $accountCSVImport
     */
    public function __construct(AccountCSVImport $accountCSVImport)
    {
        parent::__construct();
        $this->accountCSVImport = $accountCSVImport;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = $this->argument('path');
        $this->accountCSVImport->import($path);
    }
}

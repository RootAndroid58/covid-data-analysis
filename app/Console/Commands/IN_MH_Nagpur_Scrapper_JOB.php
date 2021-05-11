<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Helpers\ScrappingHelper;

class IN_MH_Nagpur_Scrapper_JOB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrap:INMHNagpur';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will scrap data from covidhelpnagpur.in';

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
        \Log::info("Job to scrap IN-MH-Nagpur Started Successfully!");

        $data = ScrappingHelper::Scrap_IN_MH_Nagpur();
        // dd(str_replace(['"""','\n'],['',''],$data));
        dd($data);

        // \Log::info("Job to scrap IN-MH-Nagpur completed!");
        $this->info('scrap:INMHNagpur IN-MH-Nagpur successfully!');
        return 0;
    }
}

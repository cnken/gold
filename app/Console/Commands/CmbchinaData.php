<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class CmbchinaData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cmbchina:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取黄金实时卖出和买入金额';

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
     * @return mixed
     */
    public function handle()
    {
        $cmbchinaUrl = 'https://ai.cmbchina.com/mb5web/metaldefault.html?DeviceType=E&Version=6.5.1&behavior_entryid=DEA000001';

        $startHour = 9;
        $endHour = 18;
        while (date('H') >= $startHour && date('H') < $endHour ) {
            $client = new Client();
            $contents = $client->get($cmbchinaUrl)->getBody()->getContents();

            $regex="/<div class=\"metprc-item-content.*?>(.*?)<\/div>/ism";
            preg_match_all($regex, $contents, $matches);var_dump($matches);
            DB::table('cmbchina')->insert([
                ['buy_price' => $matches[1][1], 'sell_price' => $matches[1][0], 'created_at' => now()],
            ]);
            sleep(60);
        }


    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\MsTwitterList;
use Exception;
use COM;

class AddList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:addlist {listId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Twitter公開リストIDをDBに登録 第一引数にリストIDを指定';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = date('Y-m-d H:i:s');
        $listId = $this->argument('listId');

        try {
            $list = MsTwitterList::firstOrCreate(['list_id' => $listId]);
            Log::info("[{$now}] リストを作成しました。list_id: {$list->list_id}");

        } catch (Exception $e) {
            Log::error("[{$now}] リストの作成に失敗しました。list_id: {$listId}");
            Log::error($e->getMessage());
            echo ($e->getMessage());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Usecases\Twitter\SavePublicListTweetsUseCase;
use Exception;
use Log;

class SaveAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:saveall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'リスト情報・ユーザー情報・ツイート情報をDBに保存';

    public $savePublicListTweetsUseCase;

    public function __construct(
        SavePublicListTweetsUseCase $savePublicListTweetsUseCase)
    {
        parent::__construct();
        $this->savePublicListTweetsUseCase = $savePublicListTweetsUseCase;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->savePublicListTweetsUseCase->invoke();

        } catch (Exception $e) {
            Log::error($e->getMessage());
            echo ($e->getMessage());
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}

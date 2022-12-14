<?php
namespace App\Bot\General;

use App\Models\TelegramId;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

abstract class ExtendedSystemCommand extends SystemCommand
{
    protected TelegramId|null $user;
    protected BotSession $session;
    protected $debug;

    public function __construct(Telegram $telegram, ?Update $update = null)
    {
        $this->debug = (bool)config('bot.telegram.debug_mode');

        parent::__construct($telegram, $update);

        $this->user = (new BotUser($this))->getRegisteredUser();
        $this->session = (new BotSession($this));

//        if($this->getMessage()->getCommand() == ltrim($this->usage,'/')){
//            $this->session->state = 'base';
//        }

    }

    protected function laraLog($caption, ...$data):void
    {
        Log::debug(var_export(['Caption: '.$caption, $data],true));
    }

    protected function robotLog($obj): void
    {
        if (!$this->debug) return;

        $message = (is_string($obj))?$obj:var_export($obj,true);

        $username = $this->getMessage()?->getFrom()?->getUsername();
        $username = $username ?: '---';

        $command = $this->session->getCurrCommand();
        $command = $command ?: '---';

        $text = "username: {$username}" . PHP_EOL;
        $text .= "command: {$command}" . PHP_EOL;
        $text .= $message;

        $data = [
            'chat_id' => config('bot.telegram.admin_id'),
            'text'    => $text,
//            'reply_markup' => Keyboard::remove(['selective' => true]),
        ];

        Request::sendMessage($data);
    }
}

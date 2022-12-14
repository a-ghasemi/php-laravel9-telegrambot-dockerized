<?php

namespace App\Bot\Commands;

use App\Bot\General\BotSession;
use App\Bot\General\ExtendedSystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class GenericmessageCommand extends ExtendedSystemCommand
{
    protected $name = 'genericmessage';
    protected $description = 'Handle generic message';
    protected $version = '1.0.0';

    /**
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();

        $command = $this->session->getCurrCommand();

        $this->robotLog("[generic_message]");

        return $command ? $this->telegram->executeCommand($command) : Request::emptyResponse();
    }
}

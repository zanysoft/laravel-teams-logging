<?php

namespace ZanySoft\LaravelTeamsLogging;

use Monolog\Logger as MonologLogger;

class Logger extends MonologLogger
{
    /**
     * @param  int  $level
     * @param  bool  $bubble
     */
    public function __construct($url, $level = MonologLogger::DEBUG, $bubble = true)
    {
        parent::__construct('teams-logger');

        $this->pushHandler(new LoggerHandler($url, $level, $bubble));
    }
}

<?php

namespace ZanySoft\LaravelTeamsLogging;

class LoggerChannel
{
    /**
     * @return Logger
     */
    public function __invoke(array $config)
    {
        return new Logger($config['url'], $config['level'] ?? \Monolog\Level::Debug);
    }
}

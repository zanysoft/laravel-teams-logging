<?php

namespace ZanySoft\LaravelTeamsLogging;

use Exception;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use Throwable;
use ZanySoft\LaravelTeamsLogging\Exception\FlattenException;

class LoggerHandler extends AbstractProcessingHandler
{
    /** @var string */
    private $url;

    /** @var string */
    private $style;

    /** @var string */
    private $name;

    /**
     * @param  int  $level
     * @param  string  $name
     * @param  bool  $bubble
     */
    public function __construct($url, $level = \Monolog\Level::Debug, $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->url = $url;
        $this->style = config('teams-logging.message_style') ?: 'simple';
        $this->name = config('teams-logging.message_title') ?: config('app.name');

        if (! in_array($this->style, ['card', 'simple'])) {
            $this->style = 'simple';
        }
    }

    /**
     * Styling message as simple message
     *
     * @param  string  $level
     * @param  string  $message
     * @param  array  $facts
     */
    public function useCardStyling($level, $message, $facts)
    {
        $loggerColour = new LoggerColour($level);

        // LoggerMessage $data
        $loggerMessageData = [
            'summary' => $level.($this->name ? ': '.$this->name : ''),
            'themeColor' => (string) $loggerColour,
            'sections' => [],
        ];

        // LoggerMessage $data['sections']
        $section = [
            'activityTitle' => config('teams-logging.verbose_title', false)
                ? mb_strtoupper($level).' : '.$this->name.' ('.config('app.url').')'
                : $this->name,
            'activitySubtitle' => $message,
            'facts' => $facts,
            'markdown' => true,
        ];

        if (config('teams-logging.show_avatars', true)) {
            $section['activityImage'] = (string) new LoggerAvatar($level);
        }

        if (config('teams-logging.show_type', true)) {
            $section['activitySubtitle'] = '<span style="color:#'.(string) $loggerColour.'">'.$message.'</span>';
        }

        $loggerMessageData['sections'][] = $section;

        $loggerMessage = new LoggerMessage($loggerMessageData);

        return $loggerMessage->jsonSerialize();
    }

    /**
     * Styling message as simple message
     *
     * @param  string  $level
     * @param  string  $message
     */
    public function useSimpleStyling($level, $message)
    {
        $loggerColour = new LoggerColour($level);

        return new LoggerMessage([
            'text' => ($this->name ? $this->name.' - ' : '').'<span style="color:#'.(string) $loggerColour.'">'.$level.'</span>: '.$message,
            'themeColor' => (string) $loggerColour,
        ]);
    }

    /**
     * @return LoggerMessage
     */
    protected function getMessage(LogRecord $record)
    {
        if ($this->style == 'card') {
            $facts = [];

            // Date
            if (config('teams-logging.show_date', true)) {
                $facts[] = [
                    'name' => 'Sent Date',
                    'value' => date(config('teams-logging.date_format', 'D, M d Y H:i:s e')),
                ];
            }

            // Route
            if (config('teams-logging.show_route', false) && request()) {
                $facts[] = [
                    'name' => 'Route',
                    'value' => request()->getMethod().' : '.request()->getPathInfo(),
                ];
            }

            // (Controller) Action
            if (config('teams-logging.show_action', false) && request() && request()->route()) {
                $facts[] = [
                    'name' => 'Action',
                    'value' => request()->route()->getActionName(),
                ];
            }

            // Logged-In User
            if (config('teams-logging.show_user', false) && auth()->check()) {
                $facts[] = [
                    'name' => 'User',
                    'value' => auth()->user()->name.' ('.auth()->user()->email.')',
                ];
            }

            // Included Context
            foreach ($record['context'] as $name => $value) {
                if ($name == 'exception' && config('teams-logging.show_exception', false)) {
                    $name = ucfirst($name);
                    if ($value instanceof Exception || $value instanceof Throwable) {
                        $exception = FlattenException::create($value);

                        $value = $this->arrayTraceToString($exception, config('teams-logging.exception_limit', 10));
                    }
                }
                $facts[] = ['name' => $name, 'value' => $value];
            }

            return $this->useCardStyling($record['level_name'], $record['message'], $facts);
        }

        return $this->useSimpleStyling($record['level_name'], $record['message']);
    }

    protected function arrayTraceToString(FlattenException $exception, $limit = 10)
    {
        $lines = [];
        $traces = $exception->getTrace();

        foreach ($traces as $index => $trace) {
            if ($limit > 0 && count($lines) >= $limit) {
                break;
            }

            $function = $trace['function'] ?? '';
            $class = $trace['class'] ?? '';
            $type = $trace['type'] ?? '';
            $args = $trace['args'] ?? '';
            $file = $trace['file'] ?? '[internal function]';
            $line = $trace['line'] ?? '';

            $args = $exception->formatArgs($args);

            $html = "<div><strong style='padding-right: 3px;'>{$index}#</strong>";
            if ($function) {
                if ($class) {
                    $html .= $class;
                    if ($type) {
                        $html .= "<strong>{$type}</strong>";
                    }
            }
                $html .= $function."(<span style='color: #777'>{$args}</span>)";
            }
            if ($file) {
                $file = ltrim(str_replace(
                    str_replace('/', '\\', base_path('')),
                    '',
                    str_replace('/', '\\', $file)
                ), '\\');

                $html .= ($function ? ' in' : 'In')." {$file} (line: {$line})";
            }
            $html .= '</div>';

            $lines[] = $html;
        }

        if (count($trace) > count($lines)) {
            $lines[] = '#'.count($lines).' ...';
        } else {
            $lines[] = '#'.count($trace);
        }

        return implode('', $lines); // PHP_EOL
    }

    protected function write(LogRecord $record): void
    {
        try {

        $level = mb_strtolower($record['level_name'] ?? '');
        $allow = config("teams-logging.messages.{$level}", false);

        if ($this->url && $allow) {

            $json = json_encode($this->getMessage($record));
            $ch = curl_init($this->url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: '.mb_strlen($json),
            ]);

            if (! config('teams-logging.verify_ssl', true)) {
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                ]);
            }

            curl_exec($ch);
        }
        } catch (Throwable $e) {
        }
    }
}

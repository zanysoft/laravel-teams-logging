<?php

declare(strict_types=1);

namespace ZanySoft\LaravelTeamsLogging;

class LoggerAvatar
{
    public const EMERGENCY = 'https://adorable-avatars.broken.services/face/eyes7/nose7/mouth7/721C24';

    public const ALERT = 'https://adorable-avatars.broken.services/face/eyes7/nose7/mouth6/AF2432';

    public const CRITICAL = 'https://adorable-avatars.broken.services/face/eyes7/nose7/mouth5/FF0000';

    public const ERROR = 'https://adorable-avatars.broken.services/face/eyes7/nose7/mouth9/FF8000';

    public const WARNING = 'https://adorable-avatars.broken.services/face/eyes6/nose7/mouth10/FFEEBA';

    public const NOTICE = 'https://adorable-avatars.broken.services/face/eyes6/nose7/mouth3/B8DAFF';

    public const INFO = 'https://adorable-avatars.broken.services/face/eyes5/nose7/mouth1/BEE5EB';

    public const DEBUG = 'https://adorable-avatars.broken.services/face/eyes5/nose7/mouth1/C3E6CB';

    /** @var string */
    private $const;

    public function __construct($const = 'DEBUG')
    {
        $this->const = $const;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return config('teams-logging.avatars.'.mb_strtolower($this->const), constant('self::'.$this->const));
    }
}

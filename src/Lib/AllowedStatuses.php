<?php

namespace BeeJeeMVC\Lib;

class AllowedStatuses
{
    public const DONE_STATUS = 'done';
    public const EDITED_STATUS = 'edited';
    public const ALLOWED_STATUSES = [
        self::DONE_STATUS,
        self::EDITED_STATUS,
    ];
}

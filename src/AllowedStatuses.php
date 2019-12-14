<?php

namespace BeeJeeMVC;

class AllowedStatuses
{
    private const CREATED_STATUS = 'created';
    private const DONE_STATUS = 'done';
    private const EDITED_STATUS = 'edited';
    private const ALLOWED_STATUSES = [
        self::CREATED_STATUS,
        self::DONE_STATUS,
        self::EDITED_STATUS,
    ];

    /**
     * @return array
     */
    public function getAllowedStatuses(): array
    {
        return self::ALLOWED_STATUSES;
    }

    /**
     * @return string
     */
    public function getCreatedStatus(): string
    {
        return self::CREATED_STATUS;
    }

    /**
     * @return string
     */
    public function getDoneStatus(): string
    {
        return self::DONE_STATUS;
    }

    /**
     * @return string
     */
    public function getEditStatus(): string
    {
        return self::EDITED_STATUS;
    }
}

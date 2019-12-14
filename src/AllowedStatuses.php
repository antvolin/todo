<?php

namespace BeeJeeMVC;

class AllowedStatuses
{
    private const DONE_STATUS = 'done';
    private const EDITED_STATUS = 'edited';

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

    /**
     * @return array
     */
    public function getAllowedStatuses(): array
    {
        return [
            self::DONE_STATUS,
            self::EDITED_STATUS,
        ];
    }
}

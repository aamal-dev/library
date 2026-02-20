<?php

namespace App\Enums;

enum WaitingListStatusEnum: string
{
    case PENDING = 'pending';

    case PROCESSED = 'processed';
}

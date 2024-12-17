<?php

namespace App\Enum;

enum SuccessEnum: string {
    case SUCCESS = 'success';
    case FAILURE = 'failure';
    case WARNING = 'warning';
    case REDIRECT = 'redirect';
}

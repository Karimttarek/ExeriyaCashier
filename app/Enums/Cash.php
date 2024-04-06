<?php

namespace App\Enums;

enum Cash  : int {

    case RECEIPT_TO_SUPPLIER = 2;
    case RECEIPT_TO_CUSTOMER = 8;
    case EXPENSE = 5;
    case OTHER = 10;

    public static function Values(): array{
        return array_column(Cash::cases(), 'value');
    }
}

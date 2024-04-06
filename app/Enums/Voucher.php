<?php

namespace App\Enums;

use app\Enums\Enum;

enum Voucher : int {

    use Enum;

    case RECEIPT_FROM_CUSTOMER = 1;
    case RECEIPT_FROM_SUPPLIER = 9;
    case OTHER = 11;

    public static function Values(): array{
        return array_column(Voucher::cases(), 'value');
    }
}

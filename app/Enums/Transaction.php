<?php

namespace App\Enums;

enum Transaction : int {
    case PURCHASE = 1;
    case SALES = 2;
    case PURCHASE_RETURN = 3;
    case SALES_RETURN = 4;
    case POS_RECEIPT = 5;
    case POS_RETURN_RECEIPT = 6;


    public function labels(): int
    {
        return match ($this) {
            self::PURCHASE         => __('app.PURCHASE'),
            self::SALES       => __('app.SALES'),
            self::PURCHASE_RETURN      => __('app.PURRETURN'),
            self::SALES_RETURN      => __('app.SALESRETURN'),
            self::POS_RECEIPT      => __('app.POS'),
            self::POS_RETURN_RECEIPT      => __('app.POSRETURN'),
        };
    }

    public static function Values(): array{
        return array_column(self::cases(), 'value');
    }

    /**
     * Sends labels to PowerGrid Enum Input
     *
     */
    public function labelPowergridFilter(): string
    {
        return $this->labels();
    }
}

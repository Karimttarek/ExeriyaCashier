<?php

namespace App\Enums;

enum Role : int {
    case USER = 0;
    case MANGER = 1;
    case ADMIN = 2;
    case CASHIER = 3;

    public function labels(): string
    {
        return match ($this) {
            self::USER         => __('app.User'),
            self::MANGER       => __('app.Manager'),
            self::ADMIN      => __('app.Admin'),
            self::CASHIER      => __('app.Cashier'),
        };
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

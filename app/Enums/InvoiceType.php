<?php

namespace App\Enums;

enum InvoiceType  : string {

    case I = "INVOICEI";
    case C = "CreditNote(C)";
    case D = "DebitNote(D)";

    public static function Values(): array{
        return array_column(self::cases(), 'value');
    }

    public static function byName(string $name): string
    {
        foreach (self::cases() as $status) {
            if( $name === $status->name ){
                return $status->value;
            }
        }
        throw new \ValueError("$name is not a valid backing value for enum " . self::class );
    }
}

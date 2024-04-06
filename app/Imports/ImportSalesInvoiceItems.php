<?php

namespace App\Imports;

use App\Models\InvoicesDetails;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ImportSalesInvoiceItems implements ToModel , WithStartRow ,WithChunkReading //WithValidation
{

    protected $fileName;


    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row){


        return new InvoicesDetails([
            'invoice_number' => $row[0],
            'invoice_type' => 2,
            'item' => $row[1],
            'qty' => $row[2],
            'price' => $row[3],
            'tax' => $row[4],
            'tax_per' => $row[5],
            'discount' => $row[6],
            'discount_per' => $row[7],
            'total' => $row[8],
        ]);

    }

}

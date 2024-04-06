<?php

namespace App\Imports;

use App\Models\InvoicesHead;
use App\Models\InvoicesDetails;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ImportSalesInvoice implements ToModel , WithStartRow ,WithChunkReading //WithValidation
{


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
     * Transform a date value into a Carbon object.
     *
     * @return \Carbon\Carbon|null
     */
    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row){

        return new InvoicesHead([
            'invoice_type' => 2,
            'invoice_number' => $row[0],
            'invoice_date' => $this->transformDate($row[1]),
            'status' =>'paid',
            'customer' => $row[2],
            'customer_details' => $row[3],
            'invoice_discount' => $row[4],
            'invoice_extra_discount' => $row[5],
            'invoice_tax' => $row[6],
            'invoice_extra_tax' => $row[7],
            'total_items' => $row[8],
            'total_tax' => $row[9],
            'total_discount' => $row[10],
            'total_after_discount' => $row[11],
            'total' => $row[12],
            'notes' => $row[13],
            'items_count' => $row[14],
            'entry' => Auth::user()->name,
            'created_at' => Carbon::now(),
        ]);


    }

}

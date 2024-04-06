<?php

namespace App\Imports;

use App\Models\InvoicesHead;
use App\Models\InvoicesDetails;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Events\BeforeSheet;

class ImportPurInvoice implements ToModel , WithStartRow ,WithChunkReading  ,SkipsUnknownSheets
{

    public $sheetNames;

    public function __construct(){
        $this->sheetNames = [];
    }
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $this->sheetNames[] = $event->getSheet()->getTitle();
            }
        ];
    }
    public function getSheetNames() {
        return $this->sheetNames;
    }
    public function onUnknownSheet($sheetName)
    {
        // E.g. you can log that a sheet was not found.
        info("Sheet {$sheetName} was skipped");
    }

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

        return $this->getSheetNames();
//        return new InvoicesHead([
//            'invoice_type' => 1 ,
//            'invoice_number' => $row[0],
//            'invoice_date' => $this->transformDate($row[1]),
//            'status' =>'paid',
//            'vendor' => $row[2],
//            'vendor_tax_reg_number' => $row[3],
//            'vendor_details' => $row[4],
//            'invoice_discount' => $row[5],
//            'invoice_extra_discount' => $row[6],
//            'invoice_tax' => $row[7],
//            'invoice_extra_tax' => $row[8],
//            'total_items' => $row[9],
//            'total_tax' => $row[10],
//            'total_discount' => $row[11],
//            'total_after_discount' => $row[12],
//            'total' => $row[13],
//            'notes' => $row[14],
//            'items_count' => $row[15],
//            'entry' => Auth::user()->name,
//            'created_at' => Carbon::now(),
//        ]);
//        return User::all();


    }

}

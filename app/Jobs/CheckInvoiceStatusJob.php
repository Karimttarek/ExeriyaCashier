<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CheckInvoiceStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $submitionUUID ;
    /**
     * Create a new job instance.
     */
    public function __construct($submitionUUID)
    {
        $this->submitionUUID = $submitionUUID;
    }

    /**
     * @param $submitionUUID
     * @return string
     */
    public function handle(): void
    {
        $access_token = app('App\Http\Controllers\Auth\InvoicePortalController')->index();
        $response = Http::withHeaders([
            'Authorization' => $access_token,
            'Content-Type' => 'application/json',
        ])->get(config('eta.PRDapiBaseUrl').'api/v1.0/documentSubmissions/'.$this->submitionUUID);

        DB::table('invoicehead')->where('submission_uuid',$this->submitionUUID)->update([
            'status' => $response['overallStatus']
        ]);
    }
}

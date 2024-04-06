<?php

namespace App\Http\Controllers\DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

class DBController extends Controller
{
    public function backup(){
        Artisan::call('backup:run',['--only-db'=>true]);
        return redirect()->route('home')->with('status','Backup Created');
    }
}

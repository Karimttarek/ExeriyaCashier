<?php

namespace App\Http\Controllers\Main;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(){
        // $users = DB::table('users')->whereNotNull('email_verified_at')->select('id','name','email','role','created_at')->paginate(env('PAGINATE'));
        $pendingUsers = DB::table('users')->whereNull('email_verified_at')->select('id','name','email','role','created_at')->paginate(10);
        return view('user.get' , compact('pendingUsers'));
    }

    public function destroy(Request $request){

        foreach( $request->item as $uuids){
            // DB::table('users')->where('id' , $uuids)
            //     ->whereNotIn('id' , [1 ,auth()->id()])
            //     ->update([
            //         'deleted_at' => date("Y-m-d\TH:i", strtotime(\Carbon\Carbon::now('Africa/Cairo')))
            //     ]);
            DB::table('users')->where('id' , $uuids)->delete();
        }

        return redirect()->route('User.get')->with('status',__('app.DS'));
    }

    public function accept($uuid){

        DB::table('users')->where('id' ,$uuid)->update([
            'email_verified_at' => date("Y-m-d\TH:i", strtotime(\Carbon\Carbon::now('Africa/Cairo')))
        ]);
        return redirect()->route('User.get')->with('status', __('app.AU') );
    }

    public function refuse($uuid){

        DB::table('users')->where('id' ,$uuid)->delete();
        return redirect()->route('User.get')->with('status', __('app.RU'));
    }

    public function setManager($uuid){
        DB::table('users')->where('id' ,$uuid)->update([
            'role' => Role::MANGER->value
        ]);
        return redirect()->route('User.get')->with('status', __('app.MAM'));
    }

    public function setAdmin($uuid){
        DB::table('users')->where('id' ,$uuid)->update([
            'role' => Role::ADMIN->value
        ]);
        return redirect()->route('User.get')->with('status', __('app.MAA'));
    }

    public function setUser($uuid){
        DB::table('users')->where('id' ,$uuid)->update([
            'role' => Role::USER->value
        ]);
        return redirect()->route('User.get')->with('status', __('app.MAU'));
    }

    public function setCashier($uuid){
        DB::table('users')->where('id' ,$uuid)->update([
            'role' => Role::CASHIER->value
        ]);
        return redirect()->route('User.get')->with('status', __('app.MAC'));
    }
}

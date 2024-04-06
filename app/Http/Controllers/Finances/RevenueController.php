<?php

namespace App\Http\Controllers\Finances;

use App\Http\Controllers\Controller;
use App\Services\Finances\FinanceService;
use Illuminate\Http\Request;

class RevenueController extends Controller
{
    private $type = 12;
    private string $table = 'revenues';
    /**
     * @param FinanceService $financeService
     */
    public function __construct(private FinanceService $financeService){}

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function create(){
        return $this->financeService->create($this->type,'finances.revenues.create' ,$this->table);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request){

        $this->financeService->store($request, $this->type ,'إيرادات');

        return redirect()->route('Finances.Revenues.get')->with('status' , __('app.SS'));
    }

    /**
     * @param $uuid
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function edit($uuid){
        return $this->financeService->edit($uuid , 'finances.revenues.edit', $this->table);
    }

    /**
     * @param Request $request
     * @param $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request ,$uuid){
        $this->financeService->update($request ,$uuid ,$this->type);

        return redirect()->route('Finances.Revenues.get')->with('status' , __('app.US'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $items = DB::table('products')->count();
        $totalsales = DB::table('invoicehead')->where('invoice_type', '2')->where('invoice_date', '>=', now()->subDays(30)->endOfDay())
                ->sum('total');
        $totalpur = DB::table('invoicehead')->where('invoice_type', '1')->where('invoice_date', '>=', now()->subDays(30)->endOfDay())
        ->sum('total');

                $purchase_options = [
                    'chart_title' => __('app.PURCHASES'),
                    'report_type' => 'group_by_date',
                    'model' => 'App\Models\InvoicesHead',
                    'conditions'=> [
                            ['name' => 'Purchase', 'condition' => 'invoice_type = 1', 'color' => 'gray', 'fill' => true],
                        ],
                    'group_by_field' => 'invoice_date',
                    'group_by_period' => 'day',
                    'aggregate_function' => 'sum',
                    'aggregate_field' => 'total',
                    'chart_type' => 'line',
                    'filter_field' => 'invoice_date',
                    'filter_days' => 30, // show only transactions for last 30 days
                    'filter_period' => 'month', // show only transactions for this week
                    'continuous_time' => true, // show continuous timeline including dates without data
                ];

                $purchase = new LaravelChart($purchase_options);

                $sales_options = [
                    'chart_title' => __('app.SALES'),
                    'report_type' => 'group_by_date',
                    'model' => 'App\Models\InvoicesHead',
                    'conditions'=> [
                            ['name' => 'Sales', 'condition' => 'invoice_type = 2', 'color' => 'gray', 'fill' => true],
                        ],
                    'group_by_field' => 'invoice_date',
                    'group_by_period' => 'day',
                    'aggregate_function' => 'sum',
                    'aggregate_field' => 'total',
                    'chart_type' => 'line',
                    'filter_field' => 'invoice_date',
                    'filter_days' => 30, // show only transactions for last 30 days
                    'filter_period' => 'month', // show only transactions for this week
                    'continuous_time' => true, // show continuous timeline including dates without data
                ];

                $sales = new LaravelChart($sales_options);

        return view('home', compact('totalpur','totalsales','purchase','sales' ));

    }
}

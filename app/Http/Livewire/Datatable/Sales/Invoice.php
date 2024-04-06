<?php

namespace App\Http\Livewire\Datatable\Sales;

use App\Actions\ETA\CancelInvoice;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};
use Throwable;
use WireUi\Traits\Actions;

final class Invoice extends PowerGridComponent
{
    use ActionButton;
    use WithExport;
    use Actions;

    public string $primaryKey = 'uuid';
    public string $sortField = 'uuid';
    private int $invoiceType = 2;

    /*
    |--------------------------------------------------------------------------
    |  Features Setup
    |--------------------------------------------------------------------------
    | Setup Table's general features
    |
    */
    public function setUp(): array
    {
        $this->showCheckBox('uuid');

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput()->showToggleColumns()->withoutLoading(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    |  Datasource
    |--------------------------------------------------------------------------
    | Provides data to your Table using a Eloquent, Query Builder or Collection
    |
    */

    /**
     * PowerGrid datasource.
     *
     *
     */
    public function datasource()
    {
        return DB::table('invoicehead')
        ->where('invoice_type' , $this->invoiceType)->whereNot('status','Draft')
        ->orderBy('invoice_date' , 'desc')
        ->orderBy('id' , 'desc')
        ->select('uuid','id','internal_id','submission_uuid','document_uuid','invoice_date','customer_name','total_tax','total_discount','total','items_count','status');
    }

    /*
    |--------------------------------------------------------------------------
    |  Relationship Search
    |--------------------------------------------------------------------------
    | Configure here relationships to be used by the Search and Table Filters.
    |
    */

    /**
     * Relationship search.
     *
     * @return array<string, array<int, string>>
     */
    public function relationSearch(): array
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    |  Add Column
    |--------------------------------------------------------------------------
    | Make Datasource fields available to be used as columns.
    | You can pass a closure to transform/modify the data.
    |
    | ❗ IMPORTANT: When using closures, you must escape any value coming from
    |    the database using the `e()` Laravel Helper function.
    |
    */
    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('uuid')
            ->addColumn('document_uuid')
            ->addColumn('internal_id', fn ($model) => '<a href="'.route('Sales.edit',$model->uuid).'">'. e($model->internal_id) .'</a>' )
            ->addColumn('invoice_date', fn ($model) => '<a href="'.route('Sales.edit',$model->uuid).'">'. e( Carbon::parse($model->invoice_date)->format('Y/m/d-h:i')) .'</a>' )
            ->addColumn('customer_name', fn ($model) => '<a href="'.route('Sales.edit',$model->uuid).'">'. e($model->customer_name) .'</a>' )
            ->addColumn('total_discount', fn($model) => number_format($model->total_discount,5))
            ->addColumn('total_tax', fn($model) => number_format($model->total_tax,5))
            ->addColumn('total', fn($model) => number_format($model->total,5))
            ->addColumn('status')
            ->addColumn('items_count');
    }

    /*
    |--------------------------------------------------------------------------
    |  Include Columns
    |--------------------------------------------------------------------------
    | Include the columns added columns, making them visible on the Table.
    | Each column can be configured with properties, filters, actions...
    |
    */

     /**
      * PowerGrid Columns.
      *
      * @return array<int, Column>
      */
    public function columns(): array
    {
        $text_dir = LaravelLocalization::getCurrentLocale() == 'en' ? 'text-left' : 'text-right';
        return [
            Column::make('Uuid', 'uuid')
                ->hidden(),

            Column::make(__('app.INTERNALID'), 'internal_id')
                ->sortable()
                ->searchable()
                ->headerAttribute($text_dir)
                ->bodyAttribute('text-blue-500 hover:underline' ),

            Column::make(__('app.DATE'), 'invoice_date')
                ->sortable()
                ->headerAttribute($text_dir)
                ->bodyAttribute('text-blue-500 hover:underline' ),

            Column::make(__('app.CLIENT'), 'customer_name')
                ->sortable()
                ->searchable()
                ->headerAttribute($text_dir)
                ->bodyAttribute('text-blue-500 hover:underline' ),

            Column::make(__('app.DISCOUNT'), 'total_discount')
                ->sortable()
                ->searchable()
                ->headerAttribute($text_dir),

            Column::make(__('app.TAX'), 'total_tax')
                ->sortable()
                ->searchable()
                ->headerAttribute($text_dir),

            Column::make(__('app.TOTAL'),'total')
                ->sortable()
                ->searchable()
                ->headerAttribute($text_dir),

//            Column::make(__('app.ITEMSCOUNT'), 'items_count')
//                ->headerAttribute($text_dir),

            // Column::make(__('app.STATUS'), 'status')
            //     ->contentClasses([
            //         'Valid' => 'text-green-600',
            //         'Invalid' => 'text-red-600',
            //         'Pending' => 'text-amber-600',
            //         'Canceld' => 'text-muted',
            //     ])
            //     ->sortable()
            //     ->searchable()
            //     ->headerAttribute($text_dir),
        ];
    }

    public static function status()
    {
        return collect(
            [
                ['code' => __('app.PENDING'),  'label' => 'Pending'],
                ['code' => __('app.VALID'),  'label' => 'Valid'],
                ['code' => __('app.INVALID'),  'label' => 'Invalid'],
                ['code' => __('app.CANCELED'),  'label' => 'Canceled'],
            ]
        );
    }
    /**
     * PowerGrid Filters.
     *
     * @return array<int, Filter>
     */
    public function filters(): array
    {
        return [
            Filter::inputText('internal_id')->operators(['contains']),
            Filter::datetimepicker('invoice_date'),
            Filter::inputText('customer_name')->operators(['contains']),
            Filter::select('status' ,'status')
                ->datasource(Invoice::status())
                ->optionValue('label')
                ->optionLabel('code'),
        ];
    }

    public function actions(): array
    {
        return [
            // Button::add('sendToETA')
            //     ->class('text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded inline-block'),

            // Button::add('cancel')
            //     ->class('text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded inline-block'),

            //  Button::add('Valid')
            //      ->class('text-green-600 text-xs font-medium mr-2 px-2.5 py-0.5 rounded inline-block'),

            // Button::add('Invalid')
            //     ->class('text-red-600 text-xs font-medium mr-2 px-2.5 py-0.5 rounded inline-block'),

            // Button::add('Canceled')
            //     ->class('text-gray-400 text-xs font-medium mr-2 px-2.5 py-0.5 rounded inline-block'),

            Button::add('print')
                ->class('text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded inline-block')
                ->caption('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" /></svg>'.__('app.PRINT'))
                ->route('Sales.print' ,['uuid']),

            Button::add('copy')
                ->class('text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded inline-block')
                ->caption('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5A3.375 3.375 0 006.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0015 2.25h-1.5a2.251 2.251 0 00-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 00-9-9z" /></svg>'.__('app.Copy'))
                ->route('Sales.getCopy' ,['uuid'])
                ->target('_self')
        ];
    }
    /**
     * PowerGrid Action Rules.
     *
     * @return array<int, RuleActions>
     */
    public function actionRules(): array
    {
       return [
            Rule::button('sendToETA')
                ->caption('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 7.5h-.75A2.25 2.25 0 004.5 9.75v7.5a2.25 2.25 0 002.25 2.25h7.5a2.25 2.25 0 002.25-2.25v-7.5a2.25 2.25 0 00-2.25-2.25h-.75m0-3l-3-3m0 0l-3 3m3-3v11.25m6-2.25h.75a2.25 2.25 0 012.25 2.25v7.5a2.25 2.25 0 01-2.25 2.25h-7.5a2.25 2.25 0 01-2.25-2.25v-.75" /></svg>' .__('app.SEND'))
                ->when(fn($model) => $model->status != 'Valid' && empty($model->submission_uuid) && empty($model->document_uuid) && Auth::user()->role == 1)
                ->emit('sendToETA',['uuid']),

            Rule::button('cancel')
                ->caption('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'.__('app.CANCEL'))
                ->when(fn($model) => date('Y-m-d H:m' ,strtotime($model->invoice_date)) >= date("Y-m-d H:m", strtotime(Carbon::now('Africa/Cairo')->subDays(7),strtotime(now()))) && Auth::user()->role == 1 && $model->status == 'Valid' && !empty($model->document_uuid))
                ->emit('cancelInvoice', ['document_uuid']),

             Rule::button('Valid')
                 ->setAttribute('disabled','true')
                 ->caption('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'.__('app.VALID'))
                 ->when(fn($model) => $model->status == 'Valid' && date('Y-m-d H:m' ,strtotime($model->invoice_date)) <= date("Y-m-d H:m", strtotime(Carbon::now('Africa/Cairo')->subDays(7),strtotime(now()))) && Auth::user()->role == 1)
                 ->emit('cannotDecline', []),

           Rule::button('Invalid')
               ->setAttribute('disabled','true')
               ->caption('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'.__('app.INVALID'))
               ->when(fn($model) => $model->status == 'Invalid' && !empty($model->submission_uuid) && !empty($model->document_uuid) && Auth::user()->role == 1)
               ->emit('cannotDecline', []),

           Rule::button('Canceled')
               ->setAttribute('disabled','true')
               ->caption('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>'.__('app.CANCELED'))
               ->when(fn($model) => $model->status == 'Canceled' && !empty($model->submission_uuid) && !empty($model->document_uuid) && Auth::user()->role == 1)
               ->emit('cannotDecline', []),
        ];
    }

    public function header(): array
    {
        return [
            Button::add('New')
                ->caption(__('app.NEWINVOICE'))
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-500 dark:hover:bg-pg-primary-700
                dark:ring-offset-pg-primary-800 dark:text-pg-primary-200 dark:bg-pg-primary-600')
                ->route('Sales.create',[])
                ->target('_self'),

            Button::add('bulk-delete')
                ->caption(__(__('app.TRASH').' (<span x-text="window.pgBulkActions.count(\'' . $this->tableName . '\')"></span>)'))
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-500 dark:hover:bg-pg-primary-700
                dark:ring-offset-pg-primary-800 dark:text-pg-primary-200 dark:bg-pg-primary-600')
                ->emit('bulkDelete', []),
        ];
    }

    protected function getListeners()
    {
        return array_merge(
            parent::getListeners(), [
                'bulkDelete',
                'cancelInvoice',
                'sendToETA',
            ]);
    }

    public function bulkDelete(): void
    {
        if (count($this->checkboxValues) == 0) {
            $this->notification()->warning(
                __('app.Warning'),
                __('app.You must select at least one item')
            );
        }else{
            $this->notification()->confirm([
                'title'       => __('app.Are you Sure'),
                'description' => __('app.Are you sure to move this to trash'),
                'icon'        => 'question',
                'accept'      => [
                    'label'  => __('app.Confirm'),
                    'method' => 'save',
                    'params' => 'Saved',
                ],
                'reject' => [
                    'method' => 'cancel',
                    'label'  => __('app.CANCEL'),
                ],
            ]);
            // $this->dispatchBrowserEvent('showAlert', ['items' => $this->checkboxValues]);
            return;
        }
    }
    public function cancel(){
        $this->notification()->success(
            __('app.CANCEL'),
            __('app.Canceld')
        );
    }

    public function save(){
        try{
            DB::beginTransaction();
                foreach ($this->checkboxValues  as $uuids) {
                    DB::table('invoicehead')->where(['uuid' => $uuids ,'invoice_type' => $this->invoiceType])->delete();
                    DB::table('invoicedetails')->where('uuid' , $uuids)->delete();
                    DB::table('receipts')->where('uuid' , $uuids)->delete();
                    DB::table('receipts')->where('reference' , $uuids)->delete();
                }
            DB::commit();

            $this->notification()->success(
                __('app.SUCCESS'),
                __('app.DS')
            );
        }catch(Throwable $e){
            $this->notification()->error(
                __('app.ERROR'),
                __('app.SWH'),
            );
        }
    }

    /**
     * Cancel Invoice
     */
    public function cancelInvoice($document_uuid): void
    {
        $this->notification()->confirm([
            'title'       => __('app.Are you Sure'),
            'description' => __('app.Are you sure to cancel this invoice'),
            'icon'        => 'question',
            'accept'      => [
                'label'  => __('app.Confirm'),
                'method' => 'yes',
                'params' => [$document_uuid],
            ],
            'reject' => [
                'method' => 'no',
                'label'  => __('app.CANCEL'),
            ],
        ]);
    }
    public function no(){
        $this->notification()->success(
            __('app.CANCEL'),
            __('app.Canceld')
        );
    }

    public function yes($document_uuid){
       (new CancelInvoice)->execute($document_uuid);

    }

    public function sendToETA($uuid){
        $this->dispatchBrowserEvent('sendToETA', ['uuid' => $uuid]);
    }
}

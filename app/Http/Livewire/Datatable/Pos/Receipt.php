<?php

namespace App\Http\Livewire\Datatable\Pos;

use App\Enums\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};
use Throwable;
use WireUi\Traits\Actions;

final class Receipt extends PowerGridComponent
{
    use ActionButton;
    use WithExport;
    use Actions;

    public string $primaryKey = 'uuid';
    public string $sortField = 'uuid';
    private int $invoiceType = 5;
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
    | Provides data to your Table using a Model or Collection
    |
    */

    /**
     * PowerGrid datasource.
     *
     * @return Builder<\App\Models\InvoicesHead>
     */
    public function datasource()
    {
        return DB::table('invoicehead')
        ->where('invoice_type' , $this->invoiceType)
        ->whereNot('status','Stocktaked')
        ->whereNull('deleted_at')
        ->orderBy('invoice_date' , 'desc')
        ->orderBy('id' , 'desc')
        ->select('uuid','id','document_uuid','submission_uuid','document_uuid','invoice_date','customer_name','total_tax','total_discount','total','items_count','status');
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
        ->addColumn('document_uuid', fn ($model) => '<a href="'.route('POS.edit',$model->uuid).'">'. e($model->document_uuid) .'</a>' )
        ->addColumn('invoice_date', fn ($model) => '<a href="'.route('POS.edit',$model->uuid).'">'. e( Carbon::parse($model->invoice_date)->format('Y/m/d-h:i')) .'</a>' )
        ->addColumn('customer_name', fn ($model) => '<a href="'.route('POS.edit',$model->uuid).'">'. e($model->customer_name) .'</a>' )
        ->addColumn('total', fn($model) => number_format($model->total,2))
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

            Column::make(__('app.POSID'), 'document_uuid')
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

            Column::make(__('app.TOTAL'),'total')
                ->sortable()
                ->searchable()
                ->headerAttribute($text_dir),
        ];
    }

    /**
     * PowerGrid Filters.
     *
     * @return array<int, Filter>
     */
    public function filters(): array
    {
        return [
            Filter::inputText('document_uuid')->operators(['contains']),
            Filter::datetimepicker('invoice_date'),
            Filter::inputText('customer_name')->operators(['contains']),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    | Enable the method below only if the Routes below are defined in your app.
    |
    */

    /**
     * PowerGrid InvoicesHead Action Buttons.
     *
     * @return array<int, Button>
     */


    public function actions(): array
    {
       return [
            Button::add('print')
                ->class('text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded inline-block')
                ->caption('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" /></svg>'.__('app.PRINT'))
                ->route('POS.print' ,['uuid']),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    | Enable the method below to configure Rules for your Table and Action Buttons.
    |
    */

    /**
     * PowerGrid InvoicesHead Action Rules.
     *
     * @return array<int, RuleActions>
     */

    /*
    public function actionRules(): array
    {
       return [

           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($invoices-head) => $invoices-head->id === 1)
                ->hide(),
        ];
    }
    */

    public function header(): array
    {
        return [
            Button::add('New')
                ->caption(__('app.NEWPOS'))
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-500 dark:hover:bg-pg-primary-700
                dark:ring-offset-pg-primary-800 dark:text-pg-primary-200 dark:bg-pg-primary-600')
                ->route('POS.create',[])
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

    public function save(){
        try{
            DB::beginTransaction();
                foreach ($this->checkboxValues  as $uuids) {
                    DB::table('invoicehead')->where(['uuid' => $uuids ,'invoice_type' => $this->invoiceType])->delete();
                    DB::table('invoicedetails')->where('uuid' , $uuids)->delete();
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
}

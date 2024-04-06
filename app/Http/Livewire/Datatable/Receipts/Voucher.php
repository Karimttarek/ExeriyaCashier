<?php

namespace App\Http\Livewire\Datatable\Receipts;

use Illuminate\Support\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};
use Throwable;
use WireUi\Traits\Actions;

final class Voucher extends PowerGridComponent
{
    use ActionButton;
    use WithExport;
    use Actions;

    public string $primaryKey = 'uuid';
    public string $sortField = 'id';
    private $receiptTypes = [1,9,11];

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
            // Exportable::make('export')
            //     ->striped()
            //     ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
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
     * @return Builder
     */
    public function datasource(): Builder
    {
        return DB::table('receipts')
            ->whereIn('receipt_type' , $this->receiptTypes)
            ->orderBy('id','desc')
            ->orderBy('receipt_date','desc')
            ->select('uuid' ,'no' ,'receipt_date' ,'statement','receiver_name', 'exp_name','value');
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
            ->addColumn('no' , fn ($model) => '<a href="'.route('Voucher.edit',$model->uuid).'">'. e($model->no) .'</a>' )
            ->addColumn('statement')
            ->addColumn('receipt_date', fn ($model) => '<a href="'.route('Voucher.edit',$model->uuid).'">'. e( Carbon::parse($model->receipt_date)->format('Y/m/d-h:i')) .'</a>' )
            ->addColumn('receiver_name', fn ($model) => '<a href="'.route('Voucher.edit',$model->uuid).'">'. e($model->receiver_name) .'</a>' )
            ->addColumn('statement')
            ->addColumn('receiver')
            ->addColumn('value' , fn($model) => number_format($model->value , 5));
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

            Column::make(__('app.RECEIPTID'), 'no')
                ->sortable()
                ->searchable()
                ->headerAttribute($text_dir)
                ->bodyAttribute('text-blue-500 hover:underline' ),

            Column::make(__('app.RECEIPTDATE'), 'receipt_date')
                ->searchable()
                ->sortable()
                ->headerAttribute($text_dir)
                ->bodyAttribute('text-blue-500 hover:underline' ),

            Column::make(__('app.RECEIVERNAME'), 'receiver_name')
                ->sortable()
                ->searchable()
                ->headerAttribute($text_dir)
                ->bodyAttribute('text-blue-500 hover:underline' ),

            Column::make(__('app.STATEMENT'), 'statement')
                ->sortable()
                ->searchable()
                ->headerAttribute($text_dir),

            Column::make(__('app.VALUE'), 'value')
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
            Filter::inputText('no')->operators(['contains']),
            Filter::datetimepicker('receipt_date'),
            Filter::inputText('receiver_name')->operators(['contains']),
            Filter::inputText('statement')->operators(['contains']),
            Filter::inputText('value')->operators(['contains']),
        ];
    }

    public function header(): array
    {
        return [
            Button::add('New')
                ->caption(__('app.New Receipt'))
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-500 dark:hover:bg-pg-primary-700
                dark:ring-offset-pg-primary-800 dark:text-pg-primary-200 dark:bg-pg-primary-600')
                ->route('Voucher.create',[])
                ->target('_self'),

            Button::add('bulk-delete')
                ->caption(__(__('app.TRASH').' (<span x-text="window.pgBulkActions.count(\'' . $this->tableName . '\')"></span>)'))
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-500 dark:hover:bg-pg-primary-700
                dark:ring-offset-pg-primary-800 dark:text-pg-primary-200 dark:bg-pg-primary-600')
                ->emit('bulkDelete', []),
        ];
    }

    // public function bulkDelete(){
    //     $ids = implode(', ', $this->checkboxValues);
    //     return $this->checkboxValues;
    // }
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
    public function cancel(){
        $this->notification()->success(
            __('app.CANCEL'),
            __('app.Canceld')
        );
    }

    public function save(){
        try{
            foreach ($this->checkboxValues  as $uuids) {
                DB::table('receipts')
                    ->where('uuid' , $uuids)
                    ->whereIn('receipt_type' , $this->receiptTypes)
                    ->delete();
            }
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

<?php

namespace App\Http\Livewire\Datatable\Main;

use Illuminate\Support\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};
use WireUi\Traits\Actions;

final class Client extends PowerGridComponent
{
    use ActionButton;
    use WithExport;
    use Actions;

    public string $primaryKey = 'uuid';
    public string $sortField = 'uuid';
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
     * @return Builder
     */
    public function datasource(): Builder
    {
        return DB::table('customers')
            ->orderBy('id')
            ->select('id','uuid','id','name','tax_code','email','mobile','street');
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
            ->addColumn('id' , fn ($model) => '<a href="'.route('Client.edit',$model->uuid).'">'. e($model->id) .'</a>' )
            ->addColumn('uuid')
            ->addColumn('tax_code' , fn ($model) => '<a href="'.route('Client.edit',$model->uuid).'">'. e($model->tax_code) .'</a>' )
            ->addColumn('name' , fn ($model) => '<a href="'.route('Client.edit',$model->uuid).'">'. e($model->name) .'</a>' )
            ->addColumn('email')
            ->addColumn('mobile')
            ->addColumn('street');
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

            Column::make(__('app.ID'), 'id')
                ->sortable()
                ->searchable()
                ->headerAttribute($text_dir)
                ->bodyAttribute('text-blue-500 hover:underline' ),

            Column::make(__('app.TAXREGCODE'), 'tax_code')
                ->sortable()
                ->searchable()
                ->headerAttribute($text_dir)
                ->bodyAttribute('text-blue-500 hover:underline' ),

            Column::make(__('app.NAME'), 'name')
                ->sortable()
                ->searchable()
                ->headerAttribute($text_dir)
                ->bodyAttribute('text-blue-500 hover:underline' ),

            Column::make(__('app.EMAIL'), 'email')
                ->sortable()
                ->searchable()
                ->headerAttribute($text_dir),

            Column::make(__('app.PHONE'), 'mobile')
                ->sortable()
                ->searchable()
                ->headerAttribute($text_dir),

            Column::make(__('app.ADDRESS'), 'street')
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
            Filter::inputText('id')->operators(['contains']),
            Filter::inputText('name')->operators(['contains']),
            Filter::inputText('tax_code')->operators(['contains']),
            Filter::inputText('street')->operators(['contains']),
            Filter::inputText('email')->operators(['contains']),
            Filter::inputText('mobile')->operators(['contains']),
        ];
    }

    public function header(): array
    {
        return [
            Button::add('New')
                ->caption(__('app.NEWCLIENT'))
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-500 dark:hover:bg-pg-primary-700
                dark:ring-offset-pg-primary-800 dark:text-pg-primary-200 dark:bg-pg-primary-600')
                ->route('Client.create',[])
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
    public function cancel(){
        $this->notification()->success(
            __('app.CANCEL'),
            __('app.Canceld')
        );
    }

    public function save(){
        //$this->checkboxValues
        foreach($this->checkboxValues as $uuid){
            DB::table('customers')->where('uuid' , $uuid)->delete();
        }
    }
}

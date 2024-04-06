<?php

namespace App\Http\Livewire\Datatable\Main;

use App\Actions\ETA\Login;
use App\Actions\ETA\reuseCode;
use App\Models\InvoicesDetails;
use Illuminate\Support\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};
use WireUi\Traits\Actions;

final class Product extends PowerGridComponent
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
        //$this->persist(['columns', 'filters']);

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
        return DB::table('products')
            ->select(
            'uuid',
            'code_type',
            'bar_code',
            'codeUsageRequestId',
            'parent_code',
            'item_code',
            LaravelLocalization::getCurrentLocale() == 'en' ? 'name as name' : 'name_ar as name',
            'first_unit_pur_price',
            'first_unit_sell_price',
            'tax',
            'discount',
            'stock',
            'active_from',
            'active_to',
            'active',
            'ported',
            'item_type')
            ->orderBy('id' );
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
            // ->addColumn('code_type' , fn ($model) => '<a href="'.route('Product.edit',$model->uuid).'">'. e($model->code_type) .'</a>' )
            ->addColumn('item_code' , fn ($model) =>
                str_contains($model->item_code , 'EG-') ? '<a href="'.route('Product.edit',$model->uuid).'">'. e(substr($model->item_code, strrpos($model->item_code, '-') + 1)) .'</a>' : '<a href="'.route('Product.edit',$model->uuid).'">'. $model->item_code .'</a>')
            ->addColumn('bar_code', fn ($model) => '<a href="'.route('Product.edit',$model->uuid).'">'. e($model->bar_code) .'</a>' )
            ->addColumn('name' , fn ($model) => '<a href="'.route('Product.edit',$model->uuid).'">'. e($model->name) .'</a>' )
            ->addColumn('first_unit_pur_price')
            ->addColumn('first_unit_sell_price')
            // ->addColumn('active' ,fn ($model) => Carbon::parse($model->active_to)->format('Y-m-d') > date('Y-m-d') ? 'Active' : 'Inactive')
            // ->addColumn('active_from', fn ($model) => Carbon::parse($model->active_from)->format('d/m/Y'))
            // ->addColumn('active_to', fn ($model) => Carbon::parse($model->active_to)->format('d/m/Y'));
            // ->addColumn('action');
            ;
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

            // Column::make(__('app.CODETYPE'), 'code_type')
            //     ->sortable()
            //     ->searchable()
            //     ->headerAttribute($text_dir)
            //     ->bodyAttribute('text-blue-500 hover:underline' ),

            Column::make(__('app.ITEMCODE'), 'item_code')
                ->sortable()
                ->searchable()
                ->headerAttribute($text_dir)
                ->bodyAttribute('text-blue-500 hover:underline'),

            Column::make(__('app.Barcode'), 'bar_code')
            ->sortable()
            ->searchable()
            ->headerAttribute($text_dir)
            ->bodyAttribute('text-blue-500 hover:underline'),


            Column::make(__('app.NAME'), 'name')
                ->sortable()
                ->searchable()
                ->headerAttribute($text_dir)
                ->bodyAttribute('text-blue-500 hover:underline'),

            Column::make(__('app.PURCHASEPRICE'), 'first_unit_pur_price')
                ->headerAttribute($text_dir)
                ->sortable()
                ->searchable(),

            Column::make(__('app.SELLPRICE'), 'first_unit_sell_price')
                ->headerAttribute($text_dir)
                ->sortable()
                ->searchable(),

            // Column::make('Active', 'active')
            //     ->headerAttribute($text_dir)
            //     ->sortable()
            //     ->contentClasses([
            //         'Active' => 'bg-green-600 text-green-600 rounded dark:bg-green-900 dark:text-green-300',
            //         'Inactive' => 'text-red-600',
            //     ]),

            // Column::make(__('app.ACTIVEFROM'), 'active_from')
            //     ->headerAttribute($text_dir)
            //     ->sortable(),

            // Column::make(__('app.ACTIVETO'), 'active_to')
            //     ->headerAttribute($text_dir)
            //     ->sortable(),

        ];
    }

    public static function code_type()
    {
        return collect(
            [
                ['code' => 'GS1',  'label' => 'GS1'],
                ['code' => 'EGS',  'label' => 'EGS'],
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
            Filter::select('code_type' ,'code_type')
                ->datasource(Product::code_type())
                ->optionValue('label')
                ->optionLabel('code'),

            Filter::inputText('item_code')->operators(['contains']),
            Filter::inputText('bar_code')->operators(['contains']),
            Filter::inputText('item_type')->operators(['contains']),
            Filter::inputText('name')->operators(['contains']),
            Filter::datepicker('active_from'),
            Filter::datepicker('active_to','active_to'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    | Enable the method below only if the Routes below are defined in your app.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    | Enable the method below to configure Rules for your Table and Action Buttons.
    |
    */

    public function actions(): array
    {
        return [
            // Button::add('sendETA')
            //     ->class('bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400')
            //     ->target('_self'),

            // Button::add('reuseCodeETA')
            //     ->class('bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400')
            //     ->target('_self')

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
            Rule::button('sendETA')
                ->caption(__('app.SEND'))
                ->when(fn($model) => $model->active == 'Pending' && $model->code_type == 'EGS' && str_contains($model->item_code , config('eta.registration_number')) && Auth::user()->role == 1 )
                ->redirect(fn($model) => route('Product.uploadToInvoice',$model->uuid), '_self'),

           Rule::button('reuseCodeETA')
               ->caption(__('app.SEND'))
               ->when(fn($model) => $model->active == 'Pending' && $model->code_type == 'EGS' && !str_contains($model->item_code , config('eta.registration_number'))  && Auth::user()->role == 1 )
               ->redirect(fn($model) => route('Product.reuseCode',$model->uuid), '_self'),
        ];
    }

    public function header(): array
    {
        return [
            Button::add('New')
                ->caption(__('app.NEWPRODUCT'))
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-500 dark:hover:bg-pg-primary-700
                dark:ring-offset-pg-primary-800 dark:text-pg-primary-200 dark:bg-pg-primary-600')
                ->route('Product.create',[])
                ->target('_self'),

            Button::add('BulkUpload')
                ->caption(__('app.BULKUPLOAD'))
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-500 dark:hover:bg-pg-primary-700
                dark:ring-offset-pg-primary-800 dark:text-pg-primary-200 dark:bg-pg-primary-600')
                ->openModal('new', [])
                ->id('bulkUpload'),

            Button::add('bulk-delete')
                ->caption(__(__('app.TRASH').' (<span x-text="window.pgBulkActions.count(\'' . $this->tableName . '\')"></span>)'))
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-500 dark:hover:bg-pg-primary-700
                dark:ring-offset-pg-primary-800 dark:text-pg-primary-200 dark:bg-pg-primary-600')
                ->emit('bulkDelete', []),

            // Button::add('reuse-code')
            //     ->caption(__(__('app.Reuse Code').' (<span x-text="window.pgBulkActions.count(\'' . $this->tableName . '\')"></span>)'))
            //     ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-500 dark:hover:bg-pg-primary-700
            //     dark:ring-offset-pg-primary-800 dark:text-pg-primary-200 dark:bg-pg-primary-600')
            //     ->emit('reuseCode', []),

        ];
    }

    protected function getListeners()
    {
        return array_merge(
            parent::getListeners(), [
                'bulkDelete',
                'reuseCode',
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
        foreach($this->checkboxValues as $uuids){
            if (!InvoicesDetails::where('item_uuid' ,$uuids)->exists()){
                DB::beginTransaction();
                /**
                 * Delete
                 */
                DB::table('products')->where('uuid' , $uuids)->delete();
                DB::table('manufacturs')->where('parent_uuid' , $uuids)->delete();
                /**
                 * Move To Trash
                 */
                // DB::table('products')->where('uuid' , $uuids)->update(['deleted_at' => Carbon::now()]);
                // DB::table('manufacturs')->where('parent_uuid' , $uuids)->update(['deleted_at' => Carbon::now()]);
                DB::commit();
                DB::rollBack();
            }else{
                $this->notification()->warning(
                    __('app.Warning'),
                    __('app.Product used before, cannot be deleted')
                );
            }
        }
    }

    public function reuseCode(): void
    {
        if (count($this->checkboxValues) == 0) {
            $this->notification()->warning(
                __('app.Warning'),
                __('app.You must select at least one item')
            );
        }else{
            $this->notification()->confirm([
                'title'       => __('app.Are you Sure'),
                'description' => __('app.Are you sure to reuse this code'),
                'icon'        => 'question',
                'accept'      => [
                    'label'  => __('app.Confirm'),
                    'method' => 'reuse',
                    'params' => 'Saved',
                ],
                'reject' => [
                    'method' => 'cancelReuse',
                    'label'  => __('app.CANCEL'),
                ],
            ]);
        }
    }

    public function cancelReuse(){
        $this->notification()->success(
            __('app.CANCEL'),
            __('app.Canceld')
        );
    }

    public function reuse(){
        $this->notification()->success(
            __('app.CANCEL'),
            (new reuseCode())->execute($this->checkboxValues)
        );
    }
}

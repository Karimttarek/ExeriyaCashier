<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb>
            <x-slot name="breadcrumb">
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <p class=" ml-1 text-sm font-medium hover:text-blue-800 md:ml-2 dark:text-gray-400 dark:hover:text-white">{{__('app.Stocktaking')}}</p>
                    </div>
                </li>
            </x-slot>
        </x-breadcrumb>
    </x-slot>
    <!-- Table -->
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">
        @foreach ($data as $d)

            <form method="POST" action="{{route('stocktaking.gard')}}" enctype="multipart/form-data">
                @csrf
                <!-- Date Between -->
                <div class="grid gap-6 md:grid-cols-2 mb-6">
                    <!-- From Date -->
                    <div class="mb-2">
                        <label for="bar_code" class="block mb-2 text-lg font-bold text-gray-900 dark:text-white">
                            {{__('app.From Date')}}
                        </label>
                        <input type="datetime-local" name="from_date" value="{{$from_date}}" id="from_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-lg font-bold rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <!-- Item Code -->
                    <div class="mb-2">
                        <label for="item_code" class="block mb-2 text-lg font-bold text-gray-900 dark:text-white">
                            {{__('app.To Date')}}
                        </label>
                        <input type="datetime-local" name="to_date" value="{{ date("Y-m-d\TH:i", strtotime(\Carbon\Carbon::now('Africa/Cairo'))) }}" id="to_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-lg font-bold rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            >
                    </div>
                </div>
                <!-- Totals -->
                <div class="grid gap-6 md:grid-cols-2 mt-6">
                    <!-- Income -->
                    <div class="mb-2 flex justify-center">
                        <label for="income" id="income" class="block mb-2 text-lg font-bold text-gray-900 dark:text-white">
                            {{__('app.TOTALSALES') . ' : ' . $d->INCOME}}
                        </label>
                    </div>
                    <!-- OutCome -->
                    <div class="mb-2 flex justify-center">
                        <label for="outcome" id="outcome" class="block mb-2 text-lg font-bold text-gray-900 dark:text-white">
                            {{__('app.Total Returns') . ' : ' . $d->OUTCOME}}
                        </label>
                    </div>
                </div>
                <!-- Receipts Count -->
                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Income receipts Count -->
                    <div class="mb-2 flex justify-center">
                        <label for="income" id="sales_count" class="block mb-2 text-lg font-bold text-gray-900 dark:text-white">
                            {{__('app.Receipts Count') . ' : ' . $d->sales_c}}
                        </label>
                    </div>
                    <!-- Outcome receipts Count -->
                    <div class="mb-2 flex justify-center">
                        <label for="outcome" id="return_count" class="block mb-2 text-lg font-bold text-gray-900 dark:text-white">
                            {{__('app.Receipts Count') . ' : ' . $d->return_c}}
                        </label>
                    </div>
                </div>
                <!-- Balance -->
                <div class="grid gap-6 md:grid-cols-1">
                    <!-- Income receipts Count -->
                    <div class="mb-2 flex justify-center">
                        <label for="income" id="balance" class="block mb-2 text-lg font-bold text-gray-900 dark:text-white">
                            {{__('app.amount due') . ' : ' . $d->balance}}
                        </label>
                        <input type="hidden" name="balance" id="bal" value="{{$d->balance}}">
                    </div>
                </div>

                <button type="submit" class="mt-5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    {{__('app.Stocktaking')}}
                </button>

            </form>
        @endforeach
    </div>
    <!-- Table -->
    @push('script')
    <script>
        $(document).ready(function() {
            $(document).on('input' , '#from_date , #to_date' , function (e){
                e.preventDefault();
                $.ajax({
                    url:"{{route('stocktaking.filter.gard')}}",
                    type:'GET',
                    data:{
                        '_token': "{{csrf_token()}}",
                        'from_date':$('#from_date').val(),
                        'to_date':$('#to_date').val(),
                    },
                    success:function(data){
                        $('#income').text("{{__('app.TOTALSALES')}}" + ' : ' + data[0]['INCOME'])
                        $('#outcome').text("{{__('app.Total Returns')}}" + ' : ' + data[0]['OUTCOME'])
                        $('#sales_count').text("{{__('app.Receipts Count')}}" + ' : ' + data[0]['sales_c'])
                        $('#return_count').text("{{__('app.Receipts Count')}}" + ' : ' + data[0]['return_c'])
                        $('#balance').text("{{__('app.amount due')}}" + ' : ' + data[0]['balance'])
                        $('#bal').val(data[0]['balance'])
                    },
                    error:function(data){
                    }
                });
            });
        });

    $('body').on('submit','form', function(e) {
        if (parseFloat($('#bal').val) == 0) {
            e.preventDefault();
            // window.$wireui.notify({
            //         title: {{__('app.Warning')}},
            //         description: {{__('app.There are no receipts to be stocktaked')}},
            //         icon: 'warning'
            //     })
        }
    });
    </script>
    @endpush
</x-app-layout>

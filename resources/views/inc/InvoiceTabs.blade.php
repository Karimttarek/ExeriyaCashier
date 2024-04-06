<div class="grid grid-cols-1">
    <div>
        {{__('app.INTERNALID') .' : '   }}
        @if(isset($head->internal_id) && str_contains(url()->current(), '/edit'))
        {{ $head->internal_id }}
        @else
        {{$id}}
        @endif
    </div>
    @if (@fsockopen("www.google.com", 80))
        {{__('app.Datetime') . ' : ' . Stichoza\GoogleTranslate\GoogleTranslate::trans( (new DateTime(str_contains(url()->current(), '/edit') ? date('Y-m-d h:m:s',strtotime($head->invoice_date)) : "now", new DateTimeZone('Africa/Cairo')))->format('F j, Y, g:i a'),LaravelLocalization::getCurrentLocale()) }}
        {{ fclose(@fsockopen("www.google.com", 80)) }}
    @else
    {{__('app.Datetime') . ' : ' .  (new DateTime(str_contains(url()->current(), '/edit') ? date('Y-m-d h:m:s',strtotime($head->invoice_date)) : "now", new DateTimeZone('Africa/Cairo')))->format('F j, Y, g:i a'),LaravelLocalization::getCurrentLocale() }}
    @endif
</div>


<div x-data="{openTab: 1,activeClasses: 'border-b border-gray-600 text-black',
inactiveClasses: 'hover:text-gray-500 hover:border-b hover:border-gray-500 '}"class=" w-full">
    <div class="flex flex-wrap bg-gray-50 mt-5 mb-5">
        <a href="javascript:void(0)"
            @click="openTab = 1"
            :class="openTab === 1 ? activeClasses : inactiveClasses"
            class="py-2 px-2 text-sm font-medium text-black md:text-base lg:px-2" id="InvoiceTab">
            {{__('app.INVOICE')}}
        </a>
        <a href="javascript:void(0)"
            @click="openTab = 2"
            :class="openTab === 2 ? activeClasses : inactiveClasses"
            class="text-body-color hover:bg-primary py-2 px-4 text-sm font-medium hover:text-black md:text-base lg:px-6" id="ItemTab">
            {{__('app.ITEMS')}}
        </a>
    </div>
    <div>
        @include('inc.invoice.InvoiceTab')
        @if(!isset($head) && str_contains(url()->current(), '/create'))
            @include('inc.invoice.ItemTab')
        @else
            @include('inc.invoice.ItemTabEdit')
        @endif
    </div>
</div>

<div class="list-none">
    <ul>
        <li class="grid grid-cols-2">
            <p>{{__('app.TOTALSALES')}}:</p>
            <input type="number" name="totalSales" id="totalSales" class="text-left totalSales border-none bgb-transparent"
            value="{{$head->total_sales}}" readonly>
        </li>

        <li class="grid grid-cols-2">
            <p>{{__('app.ITEMSSICOUNT')}}:</p>
            <input type="number" name="transTotalItemsDisc" id="transTotalItemsDisc" class="text-left transTotalItemsDisc border-none bgb-transparent"
             value="{{$head->total_items_discount}}" readonly>
        </li>
        <li class="grid grid-cols-2">
            <p>{{__('app.TOTALDISCOUNT')}}:</p>
            <input type="number" name="transDisc" id="transDisc" class="text-left transDisc border-none bgb-transparent"
            value="{{$head->total_discount}}" readonly>
        </li>
        <li class="grid grid-cols-2">
            <p>{{__('app.NET')}}:</p>
            <input type="number" name="totalNet" id="totalNet" class="text-left totalNet border-none bgb-transparent"
            value="{{$head->total_net}}" readonly>
        </li>
        <li class="grid grid-cols-2">
            <p>{{__('app.TAX')}}:</p>
            <input type="number" name="transTax" id="transTax" class="text-left transTax border-none bgb-transparent"
            value="{{$head->total_tax}}" readonly>
        </li>
        <li class="grid grid-cols-2">
            <p>{{__('app.TAXTABLE')}}:</p>
            <input type="number" name="transTaxTable" id="transTaxTable" class="text-left transTaxTable border-none bgb-transparent"
            value="{{$head->total_tax_table}}" readonly>
        </li>
        <input type="hidden" name="transTotalDiscAfterTax" id="transTotalDiscAfterTax" class="text-left transTotalDiscAfterTax border-none bgb-transparent"
        value="{{$head->discount_after_tax}}" readonly>

        <li class="grid grid-cols-2">
            <p>{{__('app.TOTAL')}}:</p>
            <input type="number" name="transTotal" id="transTotal" class="text-left transTotal border-none bgb-transparent"
            value="{{$head->total}}" readonly>
        </li>
    </ul>
</div>

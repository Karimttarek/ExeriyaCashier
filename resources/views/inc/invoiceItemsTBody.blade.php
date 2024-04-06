@foreach ($invoicedetails as $item)
    <tr id="{{$item->item_uuid.$item->id}}" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
        <input type='hidden' name='items[{{ $item->number}}][number]' class='item text-center' id='num' value='{{ $item->number}}'>
        <input type='hidden' name='items[{{ $item->number}}][uuid]' class='item text-center' checked style='background: transparent ; border:none' value='{{$item->item_uuid}}' readonly>
        <td class="py-4" style="width: 200px">
            <div class='row'>
                <p><input type='text' class='col-12 text-center w-full' name='items[{{ $item->number }}][code_type]' style='background: transparent ; border:none' value='{{$item->code_type}}' readonly>
                    <input type='text' class='col-12 text-center w-full' name='items[{{ $item->number}}][item_code]' style='background: transparent ; border:none' value='{{$item->item_code}}' readonly></p>
            </div>
        </td>
        <td class="py-4" style="width: 300px">
            <input type='text' class="w-full text-center" name='items[{{ $item->number}}][item]' style='background: transparent ; border:none' value='{{$item->item}}' readonly>
            <input type='text' name='items[{{ $item->number }}][description]' class="w-full text-center" style='background: transparent ; border:none' value='{{$item->description}}' readonly>
        </td>
        <td class='rw-qty py-4' style='width: 100px'>
            <input type='number' class="w-full text-center" name='items[{{ $item->number }}][qty]' id='rwqty' style='background: transparent ; border:none' value='{{$item->qty}}' >
            <input type='text' style='background: transparent ; border:none' class="w-full text-center" name='items[{{ $item->number }}][unit_type]' value='{{$item->unit_type}}' >
        </td>
        <td class='rw-price py-4' style="width: 100px">
            <input type='number' class="w-full text-center" name='items[{{ $item->number }}][unitPrice]' id='rwprice' step='1' style='background: transparent ; border:none' value='{{$item->price}}'>
            <input type='text' class="w-full text-center" style='background: transparent ; border:none' name='items[{{ $item->number }}][currency]' value='{{$item->currency}}' >
        </td>
        <td class='rw-disc py-4' style="width: 100px">
            <input type='number' class="w-full text-center" name='items[{{ $item->number }}][disc]' id='rwdisc' step='1' style='background: transparent ; border:none' value='{{$item->discount}}'>
            <input type='number' class="w-full text-center" name='items[{{ $item->number }}][discPer]' id='rwdisc-per' style='background: transparent ; border:none' value='{{$item->discount_per}}'>
        </td>
        <td class='rw-net py-4' style="width: 120px">
            <input type='number' class="w-full text-center" name='items[{{ $item->number }}][net]' id='rwnet' step='1' style='background: transparent ; border:none' value='{{$item->net}}'>
        </td>
        <td class='rw-tax py-4' style="width: 100px">
            <input type='number' class="w-full text-center w-full" name='items[{{ $item->number }}][tax]' id='rwtax' step='1' style='background: transparent ; border:none' value='{{$item->tax}}'>
            <input type='number' class="w-full text-center w-full" name='items[{{ $item->number }}][taxPer]'id='rwtax-per' style='background: transparent ; border:none' value='{{$item->tax_per }}'>
            {{-- @if (!empty($item->tax_type)) --}}
            @forelse (explode(',',$item->tax_type) as $key => $taxs)
                <input type='hidden' name='items[{{ $item->number }}][taxable][0][tax_type][]' class="w-full" value='{{$taxs}}'>
                <input type='hidden' name='items[{{ $item->number }}][taxable][0][tax_sub_type][]' class="w-full" value='{{explode(',' ,$item->tax_sub_type)[$key]}}'>
                <input type='hidden' id="txVal" name='items[{{ $item->number }}][taxable][0][taxvalue][]' class="w-full" value='{{explode(',' ,$item->taxvalue)[$key]}}'>
                <input type='hidden' name='items[{{ $item->number }}][taxable][0][taxPervalue][]' class="w-full" value='{{explode(',' ,$item->taxPervalue)[$key]}}'>
            @empty
                <p></p>
            @endforelse
            {{-- @endif --}}
        </td>
        <input type="hidden" name="items[{{ $item->number }}][totalSales]" class="rw-totalSales" id="rw-totalSales"  value="{{$item->total_sales}}">

        <input type="hidden" name="items[{{ $item->number }}][discountAfterTax]" class="rw-discountAfterTax text-center" id="rw-discountAfterTax"  value="{{$item->discount_after_tax}}">
        <input type='hidden' name='items[{{ $item->number }}][tax_table]' class='rw-tax-table text-center' id='rw-tax-table' style='background: transparent ; border:none' value='{{$item->tax_table}}'>
        <input type='hidden' name='items[{{ $item->number }}][tax_table_per]' class='rw-tax-table_per text-center' id='rw-tax-table_per' style='background: transparent ; border:none' value='{{$item->tax_table_per}}'>
        <td class='rw-total py-4'  style="width: 120px">
            <input type='number' name='items[{{ $item->number }}][total]' class="rwtotal text-center w-full" id='rwtotal' style='background: transparent ; border:none' value="{{$item->total}}" readonly>
        </td>
        <td class="hidden-print py-4"  style="width: 40px">
            <a href='#' class="w-full">
                <svg class='trash' xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash' viewBox='0 0 16 16'> <path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z'/> <path fill-rule='evenodd' d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z'/> </svg>
            </a>
        </td>
    </tr>
@endforeach

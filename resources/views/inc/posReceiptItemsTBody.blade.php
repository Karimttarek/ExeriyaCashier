@foreach ($invoicedetails as $item)
<tr id="{{$item->item_uuid.$item->id}}" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
    <input type='hidden' name='items[{{ $item->number}}][number]' class='item text-center' id='num' value='{{ $item->number }}'>
    <input type='hidden' name='items[{{ $item->number}}][uuid]' class='item text-center' checked style='background: transparent ; border:none' value='{{$item->item_uuid}}' readonly>
    <input type="hidden" name="items[{{ $item->number}}][item_code]" class="item" value="{{ $item->item_code }}">

    <td style="width: 300px">
        <input type="text" name="items[{{ $item->number}}][item]'" class="text-center w-full" style="background: transparent ; border:none" value="{{$item->item}}" readonly>
    </td>
    <td class="rw-type" style="width: 100px">
        <input type="text" class="text-center w-full" style="background: transparent ; border:none" name="items[0][unit_type]" value="{{$item->unit_type}}" readonly>
    </td>
    <td class="rw-qty" style="width: 100px">
        <input type="number" name="items[{{ $item->number}}][qty]" class="text-center w-full" id="rwqty" style="background: transparent ; border:none" value="{{$item->qty}}">
    </td>
    <td class="rw-price" style="width: 100px">
        <input type="number" name="items[{{ $item->number}}][unitPrice]" class="text-center w-full" id="rwprice" step="1" style="background: transparent ; border:none" value="{{$item->price}}">
    </td>
    <td class="rw-net" style="width: 120px">
        <input type="number" name="items[{{ $item->number}}][net]" class="text-center w-full" id="rwnet" step="1" style="background: transparent ; border:none" value="{{$item->net}}" readonly>
    </td>
    <td class="rw-disc" style="width: 100px">
    <input type="number" name="items[{{ $item->number}}][disc]" class="text-center w-full" id="rwdisc" step="1" style="background: transparent ; border:none" value="{{$item->discount}}">
    </td>
    <td class="rw-total" style="width: 120px">
        <input type="number" class="text-center w-full" name="items[{{ $item->number}}][total]" id="rwtotal" style="background: transparent ; border:none" value="{{$item->total}}" readonly>
    </td>
    <td class=" h-print" style="width: 40px">
        <a href="#">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="trash text-center w-full bi bi-trash" viewBox="0 0 16 16">
                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"></path>
                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"></path>
            </svg>
        </a>
    </td>
</tr>
@endforeach

@forelse ($invoices as $invoice)
    <tr>
        <td class="h-print"><input type="checkbox" name="item[]" class="item" value="{{$invoice->uuid}}"></td>
        <td><a href="{{route('Pur.edit',$invoice->uuid)}}"title ="Edit Invoice"> {{$invoice->internal_id}} </a> </td>
        <td><a href="{{route('Pur.edit',$invoice->uuid)}}"title ="Edit Invoice"> {{date('Y/m/d' ,strtotime($invoice->invoice_date))}} </a> </td>
        <td>{{$invoice->issuer_name}}</td>
        <td>{{number_format($invoice->total_discount ,5)}}</td>
        <td>{{number_format($invoice->total_tax ,5)}}</td>
        <td>{{number_format($invoice->total ,5)}}</td>
        <td>{{$invoice->items_count}}</td>
        <td><a href="{{route('Pur.print',$invoice->uuid)}}"><i class="fa fa-print"></i></a></td>
    </tr>
@empty
    <tr> <td colspan="10">{{__('app.NODATAAVAILABE')}}</td></tr>
@endforelse

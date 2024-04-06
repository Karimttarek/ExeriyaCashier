
@forelse ($invoices as $invoice)
    @if ($invoice->status == 'Canceled')
        <tr class="text-muted">
    @else
    <tr>
    @endif
    <td class="h-print"><input type="checkbox" name="item[]" class="item" value="{{$invoice->uuid}}"></td>
    <td><a href="{{route('Sales.edit',$invoice->uuid)}}" title ="Edit Invoice"> {{$invoice->internal_id}} </a></td>
    <td><a href="{{route('Sales.edit',$invoice->uuid)}}" title ="Edit Invoice">{{date('Y/m/d' ,strtotime($invoice->invoice_date))}} </a></td>
    <td>{{$invoice->customer_name}}</td>
    <td>{{number_format($invoice->total_discount ,5)}}</td>
    <td>{{number_format($invoice->total_tax ,5)}}</td>
    <td>{{number_format($invoice->total ,5)}}</td>
    <td>{{$invoice->items_count}}</td>
    @if ($invoice->status == 'Pending')
    <td class="text-warning no-print">{{__('app.PENDING')}}</td>
    @elseif ($invoice->status == 'Invalid')
    <td class="text-danger no-print">{{__('app.INVALID')}}</td>
    @elseif ($invoice->status == 'Valid')
    <td class="text-success no-print">{{__('app.VALID')}}</td>
    @elseif ($invoice->status == 'Canceled')
    <td class="text-muted no-print">{{__('app.CANCELED')}}</td>
    @else
    <td class="text-gray-dark no-print">{{$invoice->status}}</td>
    @endif
    @if ($invoice->status != 'Valid' && empty($invoice->submission_uuid) && empty($invoice->document_uuid))
    <td class="h-print">
        @if (Auth::user()->role == 1)
{{--                                    <a href="{{route('submitDoc',$invoice->uuid)}}"><button type="button" class="btn btn-sm text-primary subDoc ">{{__('app.SEND')}}</button></a>--}}
            <button id="{{$invoice->uuid}}" class="btn btn-sm text-primary border border-primary eInvoice ">send</button>
        @endif
    </td>
    @elseif($invoice->status == 'Valid' && !empty($invoice->submission_uuid) && !empty($invoice->document_uuid) )
    @if (Auth::user()->role == 1)
        @if (date('Y/m/d' ,strtotime($invoice->invoice_date)) >= date("Y/m/d", strtotime(Carbon\Carbon::now('Africa/Cairo')->subDays(3),strtotime(now()))))
        <td class="text-success">
            <button type="button" class="btn btn-sm text-danger reject-doc" value="{{$invoice->document_uuid}}"
                    data-route ="{{route('eSalesInvoices.cancel', $invoice->document_uuid)}}"
                    data-id="{{__('app.REJECTIONDOCMESSAGE') .$invoice->document_uuid . ' !'}}" onclick="
                event.preventDefault();
                $('#cancel #cancelation-form').attr('action' , $(this).attr('data-route')) ;$('#cancel').modal('show');">
                {{__('app.CANCEL')}}</button>
        </td>
        @else
        <td><p class="badge badge-secondary text-wrap">{{__('app.CANNOTDECLINE')}}</p></td>
        @endif

    @else
        <td></td>
    @endif
    @else
    <td><p class="badge badge-secondary  text-wrap">{{__('app.CANNOTDECLINE')}}</p></td>
    @endif
    <td><a href="{{route('Sales.print',$invoice->uuid)}}"><i class="fa fa-print"></i></a></td>
</tr>
@empty
<tr> <td colspan="10">{{__('app.NODATAAVAILABE')}}</td></tr>
@endforelse


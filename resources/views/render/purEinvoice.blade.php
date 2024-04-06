@if(isset($response))
    @forelse ($response['result'] as $invoice)
        <tr>
            <td><a href="{{$invoice['publicUrl']}}" target="_blank" title ="Edit Invoice"> {{$invoice['internalId']}} </a> </td>
            <td><a href="{{$invoice['publicUrl']}}" target="_blank" title ="Edit Invoice"> {{date('Y/m/d' ,strtotime($invoice['dateTimeIssued']))}} </a> </td>
            <td>{{$invoice['issuerName']}}</td>
            <td>{{number_format($invoice['totalDiscount'] ,5)}}</td>
            <td>{{number_format($invoice['netAmount'] ,5)}}</td>
            <td>{{number_format($invoice['total'] ,5)}}</td>
            <td>
                @if($invoice['status'] === 'Valid')
                    <p class="text-success" style="padding: 0 !important;margin: 0!important">{{__('app.VALID')}}</p>
                @elseif($invoice['status'] === 'Invalid')
                    <p class="text-danger" style="padding: 0 !important;margin: 0!important">{{__('app.INVALID')}}</p>
                @elseif($invoice['status'] === 'Cancelled')
                    <p class="text-muted" style="padding: 0 !important;margin: 0!important">{{__('app.CANCELD')}}</p>
                @else
                    <p style="padding: 0 !important;margin: 0!important">{{$invoice['status']}}</p>
                @endif
            </td>
            <td>
                @if($invoice['status'] === 'Valid')
                    <button type="button" class="btn btn-sm text-danger reject-doc" value="{{$invoice['uuid']}}"
                            data-route ="{{route('ePurInvoices.reject', $invoice['uuid'])}}"
                            data-id="{{__('app.REJECTIONDOCMESSAGE') .$invoice['internalId'] . ' !'}}" onclick="
                                            event.preventDefault();
                                            $('#reject #rejection-form').attr('action' , $(this).attr('data-route')) ;$('#reject').modal('show');">
                        {{__('app.REJECT')}}</button>
                @else
                @endif
            </td>
            <td><a href="{{substr_replace($invoice['publicUrl'] ,  '/print' ,28 ,-200)}}" target="blank"><i class="fa fa-print"></i></a></td>
        </tr>
    @empty
        <tr> <td colspan="10">{{__('app.NODATAAVAILABE')}}</td></tr>
    @endforelse
@else
    <tr> <td colspan="10">{{__('app.NODATAAVAILABE')}}</td></tr>
@endif

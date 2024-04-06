<table class="table table-hover table-sm border table-striped">
    <thead>
    <tr>
        <th scope="col">{{__('app.DATE')}}</th>
        <th scope="col">{{__('app.INVOICEID')}}</th>
        <th scope="col">{{__('app.ITEM')}}</th>
        <th scope="col">{{__('app.STATEMENT')}}</th>
        <th scope="col">{{__('app.OUT')}}</th>
        <th scope="col">{{__('app.IN')}}</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($data as $item)
        <tr>
            <td>{{date('Y/m/d' , strtotime($item->invoice_date))}}</td>
            <td> <a href=@if($item->invoice_type == 2) {{route('Sales.edit',$item->uuid)}} @else {{route('Pur.edit',$item->uuid)}} @endif>{{ $item->invoice_number }}</a></td>
            <td>{{$item->child_name}}</td>
            @if($item->invoice_type == 1)
                <td>{{__('app.PINV')}}</td>
            @elseif($item->invoice_type == 2)
                <td>{{__('app.SINV')}}</td>
            @elseif($item->invoice_type == 3)
                <td>{{__('app.PRINV')}}</td>
            @else
                <td>{{__('app.SRINV')}}</td>
            @endif
            <td>{{number_format($item->OUTCOME,2)}}</td>
            <td>{{number_format($item->INCOME,2)}}</td>
        </tr>
    @empty
        <tr> <td colspan="10">{{__('app.NODATAAVAILABE')}}</td></tr>
    @endforelse
    @if($balance != 'null')
        <tr class="text-bold bg-head">
            <td colspan="4"><h5>{{__('app.STOCKBALANCE')}}</h5></td>
            <td colspan="2" class="text-center"><h5>{{$balance}}</h5></td>
        </tr>
    @endif
    </tbody>
</table>

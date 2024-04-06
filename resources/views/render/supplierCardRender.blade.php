@forelse ($data as $rec)
    <tr>
        <td>{{date('Y/m/d', strtotime($rec->date))}} </td>
        <td>{{$rec->statement}}</td>
        <td>{{$rec->supplier. $rec->receiver_name}}</td>
        <td>{{number_format($rec->credit ,3)}}</td>
        <td>{{number_format($rec->debit ,3)}}</td>
    </tr>
    @empty
<tr> <td colspan="10">{{__('app.NODATAAVAILABE')}}</td></tr>
@endforelse
<tr class="text-bold bg-head">
    <td colspan="3"><h5>{{__('app.BALANCE')}}</h5></td>
    <td colspan="2"><h5>{{number_format($balance , 5)}}</h5></td>
</tr>

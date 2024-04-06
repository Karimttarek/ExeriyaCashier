@forelse ($data as $rec)
    <tr>
        <td>{{date('Y/m/d', strtotime($rec->receipt_date))}} </td>
        <td>{{$rec->statement}}</td>
        <td>{{$rec->exp_name}}</td>
        <td>{{number_format($rec->value , 3)}}</td>
    </tr>
@empty
    <tr> <td colspan="10">{{__('app.NODATAAVAILABE')}}</td></tr>
@endforelse
<tr class="text-bold bg-head">
    <td colspan="3"><h5>{{__('app.BALANCE')}}</h5></td>
    <td colspan="1"><h5>{{number_format($balance , 5)}}</h5></td>
</tr>

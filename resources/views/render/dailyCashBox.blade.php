@forelse ($data as $rec)
<tr>
    <td>{{$rec->no}}</td>
    <td>{{date('Y/m/d', strtotime($rec->receipt_date))}}</td>
    <td>{{$rec->statement}}</td>
    <td>{{$rec->customer_name . $rec->supplier_name . $rec->exp_name . $rec->receiver_name}}</td>
    <td>{{number_format($rec->INCOME,3)}}</td>
    <td>{{number_format($rec->OUTCOME,3)}} </td>
  </tr>
  @empty
  <tr> <td colspan="10">{{__('app.NODATAAVAILABE')}}</td></tr>
  @endforelse
<tr class="text-bold bg-head">
    <td colspan="4"><h5>{{__('app.CASHBALANCE')}}</h5></td>
    <td colspan="2"><h5>{{number_format($balance , 3)}}</h5></td>
</tr>

@forelse ($data as $item)
<tr>
    @if(LaravelLocalization::getCurrentLocale() == 'en')
        <td>{{$item->name}}</td>
    @else
        <td>{{$item->name_ar}}</td>
    @endif
        <td>{{$item->COUNT}}</td>
        <td>{{number_format($item->TOTAL_SALES / $item->COUNT,3)}}</td>
        <td>{{number_format($item->TOTAL_SALES,3)}}</td>
        <td>{{number_format($item->DISCOUNT,3)}}</td>
        <td>{{number_format($item->TAX,3)}}</td>
        <td>{{number_format($item->VALUE,3)}}</td>
  </tr>
  @empty
  <tr> <td colspan="10">{{__('app.NODATAAVAILABE')}}</td></tr>
  @endforelse

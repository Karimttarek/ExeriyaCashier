@forelse ($data as $item)
<tr>
    @if(LaravelLocalization::getCurrentLocale() == 'en')
        <td>{{$item->name}}</td>
    @else
        <td>{{$item->name_ar}}</td>
    @endif
    <td>{{$item->COUNT}}</td>
    <td>{{$item->TOTAL_SALES / $item->COUNT}}</td>
    <td>{{$item->TOTAL_SALES}}</td>
    <td>{{$item->DISCOUNT}}</td>
    <td>{{$item->TAX}}</td>
    <td>{{$item->VALUE}}</td>
  </tr>
  @empty
  <tr> <td colspan="10">{{__('app.NODATAAVAILABE')}}</td></tr>
  @endforelse

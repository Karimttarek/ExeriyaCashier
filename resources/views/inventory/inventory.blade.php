@extends('layouts.reports')
@section('content')

<div class="form-group col-12 p-1 text-muted no-print" style="background: #dff0d8">
    <p>{{__('messages.INVENTORYMESSAGE')}}</p>
</div>

<div class="m-5">
    <div class="row">
        <div class="container no-print m-b-2">
            <button type="button" class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> {{__('app.PRINT')}}</button>
        </div>
        <div class="conainer">
            <table class="table table-hover table-sm border text-center table-striped">
                <thead>
                  <tr class="bg-head">
                    <th scope="col">{{__('app.ITEMNAME')}}</th>
                      <th scope="col">{{__('app.STOCK')}}</th>
                  </tr>
                </thead>
                <tbody>
                    @forelse ($data as $item)
                    <tr>
                        @if(LaravelLocalization::getCurrentLocale() == 'en')
                            <td>{{$item->name}}</td>
                        @else
                            <td>{{$item->name_ar}}</td>
                        @endif
                        <td>{{$item->INCOME - $item->OUTCOME}}</td>
                      </tr>
                      @empty
                      <tr> <td colspan="10">{{__('app.NODATAAVAILABE')}}</td></tr>
                      @endforelse

                </tbody>
              </table>
        </div>
    </div>
</div>
<div class="d-flex justify-content-center no-print">
    {!!  $data -> links("pagination::bootstrap-4") !!}
</div>
@endsection

@push('script')
<script>
</script>
@endpush


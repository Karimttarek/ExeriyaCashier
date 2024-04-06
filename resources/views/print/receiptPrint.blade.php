@extends('layouts.print')
@section('content')
    @foreach($receipt as $rec)
        <div class="container-xl m-t-200">
            <div class="form-group row text-center text-bold m-t-10" style="border:1px solid #000">
                    <h4 class="col-12">{{__('app.VOUCHERRECEIPT')}}</h4>
                <div class="bordered">
                    <!-- POUNDS -->
                    <div class="form-group row text-center">
                        <div class="col-1"></div>
                        <div class="form-group col-md-2">
                            <p  class="col-form-label">{{__('app.POUND')}}</p>
                            <input type="text"  class="form-control" value="{{explode(".", number_format($rec->value ,5))[0]}}">
                        </div>
                        <div class="form-group col-md-2">
                            <p for="purchase_price" class="col-form-label">{{__('app.PENNY')}}</p>
                            <input type="text" class="form-control" value="{{explode(".", number_format($rec->value ,5))[1]}}">
                        </div>
                    </div>
                    <!-- DATE -->
                    <div class="form-group row text-center">
                        <p for="code_type" class="col-sm-2 col-form-label">{{__('app.DATE')}}</p>:
                        <div class="col-sm-3 text-left">
                            <p class="col-sm-12 col-form-label border-bottom">{{date('Y/m/d', strtotime($rec->receipt_date))}}</p>
                        </div>
                        <p for="code_type" class="col-sm-2 col-form-label">{{__('app.RECEIPTDATE')}}</p>:
                        <div class="col-sm-4 text-left">
                            <p class="col-sm-12 col-form-label border-bottom">
                                {{Stichoza\GoogleTranslate\GoogleTranslate::trans(date('F d Y', strtotime($rec->receipt_date)),LaravelLocalization::getCurrentLocale())}}
                            </p>
                        </div>
                    </div>
                    <!-- CUSTOMER -->
                    <div class="form-group row text-center">
                        <p for="code_type" class="col-sm-2 col-form-label">{{__('app.RECEIVEDFROM')}}</p>:
                        <div class="col-sm-9 text-left">
                            <p class="col-sm-12 col-form-label border-bottom">{{$rec->customer_name . $rec->receiver_name . $rec->supplier_name}}</p>
                        </div>
                    </div>
                    <!-- BANK -->
                    <div class="form-group row text-center">
                        <p for="code_type" class="col-sm-2 col-form-label">{{__('app.CASH') . '/' . __('app.CHECKNO')}}</p>:
                        <div class="col-sm-3 text-left">
                            <p class="col-sm-12 col-form-label border-bottom">{{$rec->check_no}}</p>
                        </div>
                        <p for="code_type" class="col-sm-2 col-form-label">{{__('app.BANKNAME')}}</p>:
                        <div class="col-sm-4 text-left">
                            <p class="col-sm-12 col-form-label border-bottom">{{$rec->bank_name}}</p>
                        </div>
                    </div>
                    <!-- AMOUNT -->
                    <div class="form-group row text-center">
                        <p for="code_type" class="col-sm-2 col-form-label">{{__('app.AMOUNT')}}</p>:
                        <div class="col-sm-9 text-left">
                            <p class="col-sm-12 col-form-label border-bottom">{{number_format($rec->value ,5)}} >> {{
                                !empty($rec->value_text) ? Stichoza\GoogleTranslate\GoogleTranslate::trans($rec->value_text ,LaravelLocalization::getCurrentLocale())
                                            : $rec->value_text
                                }}
                            </p>
                        </div>
                    </div>
                    <!-- STATEMENT -->
                    <div class="form-group row text-center mb-5">
                        <p for="code_type" class="col-sm-2 col-form-label">{{__('app.THATFOR')}}</p>:
                        <div class="col-sm-9 text-left">
                            <p class="col-sm-12 col-form-label border-bottom">{{$rec->statement}}</p>
                        </div>
                    </div>
                    <!-- SIGNATURE -->
                    <div class="form-group row text-center">
                        <p for="code_type" class="col-sm-2 col-form-label">{{__('app.ACCOUNTANT')}}</p>:
                        <div class="col-sm-3 text-left">
                            <p class="col-sm-12 col-form-label border-bottom"></p>
                        </div>
                        <p for="code_type" class="col-sm-2 col-form-label">{{__('app.RECEIVER')}}</p>:
                        <div class="col-sm-4 text-left">
                            <p class="col-sm-12 col-form-label border-bottom"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
@push('script')
<script>
    window.addEventListener("load", window.print());
</script>
@endpush

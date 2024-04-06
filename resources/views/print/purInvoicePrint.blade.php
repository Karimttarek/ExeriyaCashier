<x-print-layout>
@foreach($invoicehead as $h)
    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-12">
                <h2 class="page-header">
                    @foreach($profile as $p)
                            {{$p->company_name}}
                            <p class=""><b>{{__('app.TAXREGCODE')}} :</b> {{$p->tax_rCode}}<br></p>
                    @endforeach
                </h2>
            </div>
            <div class="col-12 text-center mb-1">
                <b class="page-header">
                    @if($h->invoice_type == 1)
                        <div class="text-black">
                            {{__('app.PURINV')}}<br>
                            {{$h->internal_id}}
                        </div>
                    @else
                        {{__('app.PURRETURNINV')}}<br>
                        {{$h->internal_id}}
                    @endif
                </b>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info mb-1">
            <!-- /.col -->
            <div class="col-sm-6 invoice-col">
                <b>{{__('app.ORDERID')}} :</b> {{$h->id}}<br>
                <b>{{__('app.INVOICEDATE')}} :</b> {{date('Y/m/d' ,strtotime($h->invoice_date))}}<br>
                <b>{{__('app.SUPPLIERNAME')}} :</b> {{$h->issuer_name}}<br>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- Table row -->
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-striped table-sm table-bordered">
                    <thead>
                    <tr>
                        <th>{{__('app.ITEMNAME')}}</th>
                        <th>{{__('app.DESCRIPTION')}}</th>
                        <th>{{__('app.QTY')}}</th>
                        <th>{{__('app.UNITPRICE')}}</th>
                        <th>{{__('app.DISCOUNT')}}</th>
                        <th>{{__('app.NET')}}</th>
                        <th>{{__('app.TAX')}}</th>
                        {{--                            <th>{{__('app.DISCOUNTAFTERTAX')}}</th>--}}
                        <th>{{__('app.TOTAL')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($invoicedetails as $item)
                        <tr>
                            @if(LaravelLocalization::getCurrentLocale() == 'en')
                                <td>{{$item->name}}</td>
                                <td>{{$item->description}}</td>
                            @else
                                <td>{{$item->name_ar}}</td>
                                <td>{{$item->description_ar}}</td>
                            @endif
                                <td>{{number_format($item->qty,2)}}</td>
                                <td>{{number_format( ($item->price + ($item->tax_table / $item->qty) ) ,3)}}</td>
                                <td>{{number_format($item->discount,3)}}</td>
                                <td>{{number_format($item->net,3)}}</td>
                                <td>{{number_format($item->tax,3)}}</td>
                                {{--                                <td>{{number_format($item->discount_after_tax,3)}}</td>--}}
                                <td>{{number_format($item->total,3)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
            <!-- accepted payments column -->
            <div class="col-6">
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered">
                        <tr>
                            <th style="width:50%">{{__('app.NET')}}</th>
                            <td>{{number_format($h->total_net,3)}}</td>
                        </tr>
                        <tr>
                            <th>{{__('app.INVOICEDISCOUNT')}}</th>
                            <td>{{number_format($h->total_discount,3)}}</td>
                        </tr>
                        <tr>
                            <th>{{__('app.INVOICETAX')}}</th>
                            <td>{{number_format($h->total_tax,3)}}</td>
                        </tr>
                        <tr>
                            <th>{{__('app.DISCOUNTAFTERTAX')}}</th>
                            <td>{{number_format($h->discount_after_tax,3)}}</td>
                        </tr>
                        <tr>
                            <th>{{__('app.TOTAL')}}</th>
                            <td>{{number_format($h->total,3)}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endforeach
<footer class="text-center" style="position: absolute;bottom: 0;width: 100%">
    <!-- Copyright -->
    <div class="text-center bg-light">
        @foreach($profile as $p)
            <p>{{__('app.ADDRESS'). ' : ' . $p->street  . ' / ' . __('app.PHONE') . ' : ' . $p->mobile}}</p>
        @endforeach
            <hr>
        <p>Copyright &copy; 2023 Exeriya. All rights reserved.</p>
    </div>
    <!-- Copyright -->
</footer>
</x-print-layout>

<x-print-layout>
    @foreach($invoicehead as $h)
        <section class="invoice">
            <!-- title row -->
            {{-- <div class="col-12 text-center mb-6">
                <h4 class="page-header">
                    @if($h->invoice_type == 2)
                        {{__('app.SALESINV')}}<br>
                        {{$h->internal_id}}
                    @else
                        {{__('app.SALESRETURNINV')}}<br>
                        {{$h->internal_id}}
                    @endif
                </h4>
            </div> --}}
            <div class="row">
                <!-- /.col -->
                <div class="row col-12 mb-6">
                    <div class="col-6 text-center d-flex justify-content-start mt-auto">
                        <h2 class="page-header align-middle">
                            {{$company[0]->company_name}}<br>
                            {{$company[0]->tax_rCode}}
                        </h2>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        @if(!empty($company[0]->img))
                            <img src="{{ asset($company[0]->img) }}" alt="{{ config('app.name')}}" class="rounded-pill" style="max-height: 100px">
                        @endif
                    </div>
                </div>
                <hr class="border-2 border-topy">
                <!-- /.col -->
                <div class="col-12 text-center mb-6">
                    <h4 class="page-header">
                        @if($h->invoice_type == 2)
                            {{__('app.SALESINV')}}<br>
                            {{$h->internal_id}}
                        @else
                            {{__('app.SALESRETURNINV')}}<br>
                            {{$h->internal_id}}
                        @endif
                    </h4>
                </div>
            </div>
            <!-- info row -->
            <div class="row invoice-info mb-6">
                <!-- /.col -->
                <div class="col-sm-6 invoice-col">
                    <div class="mb-2"><b class="text-lg d-inline">{{__('app.INVOICEID')}} :</b> <span class="d-inline text-bold" dir="ltr">{{$h->internal_id}}</span></div>
                    <div class="mb-2"><b class="text-lg d-inline">{{__('app.INVOICEDATE')}} :</b> <span class="d-inline text-bold">{{date('Y/m/d' ,strtotime($h->invoice_date))}}</span></div>
                    <div class="mb-2"><b class="text-lg d-inline">{{__('app.CUSTOMERNAME')}} :</b> <span class="d-inline text-bold">{{$h->customer_name}}</span></div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <!-- Table row -->
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-striped table-sm table-bordered border-2">
                        <thead>
                        <tr>
                            <th class="text-base text-center align-middle">{{__('app.ITEMNAME')}}</th>
                            <th class="text-base text-center align-middle">{{__('app.DESCRIPTION')}}</th>
                            <th class="text-base text-center align-middle">{{__('app.QTY')}}</th>
                            <th class="text-base text-center align-middle">{{__('app.UNITPRICE')}}</th>
                            {{-- <th class="text-base text-center align-middle">{{__('app.DISCOUNT')}}</th> --}}
                            {{-- <th class="text-base text-center align-middle">{{__('app.NET')}}</th> --}}
                            {{-- <th class="text-base text-center align-middle">{{__('app.TAX')}}</th> --}}
                            <th class="text-base text-center align-middle">{{__('app.TOTAL')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($invoicedetails as $item)
                            <tr>
                                @if(LaravelLocalization::getCurrentLocale() == 'en')
                                    <td class="text-base text-center align-middle">{{$item->name}}</td>
                                    <td class="text-base text-center align-middle">{{$item->description}}</td>
                                @else
                                    <td class="text-base text-center align-middle">{{$item->name_ar}}</td>
                                    <td class="text-base text-center align-middle">{{$item->description_ar}}</td>
                                @endif
                                <td class="text-base text-center align-middle">{{number_format($item->qty,2)}}</td>
                                <td class="text-base text-center align-middle">{{number_format( ($item->price + ($item->tax_table / $item->qty) ) ,3)}}</td>
                                {{-- <td class="text-base text-center align-middle">{{number_format($item->discount,3)}}</td> --}}
                                {{-- <td class="text-base text-center align-middle">{{number_format($item->net,3)}}</td> --}}
                                {{-- <td class="text-base text-center align-middle">{{number_format($item->tax,3)}}</td> --}}
                                <td class="text-base text-center align-middle">{{number_format($item->total,3)}}</td>
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
                                <th class="text-lg">{{__('app.NET')}}</th>
                                <td class="text-base">{{number_format($h->total_net,3)}}</td>
                            </tr>
                            <tr>
                                <th class="text-lg">{{__('app.INVOICEDISCOUNT')}}</th>
                                <td class="text-base">{{number_format($h->total_discount,3)}}</td>
                            </tr>
                            {{-- <tr>
                                <th class="text-lg">{{__('app.INVOICETAX')}}</th>
                                <td class="text-base">{{number_format($h->total_tax,3)}}</td>
                            </tr> --}}
                            {{-- <tr>
                                <th class="text-lg">{{__('app.DISCOUNTAFTERTAX')}}</th>
                                <td class="text-base">{{number_format($h->discount_after_tax,3)}}</td>
                            </tr> --}}
                            <tr>
                                <th class="text-lg">{{__('app.TOTAL')}}</th>
                                <td class="text-base">{{number_format($h->total,3)}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
    @endforeach
    <footer class="footer mt-auto rounded">
        <div class="container text-center py-3 mb-2 bg-light fs-4">
            <span class="text-muted">{{__('app.ADDRESS'). ' : ' . $profile[0]->street  . ' / ' . __('app.PHONE') . ' : ' . $profile[0]->mobile}}</span>
        </div>
        <div class="container text-center text-sm">
            <span class="text-muted">Copyright &copy; 2023 <span class="text-decoration-underline">Exeriya</span>. All rights reserved.</span>
        </div>
    </footer>
</x-print-layout>

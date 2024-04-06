@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.CONTENT -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <!-- Main content -->
                    <div class="invoice p-3 mb-3">
                        <!-- Table row -->
                        <div class="row">
                            <!-- /.row -->
                            <h3 class=" text-danger mb-3">{{ __('app.NEWUPDATES')}}
                                <span class="text-black">{{Stichoza\GoogleTranslate\GoogleTranslate::trans(date('F d Y', strtotime('7/15/2023')),LaravelLocalization::getCurrentLocale())}}</span>
                            </h3>
                            <ul class="pl-5">
                                <li><h4>{{__('app.BUGFIXED')}}</h4> </li>
                                <ul>
                                    <li><p><span class="text-bold">“ {{__('app.FIXED')}} “</span>   {{__('app.Submitting a new invoice, some items not stored')}}</p></li>
                                    <li><p><span class="text-bold">“ {{__('app.FIXED')}} “</span>  {{__('app.Editing an existing invoice, some items not affected by updating the invoice')}}</p></li>
                                    <li><p><span class="text-bold">“ {{__('app.FIXED')}} “</span>  {{__('app.Submitting or editing an existing invoice, the tax is repeated on some products')}}</p></li>
                                </ul>
                            </ul>

                            <p class="pl-4">{{__('app.Applying this updates by deleting the browser cache')}}</p>
                            <ol class="pl-5">
                                <li>{{__('app.On your computer, open your browser')}}</li>
                                <li>{{__('app.At the top right, click More More')}}</li>
                                <li>{{__('app.Click More tools and then Clear browsing data')}}</li>
                                <li>{{__('app.At the top, choose a time range. To delete everything, select All time')}}</li>
                                <li>{{__('app.Next to "Cookies and other site data" and "Cached images and files," check the boxes')}}</li>
                                <li>{{__('app.Click Clear data')}}</li>
                            </ol>

                            <p class="pl-4">{{__('app.Or by opening your application, and click on "CTRL + F5"')}}</p>
                        </div>
                        <!-- /.invoice -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
    </section>
    <!-- /.content -->
    <!-- MODALS -->
    <!-- Modal -->
@endsection

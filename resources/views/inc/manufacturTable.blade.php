<!-- Product Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <form>
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex">
                        <h5 class="modal-title" id="exampleModalCenterTitle">{{__('app.PRODUCT')}}</h5>
                    </div>
                    <div class=" @if(LaravelLocalization::getCurrentLocale() =='en') ml-auto  @else mr-auto-navbav @endif ">
                        <i class="fas fa-times" style="cursor: pointer;"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <!-- ITEM CODE -->
                    <div class="form-group row">
                        <p for="name" class="col-sm-2 col-form-label">{{__('app.ITEMNAME')}}</p>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="product-filter" placeholder="{{__('app.SEARCHBYITEMNAMEORCODE')}}">
                        </div>
                    </div>
                    <!-- Product search -->
                    <input type="hidden" id="product-uuid">
                    <div class="form-group row">
                        {{-- <p for="name" class="col-sm-2 col-form-label">Filter</p> --}}
                        <div class="col-sm-12">
                            <select class="form-select" style="width: 100%;" id="product-search">
                                <option selected disabled>{{__('app.SELECTPRODUCT')}}</option>
                                @foreach ($products as $item)
                                    <option value="{{$item->name}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- ITEM CODE -->
                    <div class="form-group row">
                        <p for="name" class="col-sm-2 col-form-label">{{__('app.CODETYPE')}}</p>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="product-codeType" style="border: none" readonly>
                        </div>
                        <p for="name" class="col-sm-2 col-form-label">{{__('app.ITEMCODE')}}</p>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="product-itemCode" style="border: none" readonly>
                        </div>
                    </div>
                    <!-- ITEM NAME -->
                    <div class="form-group row">
                        <p for="name" class="col-sm-2 col-form-label">{{__('app.ITEMNAME')}}</p>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="product-name" style="border: none" readonly>
                        </div>
                    </div>
                    <!-- ITEM DESCRIPTION -->
                    <div class="form-group row">
                        <p for="name" class="col-sm-2 col-form-label">{{__('app.DESCRIPTION')}}</p>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="product-description" style="border: none" readonly>
                        </div>
                    </div>
                    <!-- Product QTY -->
                    <div class="form-group row">
                        <p for="name" class="col-sm-2 col-form-label">{{__('app.QTY')}}</p>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="product-qty" min="1">
                        </div>
                    </div>
                    <!-- Product PRICE -->
                    <div class="form-group row">
                        <p for="name" class="col-sm-2 col-form-label">{{__('app.UNITPRICE')}}</p>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="product-price" min="1">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex">
                        <p class="text-sm">{{__('app.PRODUCTAPPLYNOTE')}}</p>
                    </div>
                    <div class=" @if(LaravelLocalization::getCurrentLocale() =='en') ml-auto  @else mr-auto-navbav @endif ">
                        <input type="reset" class="btn btn-flat btn-flat btn-flat btn-flat btn-flat btn-flat btn-flat btn-flat btn-flat btn-flat btn-warning" id="reset" value="{{__('app.RESET')}}">
                        <input type="button" class="btn btn-flat btn-flat btn-flat btn-flat btn-flat btn-flat btn-flat btn-flat btn-flat btn-flat btn-info" id="apply" value="{{__('app.APPLY')}}">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

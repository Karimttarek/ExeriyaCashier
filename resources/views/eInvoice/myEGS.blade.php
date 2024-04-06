@extends('layouts.app')
@section('content')
<section class="content-header">
<div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-6">
        <h1>EGS Items</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
        <li class="breadcrumb-item active">EGS</li>
        </ol>
    </div>
    </div>
</div><!-- /.container-fluid -->
</section>
<!-- /.CONTENT -->
<section class="content">
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            @if (session('status'))
                <div class="callout callout-info">
                <h5><i class="fas fa-info"></i> Success:</h5>
                    {{session('status')}}
                </div>
            @endif
            @if (session('error'))
            <div class="callout callout-danger">
                <h5><i class="fas fa-info"></i> Error:</h5>
                    <pre style="color:red">{{session('error')}}</pre>
                </div>
            @endif
        <!-- Main content -->
        <div class="p-3 mb-3">
        <!-- Table row -->
            <div class="row">
                <table  class=" table table-striped table-hover table-bordered table-sm" id="product-table">
                    <thead>
                        <tr>
                            <th  title="Item Code">Item Code</th>
                            <th  title="Name">Name</th>
                            <th  title="Discount">Description</th>
                            <th  title="Tax">Active From</th>
                            <th  title="Purchase Price">Active To</th>
                            <th  title="Stock">Status</th>
                        </tr>
                    </thead>
                         @forelse ($items as $item)
                        <tr>
                            <td>{{$item['itemCode']}}</td>
                            <td>{{$item['codeNamePrimaryLang']}}</td>
                            <td>{{$item['descriptionPrimaryLang']}}</td>
                            <td>{{$item['activeFrom']}}</td>
                            <td>{{$item['activeTo']}}</td>
                            <td>{{$item['status']}}</td>
                         </tr>  
                    
                        @empty
                        <tr> <td colspan="99">No Products available</td></tr>

                        @endforelse 
                        
                    </tbody>

                    </table>
        <!-- /.row -->
        </div>
        <!-- /.invoice -->
        </div><!-- /.col -->
</div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->

<div class="d-flex justify-content-center">
    {{-- {!!  $items -> links("pagination::bootstrap-4") !!} --}}
</div>
@endsection

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{LaravelLocalization::getCurrentLocaleDirection()}}">
<head>
    <meta charset="UTF-8">
    <meta name=description content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{URL::asset('favicons/Exeriya-non-bg.png')}}">
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{URL::asset('dist/css/adminlte.min.css')}}">
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @if(LaravelLocalization::getCurrentLocale() == 'ar')
        <!-- CAIRO -->
        <link href='https://fonts.googleapis.com/css?family=Cairo' rel='stylesheet'>
        <style>
            *{font-family: Cairo; }
        </style>
    @endif
    <style>
        body {margin: 20px}
        @page { size: auto;  margin: 8mm; }
    </style>
</head>
<body>
    <!-- Heading -->
    <div class="mb-5 p-1 rounded" style="background: #eee !important;">
        <h1 class="text-center text-bold">{{ __('app.Item Profits Rep')  }}</h1>
    </div>
    <div class="mb-5">
        <div class="col-6 table-responsive text-lg">
            <table class="table-striped">
                <tr>
                    <th>{{ __('app.From Date') }} : </th>
                    <td>{{  date('Y-m-d h:m' , strtotime( array_key_exists('Date' ,$data[0]) ? $data[0]['Date'] : $data[0]['التاريخ']) ) }}</td>
                </tr>
                <tr>
                    <th>{{ __('app.To Date') }} : </th>
                    <td>{{  date('Y-m-d h:m' , strtotime( array_key_exists('Date' , $data[0]) ? $data[count($data) -1]['Date'] : $data[count($data) -1]['التاريخ'] )) }}</td>
                </tr>
            </table>
        </div>
    </div>
    <!-- Heading -->
    <table class="table table-bordered table-condensed table-striped text-center mt-6">
        @foreach($data as $row)
            @if ($loop->first)
                <tr>
                    @foreach($row as $key => $value)
                        <th class="text-center">{!! $key !!}</th>
                    @endforeach
                </tr>
            @endif
            <tr>
                @foreach($row as $key => $value)
                    @if(is_string($value) || is_numeric($value))
                        <td>{!! $value !!}</td>
                    @else
                        <td></td>
                    @endif
                @endforeach
            </tr>
        @endforeach

    </table>
    <!-- Footer -->
    <div class="row">
        <!-- accepted payments column -->
        <div class="col-6">
            <div class="table-responsive wrapper">
                <table class="table table-striped table-sm table-bordered text-center">
                    @foreach($row as $key => $value)
                        @if(is_numeric($value) && !str_contains($key,'ID' ) && !str_contains($key,'رقم' ) && !str_contains($key,'Registration' ) && !str_contains($key,'التسجيل' ) && !str_contains($key,'Statement' ) && !str_contains($key,'البيان' ) && !preg_match('/^[A-Za-z]+$/', $value))
                            <tr>
                                <th style="width:50%">{!! $key !!}</th>
                                <td>{!! number_format(intval(array_sum(array_column($data,$key))) , 5) !!}</td>
                            </tr>
                        @endif
                    @endforeach
                </table>
            </div>
        </div>
        <!-- /.col -->
    </div>
</body>
    <script>
        window.addEventListener("load", window.print());
    </script>
</html>

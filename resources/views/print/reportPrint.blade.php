<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{LaravelLocalization::getCurrentLocaleDirection()}}">
    <head>
        <title>{{ config('app.name', 'Exeriya') }}</title>
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
        <table class="table table-bordered table-condensed table-striped text-center">
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
{{--             <tfoot>--}}
{{--                <tr>--}}
{{--                    @foreach($row as $key => $value)--}}
{{--                        @if(is_numeric($value) && !str_contains($key,'ID' ) && !str_contains($key,'رقم' ) && !str_contains($key,'Registration' ) && !str_contains($key,'التسجيل' ))--}}
{{--                            <td>{!! number_format(floatval(array_sum(array_column($data,$key))) , 5) !!}</td>--}}
{{--                        @else--}}
{{--                            <td></td>--}}
{{--                        @endif--}}
{{--                    @endforeach--}}
{{--                </tr>--}}
{{--            </tfoot>--}}
        </table>
        <!-- Footer -->
        <div class="row">
            <!-- accepted payments column -->
            <div class="col-6">
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered">
                        @foreach($row as $key => $value)
                            @if(is_numeric($value) && !str_contains($key,'ID' ) && !str_contains($key,'رقم' ) && !str_contains($key,'Registration' ) && !str_contains($key,'التسجيل' ) && !str_contains($key,'Statement' ) && !str_contains($key,'البيان' ) && !preg_match('/^[A-Za-z]+$/', $value))
                                <tr>
                                    <th style="width:50%">{!! $key !!}</th>
                                    <td>{!! number_format(floatval(array_sum(array_column($data,$key))) , 5) !!}</td>
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

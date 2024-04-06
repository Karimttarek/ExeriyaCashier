@foreach ($cities as $city)
    <option value="{{$city->Desc_en}}">{{$city->Desc_en.' - '.$city->Desc_ar}}</option>
@endforeach

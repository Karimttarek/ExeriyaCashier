document.getElementById('MoreRows').onclick = function(){
    document.getElementById('InvoiceItems').classList.remove('hidden');
    $('#product-filter').focus().select();

}  //InvoiceItems
$(document).on('click' , '.fa-times' , function (){
    $('#exampleModalCenter').modal('hide');
});


function getItemStock(name ,url ,input){

    $.ajax({
        url:url,
        type:'GET',
        data:{
            '_token': "{{csrf_token()}}",
            'name':name
        },
        success:function(data){
            input.text(data);
            if (data <=  0) {
                $('.stock').css('color','red');
                shake($('.stock'));
                $('#product-qty').attr('readonly' ,true);
            }else if(data <=  10){
                $('.stock').css('color','#f0ad4e');
                $('#product-qty').attr('readonly' ,false);
            }else{
                $('.stock').css('color','green');
                $('#product-qty').attr('readonly' ,false);
            }
        },
        error:function(data){
            $('#err-list').text('Something went wrong !')
        }
    });
}

$(document).on('input' , '#product-filter' , function (e){

    const items = [];
    $("#product-search > option").each(function() {
        items.push(this.text);
    });

    $("#product-search option[data-barcode='" + $(this).val() + "']").prop("selected", true);
    $("#product-search option[data-itemcode='" + $(this).val() + "']").prop("selected", true);
    $("#product-search option[data-name='" + items.find(a =>a.includes($("#product-filter").val())) + "']").prop("selected", true);

    if ($(this).val() == $("#product-search").find('option:selected').data('barcode') || $(this).val() == $("#product-search").find('option:selected').data('name') || $(this).val() == $("#product-search").find('option:selected').data('itemcode')) {
            getItemAndCalcTotal($("#product-search"));
            getItemStock($(this).val() ,'/product/stock' ,$('#currentStock'));

    }else{

        // $('#product-filter').select();
        $('#product-itemCode').val('');
        // $("#product-search").val($("#product-search option:first").val());
        $('#product-name').val('');
        $('#product-qty').val('');
        $('#product-type').val('');
        $('#product-price').val('');
        $('#product-net').val('');
        $('#product-discount').val('');
        $('#product-total').val('');

        $('#err-list').text('منتج غير موجود.');
    }
});


$(document).ready(function() {
    $(document).on('change' , '#product-search' , function (e){
        e.preventDefault();

        getItemAndCalcTotal($(this));
        getItemStock($(this).val() ,'/product/stock' ,$('#currentStock'));
    });
});

getItemAndCalcTotal = function(select){

        $('#product-itemCode').val(select.find(':selected').data("itemcode") )
        $('#product-name').val(select.find(':selected').data("name"))
        $('#product-description').val(select.find(':selected').data("description"))
        $('#product-uuid').val(select.find(':selected').data("uuid"))
        // $('#product-search').val($(this).find(':selected').data("name"))
        $('#product-qty').val(1)
        $('#product-price').val(select.find(':selected').data("price"))
        $('#product-discount').val(0.00)


        var newOptions =
        {
            "first_": select.find(':selected').data("first_unit_type"),
            "second_": select.find(':selected').data("second_unit_type"),
            "third_": select.find(':selected').data("third_unit_type")
        };

        // remove null values from
        $.each(newOptions, function(key, value){
            if (value === "" || value === null || value === " "){
                delete newOptions[key];
            }
        });

        var $el = $("#product-type");
        $el.empty(); // remove old options


        $.each(newOptions, function(key,value) {
            $el.append($("<option></option>")
            .attr("value", key).text(value));
        });

        // $('#product-type').val(data[0].type_code).change();

        CalcItemTotal();
        $('#err-list').text('')
        $('#product-search').prop('readonly' , true);
        CalcItemTotal();
}

CalcItemTotal = function(){
    var price = $('#product-price').val().replace(/[^0-9.]/g, "");
    var qty = $('#product-qty').val().replace(/[^0-9.]/g, "");
    var disc = $('#product-discount').val().replace(/[^0-9.]/g, "");

    $('#product-net').val( (parseFloat(price) * parseFloat(qty)).toFixed(2) );
    $('#product-total').val( ((parseFloat(price) * parseFloat(qty)) - parseFloat(disc)).toFixed(2) );

}

CalcTransTotal = function(){
    var total = 0;
    $('table tr').each(function(){
        $('#rwtotal', this).each(function(){
            total += parseFloat($(this).val());
        });
        $('#total').val( (total ).toFixed(2) );
        $('#transTotal').val( (total ).toFixed(2) );
    });
}

CalcRemaining = function(paid = 0.0){

    var p = ( paid - $('#total').val() ).toFixed(2);
    $('#transPaid').val(paid);
    $('#remaining').val(p);
}

$('#paid').on('input', function (){
    CalcRemaining($(this).val());
});

$('#product-qty , #product-price , #product-discount').on('blur', function (){
    CalcItemTotal();
    CalcRemaining($('#paid').val());
});


$(document).on('click' , '#apply' , function (){

    var id = Math.floor(Math.random() * 999999999999999999999);
    var id2 = Math.floor(Math.random() * 999999999999999999999);

    if($('#product-price').val() == '' && $('#product-qty').val() == ''  && $('#product-type').val() == null ){

        $('#err-list').text('Cannot apply the form due to some empty data ,لا يمكن تطبيق النموذج بسبب وجود بعض البيانات الفارغة .');
        shake($('#err-list'));

    }
    // else if(currentStock <= 0 ||  parseFloat($('#product-qty').val()) > currentStock){
    //     $('#err-list').text('The quantity is greater than the quantity available , الكمية أكبر من الكمية المتوفرة.');
    //     shake($('#err-list'));
    // }
    else{
        // var count = $('.tr-item tr').length +1 ;
        var count = 0;
        let nums = [-1];
        $(".tr-item tr input[id='num']").each(function(){
            nums.push($(this).val());
        });
        count = Math.max.apply(Math,nums)+1;
        var data="<tr id='"+  id+id2 +"' class='bg-white border-b dark:bg-gray-800 dark:border-gray-700' >"
        data+= "<input type='hidden' name='items["+count+"][number]' id='num' class='item' value='"+count+"'>";
        data+= "<input type='hidden' name='items["+count+"][uuid]' class='item' value='"+$('#product-uuid').val()+"'>";
        data+= "<input type='hidden' name='items["+count+"][item_code]' class='item' value='"+$('#product-itemCode').val()+"'>";
        data+= "<input type='hidden' name='items["+count+"][item_type]' class='item' value='"+$("#product-type :selected").val()+"'>";
        // data +="<td class='py-4' style='width:200px'><div class='row form-group' >";
        // data+="<input type='text' class='col-12 text-center w-full hidden' name='items["+count+"][item_code]' class='text-center' style='background: transparent ; border:none' value='"+$('#product-itemCode').val()+"' readonly></p></div></td>";
        data +="<td style='width: 300px'><input type='text' name=items["+count+"][item]' class='text-center w-full' style='background: transparent ; border:none' value='"+$('#product-name').val()+"' readonly></td>";
        data +="<td class='rw-type' style='width: 100px'><input type='text' class='text-center w-full' style='background: transparent ; border:none' name='items["+count+"][unit_type]' value='"+$("#product-type :selected").text()+"' readonly ></td>";
        data +="<td class='rw-qty' style='width: 100px'><input type='number' step='.000001' name='items["+count+"][qty]' class='text-center w-full' id='rwqty' style='background: transparent ; border:none' value='"+parseFloat($('#product-qty').val()).toFixed(2)+"' ></td>";
        data +="<td class='rw-price' style='width: 100px'><input type='number' step='.000001' name='items["+count+"][unitPrice]' class='text-center w-full' id='rwprice' step='1' style='background: transparent ; border:none' value='"+parseFloat($('#product-price').val()).toFixed(2)+"'></td>";
        data +="<td class='rw-net' style='width: 120px'><input type='number' step='.000001' name='items["+count+"][net]' class='text-center w-full' id='rwnet' step='1' style='background: transparent ; border:none' value='"+$('#product-net').val()+"' readonly></td>";
        data +="<td class='rw-disc' style='width: 100px'><input type='number' step='.000001' name='items["+count+"][disc]' class='text-center w-full' id='rwdisc' step='1' style='background: transparent ; border:none' value='"+parseFloat($('#product-discount').val()).toFixed(2)+"'></td>";
        data +="<td class='rw-total' style='width: 120px'><input type='number' step='.000001' class='text-center w-full' name='items["+count+"][total]' id='rwtotal' style='background: transparent ; border:none' value='"+$('#product-total').val()+"' readonly></td>";
        data +="<td class=' h-print' style='width: 40px'><a href='#'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='trash text-center w-full bi bi-trash' viewBox='0 0 16 16'> <path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z'/> <path fill-rule='evenodd' d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z'/> </svg></a></td></tr>";
        // $('#MoreRows').append(data);
        $(data).insertBefore('#MoreRows');

        CalcTransTotal();
        CalcRemaining($('#paid').val());
        $('#reset').click();
        resetStock(0);
        rowsCount();
        $('#err-list').text('');
        // saveToDraft();
    }

});
$('#reset').on('click' , function(){
    $('#product-search').prop('readonly' , false);
    $('#err-list').text('');
});

rowsCount = function(){
    var count = $('.tr-item tr').length-1;
    $('#itemsCount').val(count);
}

$(document).on('click' , '.trash' , function (e){
    e.preventDefault();
    $(this).closest('tr').remove();
    CalcTransTotal();
    CalcRemaining($('#paid').val());
    rowsCount();
    $('#'+$(this).closest('tr').attr("id")).remove();
});

$(document).ready(function() {
    document.onkeyup = function (e) {
        var keyCode = e.keyCode;
        if(keyCode == 115 && $('#InvoiceItems').hasClass('hidden')) {
            document.getElementById('InvoiceItems').classList.remove('hidden');
            $('#product-filter').focus().select();
        } else if (keyCode == 115 && ! $('#InvoiceItems').hasClass('hidden')){
            document.getElementById('InvoiceItems').classList.add('hidden');
            $('#paid').focus().select();
        }
    };
});

$(document).ready(function() {

    document.onkeypress = function (e) {
        var keyCode = e.keyCode;
        if(keyCode == 13 && $('#product-total').val() != '' && $('#product-total').val() != null && ! $('#InvoiceItems').hasClass('hidden') &&
            $('#product-search')[0] !== document.activeElement && $('#product-type')[0] !== document.activeElement) {
            console.log(document.activeElement);
            console.log($('#product-search')[0]);

            CalcItemTotal();
            CalcTransTotal();
            CalcRemaining($('#paid').val());
            rowsCount();
            $('#apply').click();
            // $('#reset').click();
            $('#product-filter').focus().select();
        }
    };
});


$(document).ready(function() {
    $( "#product-search" ).on( "focus", function(e) {
        e.preventDefault();
        if(keyCode == 9 || keyCode == 13 ) {
            $('#product-qty').focus().select();
        }
    });

});


$(document).on('input' ,'#rwqty' , function (){

    var qty = $(this).parent().closest( ".rw-qty" ).children("#rwqty").val();
    var price = $(this).parent().siblings( ".rw-price" ).children("#rwprice").val();
    var disc = $(this).parent().siblings( ".rw-disc" ).children("#rwdisc").val();

    $(this).parents().siblings( ".rw-total" ).children("#rwtotal").val((qty * price)  - disc);
    $(this).parents().siblings( ".rw-net" ).children("#rwnet").val((qty * price));
    CalcTransTotal();
    CalcRemaining($('#paid').val())
});

$(document).on('input' ,'#rwprice' , function (){

    var qty = $(this).parent().siblings( ".rw-qty" ).children("#rwqty").val();
    var price = $(this).parent().closest( ".rw-price" ).children("#rwprice").val();
    var disc = $(this).parent().siblings( ".rw-disc" ).children("#rwdisc").val();

    $(this).parents().siblings( ".rw-total" ).children("#rwtotal").val((qty * price)  - disc);
    $(this).parents().siblings( ".rw-net" ).children("#rwnet").val((qty * price));
    CalcTransTotal();
    CalcRemaining($('#paid').val())
});

$(document).on('input' ,'#rwdisc' , function (){

    var qty = $(this).parent().siblings( ".rw-qty" ).children("#rwqty").val();
    var price = $(this).parent().siblings( ".rw-price" ).children("#rwprice").val();
    var disc = $(this).parent().closest( ".rw-disc" ).children("#rwdisc").val();

    $(this).parents().siblings( ".rw-total" ).children("#rwtotal").val((qty * price)  - disc);
    $(this).parents().siblings( ".rw-net" ).children("#rwnet").val((qty * price));
    CalcTransTotal();
    CalcRemaining($('#paid').val())

});

$(document).on('change' , '#product-type' , function (e){

    $("#product-price").val($("#product-search").find(':selected').data($(this).val()+"unit_sell_price"));
    CalcItemTotal();
});

$('body').on('submit','form', function(e) {
    if (parseFloat($('#paid').val()) < parseFloat($('#total').val()) ||
    parseFloat($("#transPaid").val()) < parseFloat($('#transTotal').val()) ) {
        e.preventDefault();

        $('#paidLessThanTotal').removeClass('hidden');
        shake($('#paidLessThanTotal li'));
    }
});

function shake(div,interval=100,distance=10,times=4){
    $(div).css('position','relative');
    for(var iter=0;iter<(times+1);iter++){
        $(div).animate({ left: ((iter%2==0 ? distance : distance*-1))}, interval);
    }//for
    $(div).animate({ left: 0},interval);
}//shake

function resetStock(){
    $('#currentStock').text(0);
    $('.stock').css('color','black');
    $('#product-qty').attr('readonly' ,false);

}

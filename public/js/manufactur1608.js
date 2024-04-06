$(document).on('click' , '#MoreRows' , function (){
    $('#Manufacturs').removeClass('hidden');
});
$(document).on('click' , '.fa-times' , function (){
    $('#exampleModalCenter').modal('hide');
});
$(document).on('input' , '#products-filter' , function (e){
    var name = $(this).val();
    $.ajax({
        url:"/product/manufactur/getItems",
        type:'GET',
        data:{
            'name':name
        },
        success:function(data){
            $("#products-search").find('option').remove().end();
            $("#products-search").append($("<option></option>").attr("value", '').text(''));
            for (let i = 0; i < data.length; i++) {
                $("#products-search").append($("<option></option>")
                    .attr("value", data[i].uuid + '*'+ data[i].name)
                    .text(data[i].name));
            }
        },
        error:function(data){
            $('#err-list').text('Something went wrong !')
        }
    });
});

$(document).on('input' , '#product-filter' , function (e){
    var name = $(this).val();
    $.ajax({
        url:"/product/manufactur/getItems",
        type:'GET',
        data:{
            'name':name
        },
        success:function(data){
            $("#product-search").find('option').remove().end();
            $("#product-search").append($("<option></option>").attr("value", '').text(''));
            for (let i = 0; i < data.length; i++) {
                $("#product-search").append($("<option></option>")
                    .attr("value", data[i].name)
                    .text(data[i].name));
            }
        },
        error:function(data){
            $('#err-list').text('Something went wrong !')
        }
    });
});
$(document).ready(function() {
    $(document).on('change' , '#product-search' , function (e){
        e.preventDefault();
        var name = $('#product-search').val();
        console.log(name);
        $.ajax({
            url:"/product/manufactur/filter",
            type:'GET',
            data:{
                'name':name
            },
            success:function(data){
                if(data.length > 0){

                    $('#product-codeType').val(data[0].code_type)
                    $('#product-itemCode').val(data[0].item_code)
                    $('#product-name').val(data[0].name)
                    $('#product-description').val(data[0].description)
                    $('#product-uuid').val(data[0].uuid)
                    $('#product-search').val(data[0].name)
                    $('#product-qty').val(1)
                    $('#product-price').val(data[0].purchase_price)

                    $('#err-list').text('')
                    $('#product-search').prop('readonly' , true);
                }else{
                    $('#err-list').text('Cannot find item:' + name)
                }
            },
            error:function(data){
                $('#err-list').text('Something went wrong !')
            }
        });
    });
});

$(document).on('click' , '#apply' , function (){

    // var id = Math.floor(Math.random() * 999999999999999999999);
    // var id2 = Math.floor(Math.random() * 999999999999999999999);
    if($('#product-price').val() != '' && $('#product-qty').val() != ''&& $('#product-tax').val() != ''&& $('#product-discount').val() != ''&& $('#product-tax-per').val() != '' && $('#product-discount-per').val() != ''){
        var count = 0;
        let nums = [-1];
        $(".tr-item tr input[id='num']").each(function(){
            nums.push($(this).val());
        });
        count = Math.max.apply(Math,nums)+1;
        var data="<tr>"
        data+= "<input type='hidden' name='items["+count+"][number]' class='item' id='num' value='"+count+"'>";
        data+= "<input type='hidden' name='items["+count+"][uuid]' class='item' value='"+$('#product-uuid').val()+"'>";
        data +="<td style='width: 40%;'><input type='text' class='col' name=items["+count+"][item]' style='background: transparent ; border:none' value='"+$('#product-name').val()+"' readonly></td>";
        data +="<td style='width: 40%;'><input type='text' class='col' style='background: transparent ; border:none' value='"+$('#product-description').val()+"' readonly></td>";
        data +="<td class='rw-price'><input type='number' class='col' name='items["+count+"][price]' id='price' style='background: transparent ; border:none' value='"+$('#product-price').val()+"' readonly></td>";
        data +="<td class='rw-qty'><input type='number' class='col' name='items["+count+"][qty]' id='rwqty' style='background: transparent ; border:none' value='"+$('#product-qty').val()+"' readonly></td>";
        data+="</td>";
        data +='<td class="h-print"><a href="#" id="trash"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"> <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/> <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/> </svg></a></td></tr>';
        // $('#MoreRows').append(data);
        $(data).insertBefore('#MoreRows');

        calcTotal();
        $('#reset').click();
        $('#err-list').text('');
    }else{
        $('#err-list').text('Cannot apply the form due to some empty data');
    }

});

calcTotal = function (){
    // var total = 0;
    // total = parseFloat($('#product-qty').val() * $('#product-price').val()).toFixed(5);
    // $('#total').val(+$('#total').val() + +total);
    var total = 0;
    var prices =[];
    var qtys =[];
    $('.rw-price').each(function(){
        $('#price', this).each(function(){
            prices.push($(this).val());
        });
    });
    $('.rw-qty').each(function(){
        $('#rwqty', this).each(function(){
            qtys.push($(this).val());
        });
    });

    for(var i=0; i< prices.length; i++) {
        total += prices[i]*qtys[i];
    }
    $('#total').val(total);
}
calcTotal();
// $(document).ready(function() {
//     var total = 0;
//     var prices =[];
//     var qtys =[];
//     $('.rw-price').each(function(){
//         $('#price', this).each(function(){
//             prices.push($(this).val());
//         });
//     });
//     $('.rw-qty').each(function(){
//         $('#rwqty', this).each(function(){
//             qtys.push($(this).val());
//         });
//     });

//     for(var i=0; i< prices.length; i++) {
//         total += prices[i]*qtys[i];
//     }
//     $('#total').val(total);
// });
$(document).on('click' , '#trash' , function (e){
    e.preventDefault();
    $(this).closest('tr').remove();
    $('#'+$(this).closest('tr').attr("id")).remove();
    calcTotal();
});

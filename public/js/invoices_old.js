document.getElementById('MoreRows').onclick = function(){
    document.getElementById('InvoiceItems').classList.remove('hidden');
}  //InvoiceItems
$(document).on('click' , '.fa-times' , function (){
    $('#exampleModalCenter').modal('hide');
});
/*
* Filters
*/
$(document).on('input' , '#product-filter' , function (){
    const items = [];
    $("#product-search > option").each(function() {
        items.push(this.text);
    });

    $("#product-search option[data-barcode='" + $(this).val() + "']").prop("selected", true);
    $("#product-search option[data-itemcode='" + $(this).val() + "']").prop("selected", true);
    $("#product-search option[data-name='" + items.find(a =>a.includes($("#product-filter").val())) + "']").prop("selected", true);

    if ($(this).val() == $("#product-search").find('option:selected').data('barcode') || $(this).val() == $("#product-search").find('option:selected').data('name') || $(this).val() == $("#product-search").find('option:selected').data('itemcode')) {
            getItemAndCalcTotal($("#product-search"));
    }
});

getItemAndCalcTotal = function(select){
    $('#product-codeType').val(select.find(':selected').data("codetype") )
    $('#product-itemCode').val(select.find(':selected').data("itemcode") )
    $('#product-name').val(select.find(':selected').data("name"))
    $('#product-description').val(select.find(':selected').data("description"))
    $('#product-uuid').val(select.find(':selected').data("uuid"))
    $('#currency-type').val("EGP").change();
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

    $('#product-tax-per').val(0)
    $('#product-discount-per').val(0)
    $('#product-discAfterTax').val(0)
    $('#product-tax-table').val(0)
    $('#product-tax-table-per').val(0)

    CalcItemPerDisc();
    CalcItemPerTax();
    CalcItemNet();
    CalcTotalSales();
    CalcItemTotal();
    $('#err-list').text('')
    $('#product-search').prop('readonly' , true);
}

// function getItems(data , element){
//     $(element).find('option').remove().end();
//         $(element).append($("<option></option>").attr("value", '').text(''));
//         for (let i = 0; i < data.length; i++) {
//             $(element).append($("<option></option>")
//                 .attr("value", data[i].name)
//                 .text(data[i].name));
//         }
// }

// function getItem(name ,url){

//     $.ajax({
//         url:url,
//         type:'GET',
//         data:{
//             '_token': "{{csrf_token()}}",
//             'name':name
//         },
//         success:function(data){
//             if(data['product'].length > 0){
//                 tax = 0 ;
//                 if(data['product'][0].tax != null){
//                     arr = (data['product'][0].tax).split(',');
//                     for (var i = 0; i < arr.length; i++) {
//                         tax += arr[i] << 0;
//                     }
//                 }

//                 $('#product-codeType').val(data['product'][0].code_type)
//                 $('#product-itemCode').val(data['product'][0].item_code)
//                 $('#product-name').val(data['product'][0].name)
//                 $('#product-description').val(data['product'][0].description)
//                 $('#product-uuid').val(data['product'][0].uuid)
//                 $('#product-search').val(data['product'][0].name)
//                 $('#product-qty').val(1)
//                 $('#product-price').val(data['product'][0].price)
//                 $('#product-tax').val(tax)
//                 $('#product-discount').val(!data['product'][0].discount == '' ? data['product'][0].discount : '0')

//                 $('#currency-type').val(data['product'][0].currency_code).change();

//                 // $('#product-type').val(data[0].type_code).change();
//                 var newOptions =
//                 {
//                     "first_": data['product'][0].first_unit_type,
//                     "second_": data['product'][0].second_unit_type,
//                     "third_": data['product'][0].third_unit_type
//                 };

//                 // remove null values from
//                 $.each(newOptions, function(key, value){
//                     if (value === "" || value === null){
//                         delete newOptions[key];
//                     }
//                 });

//                 var $el = $("#product-type");
//                 $el.empty(); // remove old options


//                 $.each(newOptions, function(key,value) {
//                     $el.append($("<option></option>")
//                     .attr("value", key).text(value));
//                 });

//                 $('#product-tax-per').val(0)
//                 $('#product-discount-per').val(0)
//                 $('#product-discAfterTax').val(0)
//                 $('#product-tax-table').val(0)
//                 $('#product-tax-table-per').val(0)

//                 CalcItemPerDisc();
//                 CalcItemPerTax();
//                 CalcItemNet();
//                 CalcTotalSales();
//                 CalcItemTotal();
//                 $('#err-list').text('')
//                 $('#product-search').prop('readonly' , true);
//             }else{
//                 $('#err-list').text('Cannot find item:' + name)
//             }
//         },
//         error:function(data){
//             $('#err-list').text('Something went wrong !')
//         }
//     });
// }
$(document).ready(function() {
    $(document).on('change' , '#product-search' , function (e){
        // getItem($('#product-search-pur').val() ,"/product/purchase/filter")
        getItemAndCalcTotal($(this));
    });
});

$(document).ready(function() {
    $(document).on('change' , '#product-search' , function (e){

        getItemAndCalcTotal($(this));
        // getItem($('#product-search').val() ,"/product/sales/filter")
    });
});


CalcItemNet = function(){
    var price = $('#product-price').val().replace(/[^0-9.]/g, "");
    var qty = $('#product-qty').val().replace(/[^0-9.]/g, "");
    var discount = $('#product-discount').val().replace(/[^0-9.]/g, "");
    var taxTable = $('#product-tax-table').val().replace(/[^0-9.]/g, "");

    var netCalc = (parseFloat(price) * parseFloat(qty) - parseFloat(discount)+ parseFloat(taxTable)).toFixed(2);
    $('#product-net').val(netCalc);

    CalcItemTax();
    CalcItemPerTax();
}

CalcTotalSales = function(){
    var price = $('#product-price').val().replace(/[^0-9.]/g, "");
    var qty = $('#product-qty').val().replace(/[^0-9.]/g, "");

    totalSales = (parseFloat(price) * parseFloat(qty)).toFixed(2);
    $('#product-totalSales').val(totalSales);
}

CalcItemTotal = function(){
    var price = $('#product-price').val().replace(/[^0-9.]/g, "");
    var qty = $('#product-qty').val().replace(/[^0-9.]/g, "");


    var totalTax = 0;
    $('.tax-row').each(function(){
        $('#product-tax', this).each(function(){
            if ($(this).data("type") == 'T4') {
                totalTax -= parseFloat($(this).val());
            }else{
                totalTax += parseFloat($(this).val());
            }
        });
    });
    var taxTable = $('#product-tax-table').val().replace(/[^0-9.]/g, "");
    var discount = $('#product-discount').val().replace(/[^0-9.]/g, "");
    var discAfterTax = $('#product-discAfterTax').val().replace(/[^0-9.]/g, "");

    $('#product-tax').length ? total =  ((parseFloat(price) * parseFloat(qty)) + totalTax - parseFloat(discount) + parseFloat(taxTable)).toFixed(2) :
        total =  ( (parseFloat(price) * parseFloat(qty))  - parseFloat(discount) + parseFloat(taxTable)).toFixed(2);
    total = total - parseFloat(discAfterTax).toFixed(2);
    $('#product-total').val(total);
}

CalcItemTax = function(){
    var net = $('#product-net').val().replace(/[^0-9.]/g, "");
    var taxCalc = 0;
    $('.tax-row').each(function(){
        $('.product-tax-per',this).each(function(){
            taxCalc = ( (parseFloat(net) ) * (parseFloat($(this).val())/100)).toFixed(2);
            $(this).closest('.tax-row').children('.product-tax').val(taxCalc);
        });
    });

}
CalcItemPerTax = function(){
    var taxPerCalc = 0;
    var net = $('#product-net').val().replace(/[^0-9.]/g, "");

    $('.tax-row').each(function(){
        $('.product-tax',this).each(function(){
            taxPerCalc = ((parseFloat($(this).val())/(parseFloat(net))*100)).toFixed(2);
            $(this).closest('.tax-row').children('.product-tax-per').val(taxPerCalc)
        });
    });
}
CalcItemDisc = function(){
    var discPer = $('#product-discount-per').val().replace(/[^0-9.]/g, "");
    var price = $('#product-price').val().replace(/[^0-9.]/g, "");
    var qty = $('#product-qty').val().replace(/[^0-9.]/g, "");
    discountCalc = (( parseFloat(price) * parseFloat(qty) ) * (parseFloat(discPer)/100)).toFixed(2);
    $('#product-discount').val(discountCalc);
    CalcItemNet();
}
CalcItemPerDisc = function(){

    var disc = $('#product-discount').val().replace(/[^0-9.]/g, "");
    var price = $('#product-price').val().replace(/[^0-9.]/g, "");
    var qty = $('#product-qty').val().replace(/[^0-9.]/g, "");
    discountPerCalc = ((parseFloat(disc) / (parseFloat(price) * parseFloat(qty)) * 100)).toFixed(2);
    if (isNaN(discountPerCalc) || discountPerCalc === "" || discountPerCalc === null ){

    }else {
        $('#product-discount-per').val(discountPerCalc);
    }

}
CalcItemTaxTable = function(){
    var taxTablePer = $('#product-tax-table-per').val().replace(/[^0-9.]/g, "");
    var price = $('#product-price').val().replace(/[^0-9.]/g, "");
    var qty = $('#product-qty').val().replace(/[^0-9.]/g, "");
    var disc = $('#product-discount').val().replace(/[^0-9.]/g, "");
    taxTableCalc = (( parseFloat(price) * parseFloat(qty) - parseFloat(disc) ) * (parseFloat(taxTablePer)/100)).toFixed(2);

    if (isNaN(taxTableCalc) || taxTableCalc === "" || taxTableCalc === null ){

    }else {
        $('#product-tax-table').val(taxTableCalc);
        CalcItemNet();
    }


}
CalcItemTaxTablePer = function(){
    var disc = $('#product-discount').val().replace(/[^0-9.]/g, "");
    var price = $('#product-price').val().replace(/[^0-9.]/g, "");
    var qty = $('#product-qty').val().replace(/[^0-9.]/g, "");

    var taxTable = $('#product-tax-table').val().replace(/[^0-9.]/g, "");
    taxTablePerCalc = ((parseFloat(taxTable)/((parseFloat(price) * parseFloat(qty) - parseFloat(disc)))*100)).toFixed(2);
    if (isNaN(taxTablePerCalc) || taxTablePerCalc === "" || taxTablePerCalc === null ){

    }else {
        $('#product-tax-table-per').val(taxTablePerCalc);
    }
}

$('#product-qty , #product-price').on('blur', function (){
    CalcItemTax();
    // CalcItemPerTax();

    CalcItemDisc();
    // CalcItemPerDisc();

    CalcItemTaxTable();
    // CalcItemTaxTablePer();

    CalcTotalSales();
    CalcItemNet();
    CalcItemTotal();
});
$(' #product-tax , #product-discount').on('blur', function (){
    CalcItemNet();
    CalcItemTotal();
//   CalcItemTaxAndDisc();
});
$(' #product-tax-table').on('blur', function (){
    CalcItemTaxTablePer();
    CalcItemTotal();
});
$('#product-tax-table-per').on('blur', function (){
    CalcItemTaxTable();
    CalcItemTotal();
});
$(document).on('blur' , '#product-tax-per' , function(){
    CalcItemTax();
    CalcItemTotal();
});
$('#product-discount-per').on('blur', function (){

    CalcItemTaxTable();
    CalcItemTaxTablePer();
    CalcItemDisc();
    CalcItemTotal();
});
$('#product-discount').on('blur', function (){

    CalcItemTaxTable();
    CalcItemTaxTablePer();
    CalcItemPerDisc();
    CalcItemTotal();
});
$(document).on('blur' , '#product-tax' , function(){
    CalcItemPerTax();
    CalcItemTotal();
})
$('#product-discAfterTax').on('blur', function (){
    CalcItemTotal();
//   CalcItemTaxAndDisc();
});
// $('#product-tax').on('input', function (){
//     CalcItemPerTax();
//     CalcItemTotal();
// });

// ADD MORE ROWS
$(document).on('click' , '#apply' , function (){

    var id = Math.floor(Math.random() * 999999999999999999999);
    var id2 = Math.floor(Math.random() * 999999999999999999999);
    tax = 0;
    taxPer = 0;
    if($('#product-tax').val() != null && $('#product-tax-per').val() != null){
        var taxArray = $('input[id=product-tax]').map(function() {
            return this.value;
        }).get();
        for (var i = 0; i < taxArray.length; i++) {
            tax += taxArray[i] << 0;
        }
        var taxPerArray = $('input[id=product-tax-per]').map(function() {
            return this.value;
        }).get();
        for (var i = 0; i < taxPerArray.length; i++) {
            taxPer += taxPerArray[i] << 0;
        }
    }
    if($('#product-codeType').val() != '' && $('#product-price').val() != '' && $('#product-qty').val() != ''&& $('#product-tax').val() != ''&& $('#product-discount').val() != ''&& $('#product-type').val() != '' && $('#currency-type').val() != '' && $('#product-net').val() != '' ){
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
        data+= "<input type='hidden' name='items["+count+"][item_type]' class='item' value='"+$("#product-type :selected").val()+"'>";
        data +="<td class='py-4' style='width:200px'><div class='row form-group' >";
        data+= "<p><input type='text' class='col-12 text-center w-full' name='items["+count+"][code_type]' style='background: transparent ; border:none' value='"+$('#product-codeType').val()+"' readonly>";
        data+="<input type='text' class='col-12 text-center w-full' name='items["+count+"][item_code]' class='text-center' style='background: transparent ; border:none' value='"+$('#product-itemCode').val()+"' readonly></p></div></td>";
        data +="<td  class='py-4' style='width: 300px'><input type='text' name=items["+count+"][item]' class='text-center w-full' style='background: transparent ; border:none' value='"+$('#product-name').val()+"' readonly><input type='text' class='text-center w-full' name='items["+count+"][description]' style='background: transparent ; border:none' value='"+$('#product-description').val()+"' readonly></td>";
        data +="<td class='rw-qty py-4' style='width: 100px'><input type='number' name='items["+count+"][qty]' class='text-center w-full' id='rwqty' style='background: transparent ; border:none' value='"+$('#product-qty').val()+"' ><input type='text' class='text-center w-full' style='background: transparent ; border:none' name='items["+count+"][unit_type]' value='"+$("#product-type :selected").text()+"' ></td>";
        data +="<td class='rw-price py-4' style='width: 100px'><input type='number' name='items["+count+"][unitPrice]' class='text-center w-full' id='rwprice' step='1' style='background: transparent ; border:none' value='"+$('#product-price').val()+"'><input type='text' class='text-center w-full' style='background: transparent ; border:none' name='items["+count+"][currency]' value='"+$('#currency-type').val()+"' ></td>";
        data +="<td class='rw-disc py-4' style='width: 100px'><input type='number' name='items["+count+"][disc]' class='text-center w-full' id='rwdisc' step='1' style='background: transparent ; border:none' value='"+$('#product-discount').val()+"'><input type='number' class='text-center w-full' name='items["+count+"][discPer]' id='rwdisc-per' style='background: transparent ; border:none' value='"+$('#product-discount-per').val()+"' ></td>";
        data +="<td class='rw-net py-4' style='width: 120px'><input type='number' name='items["+count+"][net]' class='text-center w-full' id='rwnet' step='1' style='background: transparent ; border:none' value='"+$('#product-net').val()+"'></td>";
        data +="<td class='rw-tax py-4' style='width: 100px'><input type='number' name='items["+count+"][tax]' class='text-center w-full' id='rwtax' step='1' style='background: transparent ; border:none' value='"+tax+"'><input type='number' class='text-center w-full' name='items["+count+"][taxPer]' id='rwtax-per' style='background: transparent ; border:none' value='"+taxPer+"'>";
        // var taxCount = count+Math.floor(Math.random() * 9);
        $('input[id=taxType]').each(function(){
            data+="<input type='hidden' class='count' name='items["+count+"][taxable][0][tax_type][]' class='w-full' value='"+$(this).val()+"'>"
        });
        $(".tax-selectSubType option:selected").each(function(){
            data+="<input type='hidden' name='items["+count+"][taxable][0][tax_sub_type][]' class='w-full' value='"+$(this).text()+"'>"
        });
        $('input[id=product-tax]').each(function(){
            let taxVal = 0 ;taxVal = $(this).val() ;
            data+="<input type='hidden' id='txVal' name='items["+count+"][taxable][0][taxvalue][]' class='w-full' data-type='"+$('input[id=taxType]').val()+"' value='"+taxVal+"'>"
        });
        $('input[id=product-tax-per]').each(function(){
            let taxPerVal = 0; taxPerVal = $(this).val() ;
            data+="<input type='hidden' name='items["+count+"][taxable][0][taxPervalue][]' class='w-full' value='"+taxPerVal+"'>"
        });
        data+="</td>";
        data +="<input type='hidden' name='items["+count+"][totalSales]' class='rw-totalSales text-center w-full' id='rw-totalSales' style='background: transparent ; border:none' value='"+$('#product-totalSales').val()+"'>";
        data +="<input type='hidden' name='items["+count+"][discountAfterTax]' class='rw-discountAfterTax text-center w-full' id='rw-discountAfterTax' style='background: transparent ; border:none' value='"+$('#product-discAfterTax').val()+"'>";
        data +="<input type='hidden' name='items["+count+"][tax_table]' class='rw-tax-table text-center w-full' id='rw-tax-table' style='background: transparent ; border:none' value='"+$('#product-tax-table').val()+"'>";
        data +="<input type='hidden' name='items["+count+"][tax_table_per]' class='rw-tax-table_per text-center w-full' id='rw-tax-table_per' style='background: transparent ; border:none' value='"+$('#product-tax-table-per').val()+"'>";
        data +="<td class='rw-total py-4' style='width: 120px'><input type='number' class='text-center w-full' name='items["+count+"][total]' id='rwtotal' style='background: transparent ; border:none' value='"+$('#product-total').val()+"' readonly></td>";
        data +="<td class=' h-print  py-4' style='width: 40px'><a href='#'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='trash text-center w-full bi bi-trash' viewBox='0 0 16 16'> <path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z'/> <path fill-rule='evenodd' d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z'/> </svg></a></td></tr>";
        // $('#MoreRows').append(data);
        $(data).insertBefore('#MoreRows');


        CalcTransTotal();
        $('#reset').click();
        rowsCount();
        $('.tax-row').remove();
        $('#err-list').text('');
        saveToDraft();
    }else{
        $('#err-list').text('Cannot apply the form due to some empty data');
    }

});
$('#reset').on('click' , function(){
    $('#product-search').prop('readonly' , false);
    $('#err-list').text('');
});


// CALCULATE ITEM TOTAL FUNCTIONS
CalcTransTotal = function(){
    var totalSales =0;
    var totalNet = 0;
    var totalItems = 0;
    var totaltaxTable =0;
    var totalItemsDiscount = 0;
    var totalTax = 0;
    var transTotalDiscAfterTax =0;

    var invDisc = $('#invDisc').val();
    var invTax = $('#invTax').val();

    $('table tr').each(function(){
        $('#rw-discountAfterTax', this).each(function(){
            transTotalDiscAfterTax += parseFloat($(this).val());
        });
        $('.transTotalDiscAfterTax').val(transTotalDiscAfterTax);
    });

    $('table tr').each(function(){
        $('#rw-totalSales', this).each(function(){
            totalSales += parseFloat($(this).val());
        });
        $('.totalSales').val(totalSales);
    });

    $('table tr').each(function(){
        $('.rw-tax-table', this).each(function(){
            totaltaxTable += parseFloat($(this).val());
        });
        $('.transTaxTable').val(totaltaxTable);
    });

    $('table tr').each(function(){
        $('#rwnet', this).each(function(){
            totalNet += parseFloat($(this).val() ) ;
        });
        $('#totalNet').val(totalNet);
    });

    $('table tr').each(function(){
        $('#rwtotal', this).each(function(){
            totalItems += parseFloat($(this).val()).toFixed(2);
        });
        $('.transTotalItems').val(totalItems);
    });

    $('table tr').each(function(){
        $('#rwdisc', this).each(function(){
            totalItemsDiscount += parseFloat($(this).val());
        });
        $('.transTotalItemsDisc').val(totalItemsDiscount);
    });

    $('table tr').each(function(){
        $('#txVal', this).each(function(){
            totalTax += parseFloat($(this).val() || 0);
        });
        $('.transTax').val((totalTax + parseFloat(invTax)).toFixed(2));
    });

    var totalDisc = 0;
    $('table tr').each(function(){
        $('#rwdisc', this).each(function(){
            totalDisc += parseFloat($(this).val());
        });
        $('.transDisc').val((parseFloat(invDisc)).toFixed(2));
    });

    var total = 0;
    $('table tr').each(function(){
        $('#rwtotal', this).each(function(){
            total += parseFloat($(this).val());
        });
        $('.transTotal').val( (total - parseFloat(invDisc) + parseFloat(invTax) ).toFixed(2) );
    });
}


$('#invDisc , #invExtraDisc ,#invTax , #invExtraTax').on('input' , function(){
    CalcTransTotal();
});

$(document).on('click' , '.trash' , function (e){
    e.preventDefault();
    $(this).closest('tr').remove();
    CalcTransTotal();
    rowsCount();
    $('#'+$(this).closest('tr').attr("id")).remove();
});

rowsCount = function(){
    var count = $('.tr-item tr').length-1;
    $('#itemsCount').val(count >= 2 ?  count + ' ITEMS' : count + ' ITEM');
}

$('#InvoiceID').text('ID: #0'+$('#InvoiceID').text() )

$('#internal_id,#invoice_date,#customer_id ,#customer_name ,#customer_country ,#gov ,#cities,#customer_buldingNumber,#customer_street').on('input' , function() {
    $('.'+$(this).attr('id')).text( ' '+$(this).val());

});

$(document).ready(function() {
    $(document).on('blur' , '#customer_id' , function (e){
        e.preventDefault();
        var id = $(this).val();
        $.ajax({
            url:"/customer/filter",
            type:'GET',
            data:{
                '_token': "{{csrf_token()}}",
                'id':id
            },

            success:function(customer){
                if(customer.length > 0){
                    $('#customer_type').val(customer[0].type)
                    $('#customer_id').val(customer[0].tax_code)
                    $('#customer_name' ).val(customer[0].name)
                    $('#customer_country ').val(customer[0].country)
                    $('#gov').val(customer[0].gov)
                    $('#cities').val(customer[0].city)
                    $('#customer_building_number').val(customer[0].building_number)
                    $('#customer_street').val(customer[0].street)

                    $('.customer_id').text(customer[0].tax_code)
                    $('.customer_name' ).text(customer[0].name)
                    $('.customer_country ').text(customer[0].country+' ')
                    $('.gov').text(customer[0].gov+' ')
                    $('.cities').text(customer[0].city)
                    $('.customer_building_number').text(customer[0].building_number)
                    $('.customer_street').text(customer[0].street)
                }
            },
            error:function(customer){

            }
        });
    });
});


// $(document).on('click' , '#uploadInvoice' , function (){
//     $('#invoice-form').prop('action' , "/invoice/sales/upload/to/invoice/portal" ) ;
//     $('#invoice-form').submit();
// });


$('#tax-select').on('change', function (){
    var SelectedName = this.name
    var Selectedvalue = this.value;
    $.ajax({
        url:"/get/taxes/subTypes",
        type:'GET',
        data:{
            '_token': "{{csrf_token()}}",
            'taxType':Selectedvalue
        },
        success:function(taxTypes){
            if(taxTypes.length > 0){
                var data = '<div class="grid lg:grid-cols-5 gap-1 mt-2 tax-row">';
                var taxCount = 0;
                if($('.count').length){taxCount = (parseInt($('.count').val()) +1).toFixed(0)}
                data+= '<div class="grid grid-cols-2"><input type="hidden" class="count" value="'+taxCount+'">';
                data+= '<input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="taxType" name="tax_code" value="'+Selectedvalue+'" readonly>';
                data+= '<select name="tax" id="tax-selectSubType" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 tax-selectSubType">'
                for (let i = 0; i < taxTypes.length; i++) {
                    const element = taxTypes[i]['Code'];
                    data+='<option value="'+element+'">'+element
                    data+='</option>'
                }
                data+='</select></div>'
                data+= '<input type="number" class="bg-white text-gray-900 border-gray-300 text-sm rounded-sm block w-full p-2.5 dark:bg-gray-700 dark:text-white product-tax" id="product-tax" data-type="'+Selectedvalue+'" value="0" min="0">';
                data+= '<p class="p-2.5 bg-gray-50" for="product-tax-per">%</p>';
                data+= '<input type="number" class="bg-white text-gray-900 border-gray-300 text-sm rounded-sm block w-full p-2.5 dark:bg-gray-700 dark:text-white product-tax-per" id="product-tax-per" data-type="'+Selectedvalue+'" value="0" min="0">';
                data+= '<p class="p-2.5 cursor-pointer" > <svg id="remove-tax" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"> <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/> <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/> </svg></p></div>';
                $('.tax-select').append(data);
                // $(data).insertAfter('#tax-select');
                $('#tax-select').val('Select Tax')
            }
        },
        error:function(data){
        }
    });
});

// $(document).ready(function() {
//     $('#gov').change( function(){
//         var id = $(this).val();
//         $.ajax({
//             url:"/get/cities",
//             type:'GET',
//             data:{
//                 '_token': "{{csrf_token()}}",
//                 'id':id
//             },
//             success:function(data){
//                 $('#cities').html(data);
//             }
//         });
//     });
// });

$(document).on('click' , '#remove-tax' , function (e){
    this.closest('div').remove();
    CalcItemTotal();
});


// function saveToDraft(){
//     let form = $('#subForm');
//     $.ajax({
//         type: 'POST',
//         url: '/invoice/sales/save/draft',
//         data: form.serialize() ,
//         dataType : 'json', // changing data type to json
//         success: function (data) { // here I'm adding data as a parameter which stores the response
//             console.log(data); // instead of alert I'm changing this to console.log which logs all the response in console.
//         }
//     });
// }

/**
 * Validation
 */
function isEmpty(value) {
    return (
        value === undefined ||
        value === null ||
        (typeof value === "string" && value.trim().length === 0) ||
        (typeof value === "object" && Object.keys(value).length === 0)
    );
}


// Confirm Leaveing
let nextRequest ;

$(document).on('click' , '.rounded-md.text-sm.font-medium.bg-white.text-primary-600' ,function (){
    location.href = nextRequest;
});

$(document).on('click' , '.relative.leading-8.m-0.pl-6' , function (e){

    if (document.querySelector('.bg-white.border-b') !== null) {
        e.preventDefault();
        nextRequest = $(this).closest('a').attr('href');

        window.$wireui.confirmNotification({
            title: 'You are about to lose data are you sure you want to leave?',
            description: 'Are you sure you want to leave?',
            icon: 'question',
            accept: {
                label: 'Yes, leave',
                method: 'leave',
                params: 'Leaved',
            },
            reject: {
                label: 'No, cancel',
                method: 'cancel'
            }
        })
    }
});

$(document).on('click' , 'a.flex-shrink-0.flex.items-center.gap-2.p-1.transition-colors.rounded-md.overflow-hidden.text-gray-800' , function (e){

    if (document.querySelector('.bg-white.border-b') !== null) {
        e.preventDefault();
        nextRequest = $(this).closest('a').attr('href');

        window.$wireui.confirmNotification({
            title: 'You are about to lose data are you sure you want to leave?',
            description: 'Are you sure you want to leave?',
            icon: 'question',
            accept: {
                label: 'Yes, leave',
                method: 'leave',
                params: 'Leaved',
            },
            reject: {
                label: 'No, cancel',
                method: 'cancel'
            }
        })
    }
});

$(document).ready(function() {
    document.onkeydown = function (e) {
        var keyCode = e.keyCode;
        if(keyCode == 27) {
            document.getElementById('InvoiceItems').classList.add('hidden');
        }
    };
});


$(document).on('change' , '#product-type' , function (e){
    var type = $(this).val();
    if (window.location.href.indexOf("purchase") != -1){
        $('#product-price').val($('#product-search').find(':selected').data(type + 'unit_pur_price'))

    }else{
        $('#product-price').val($('#product-search').find(':selected').data(type + 'unit_sell_price'))
    }

    CalcItemPerDisc();
    CalcItemPerTax();
    CalcItemNet();
    CalcTotalSales();
    CalcItemTotal();
});

$('#tax-select').on('change', function (){
    var SelectedName = this.name
    var Selectedvalue = this.value;

    var data = '<div class="row col-12 m-b-5">';

        data+= '<input type="text" class="btn col-5" style="border: 1px solid #ddd" id="val" name="tax_code[]" value="'+Selectedvalue+'" readonly>';
        data+= '<input type="text" id="tax_val" name="tax[]" value="0" class="form-control col-6 ">';
        // data+= '<button class="btn col-1" style="border: 1px solid #ddd">%</button>';
        // data+= '<input type="text" id="tax_per_val" name="tax_per[]" value="0" class="form-control col-4 ">';
        data+= '<button type="button" class="btn text-danger col-1" id="remove-tax"> <i class="fa fa-trash"></i></button></div>';

        // $('#tax-select').append(data);
        $(data).insertAfter('#tax-select');
});

$(document).on('click' , '#remove-tax' , function (e){
    this.closest('div').remove();
    calcSellprice();
});

calcSellprice = function(){
    var totalTax = 0;
    var sellPrice = 0;

    var purPrice = $('#purchase_price').val();
    var disc = $('#discount').val();
    var tax = $('#tax_val').val();

    $("[id^=tax_val]").each(function(){
        totalTax += parseFloat($(this).val());
    });

    sellPrice = ((parseFloat(purPrice) - parseFloat(disc) ) + parseFloat(totalTax)).toFixed(2);
    $('#sell_price').val(sellPrice);
}

$(document).on('input', '#tax_val ,#purchase_price , #discount', function(){
    calcSellprice();
});

// CalcTax = function(){
//     var purPrice = 0;
//     var taxPer = $('#tax_per_val').val();
//     var purPrice = $('#purchase_price').val();

//     tax = (parseFloat(purPrice) * (parseFloat(taxPer)/100)).toFixed(2);
//     $('#tax_val').val(tax);
// }
// CalcTaxPer = function(){
//     var purPrice = 0;
//     var tax = $('#tax_val').val();
//     var purPrice = $('#purchase_price').val();

//     taxPer = ((parseFloat(tax)/parseFloat(purPrice)*100)).toFixed(1);
//     $('#tax_per_val').val(taxPer);
// }

// $(document).on('input', '#tax_val', function(){
//     CalcTaxPer();
// });

// $(document).on('input', '#tax_per_val', function(){
//     CalcTax();
// });
// Get a reference to the file input element

$(document).on('click' , '#bulkUpload' , function (){
    $('#bulkUploadModal').removeClass('hidden');
});

/**
 * Expenses
 */
$(document).on('input' , '#exp_code , #exp_name' , function (){
    var id = $(this).val();
    $.ajax({
        url:"/expenses/filter",
        type:'GET',
        data:{
            '_token': "{{csrf_token()}}",
            'id':id
        },
        success:function(exp){
            if(exp.length > 0){
                $('#exp_code').val(exp[0].code)
                $('#exp_name' ).val(exp[0].name)
                $('#exp_statement' ).val(exp[0].statement)
                $('#exp_value').val(0)
            }
        },
        error:function(customer){

        }
    });
});

$(document).on('click' , '#expApply' , function (){

    if($('#exp_code').val() != '' && $('#exp_name').val() != ''&& $('#exp_value').val() != ''){
        var count = 0;
        let nums = [-1];
        $(".tr-item tr input[id='num']").each(function(){
            nums.push($(this).val());
        });
        count = Math.max.apply(Math,nums)+1;
        var data="<tr>"
        data+= "<input type='hidden' name='exp["+count+"][number]' id='num' class='item' value='"+count+"'>";
        data +="<td style='width: 5%;'><input type='text' class='text-center bg-inherit border-none w-full' name=exp["+count+"][exp_code]' value='"+$('#exp_code').val()+"' readonly></td>";
        data +="<td style='width: 30%;'><input type='text' name=exp["+count+"][exp_name]' class='text-center bg-inherit border-none w-full' value='"+$('#exp_name').val()+"' readonly></td>";
        data +="<td style='width: 50%;'><input type='text' class='text-center text-sm w-full rounded-lg cursor-pointer hover:ring-blue-500 hover:border-blue-500 border-white' name='exp["+count+"][exp_statement]' id='statement' value='"+$('#exp_statement').val()+"'></td>";
        data +="<td  style='width: 30%;' class='rw_val'><input type='number' class='text-center w-full text-sm rounded-lg cursor-pointer hover:ring-blue-500 hover:border-blue-500 border-white' name='exp["+count+"][exp_val]' id='rwval' value='"+$('#exp_value').val()+"'></td>";
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
/**
 * Revenues
 */

$(document).on('input' , '#rev_code , #rev_name' , function (){
    var id = $(this).val();
    $.ajax({
        url:"/revenues/filter",
        type:'GET',
        data:{
            '_token': "{{csrf_token()}}",
            'id':id
        },
        success:function(rev){
            if(rev.length > 0){
                $('#rev_code').val(rev[0].code)
                $('#rev_name' ).val(rev[0].name)
                $('#rev_statement' ).val(rev[0].statement)
                $('#rev_value').val(0)
            }
        },
        error:function(customer){

        }
    });
});

$(document).on('click' , '#revApply' , function (){

    if($('#rev_code').val() != '' && $('#rev_name').val() != ''&& $('#rev_value').val() != ''){
        var count = 0;
        let nums = [-1];
        $(".tr-item tr input[id='num']").each(function(){
            nums.push($(this).val());
        });
        count = Math.max.apply(Math,nums)+1;
        var data="<tr>"
        data+= "<input type='hidden' name='exp["+count+"][number]' id='num' class='item' value='"+count+"'>";
        data +="<td style='width: 5%;'><input type='text' class='text-center bg-inherit border-none w-full' name=exp["+count+"][exp_code]' value='"+$('#rev_code').val()+"' readonly></td>";
        data +="<td style='width: 30%;'><input type='text' name=exp["+count+"][exp_name]' class='text-center bg-inherit border-none w-full' value='"+$('#rev_name').val()+"' readonly></td>";
        data +="<td style='width: 50%;'><input type='text' class='text-center text-sm w-full rounded-lg cursor-pointer hover:ring-blue-500 hover:border-blue-500 border-white' name='exp["+count+"][exp_statement]' id='statement' value='"+$('#rev_statement').val()+"'></td>";
        data +="<td  style='width: 30%;' class='rw_val'><input type='number' class='text-center w-full text-sm rounded-lg cursor-pointer hover:ring-blue-500 hover:border-blue-500 border-white' name='exp["+count+"][exp_val]' id='rwval' value='"+$('#rev_value').val()+"'></td>";
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

/**
 * Custom Methods
 */
$(document).on('click' , '#MoreRows' , function (){
    $('#finances').removeClass('hidden');
});

numberFormat = function (){
    var no = $("#value").val();
    $.ajax({
        url:"/numberFormat",
        type:'GET',
        data:{
            '_token': "{{csrf_token()}}",
            'no':no
        },
        success:function(data){
            $('#value_text').val(data);
        },
        error:function(data){
        }
    });
};

$(document).on('input' , '#rwval' , function (){
    calcTotal();
});

calcTotal = function (){
    var total = 0;
    $('table tr').each(function(){
        $('#rwval', this).each(function(){
            total += parseFloat($(this).val());
        });
        $('#value').val((parseFloat(total)).toFixed(5));
    });
    numberFormat();
}


$(document).on('click' , '#trash' , function (e){
    e.preventDefault();
    $(this).closest('tr').remove();
    $('#'+$(this).closest('tr').attr("id")).remove();
    calcTotal();
});

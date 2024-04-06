$(document).on('input' , '#value' , function (){
    var no = $(this).val();
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
})

$(document).on('input' , '#customer_uuid , #customer_name' , function (){
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
                $('#customer_uuid').val(customer[0].tax_code ?? customer[0].id)
                $('#customer_name' ).val(customer[0].name)
            }
        },
        error:function(customer){

        }
    });
});
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
                $('#statement' ).val(exp[0].statement)
            }
        },
        error:function(customer){

        }
    });
});


$('#exp').click(function() {
    if($(this).is(':checked')) {
        $('#exp_code').prop('disabled', false);
        $('#exp_name').prop('disabled', false);

        $('#customer_name').prop('disabled', true);
        $('#customer_uuid').prop('disabled', true);
        $('#customer_name').val('');
        $('#customer_uuid').val('');
        $('#receiver').prop('disabled', true);
        $('#receiver').val('');
    }
});
$('#supp ,#cust').click(function() {
    if($(this).is(':checked')) {
        $('#customer_name').prop('disabled', false);
        $('#customer_uuid').prop('disabled', false);

        $('#exp_code').prop('disabled', true);
        $('#exp_name').prop('disabled', true);
        $('#exp_code').val('');
        $('#exp_name').val('');
        $('#receiver').prop('disabled', true);
        $('#receiver').val('');
    }
});
$('#other').click(function() {
    if($(this).is(':checked')) {
        $('#receiver').prop('disabled', false);

        $('#customer_name').prop('disabled', true);
        $('#customer_uuid').prop('disabled', true);
        $('#customer_name').val('');
        $('#customer_uuid').val('');
        $('#exp_code').prop('disabled', true);
        $('#exp_name').prop('disabled', true);
        $('#exp_code').val('');
        $('#exp_name').val('');

    }
});

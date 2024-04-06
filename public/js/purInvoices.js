$(document).on('click' , '.bulkUpload' , function (){
    $('#BulkUploadModal').modal('show');
});

$(document).on('click' , '.pages' , function (){
    $(".pagination").find(".active").removeClass("active");
    $(this).addClass("active");
    let id = $(this).attr('id');
    $.ajax({
        url:"/pur/invoice/page",
        type:'GET',
        data:{
            '_token': "{{csrf_token()}}",
            'id':id,
        },
        success:function(response){
            $('#purEinvoices').html(response)
        },
        error:function(response){
        }
    });
});

$('#searchByNumber').on('input', function (){
    var number = $(this).val();
    $.ajax({
        url:"/invoice/purchase/search",
        type:'GET',
        data:{
            '_token': "{{csrf_token()}}",
            'number':number,
        },
        success:function(data){
            $('#tbody').html(data);
        },
        error:function(data){
        }
    });
});

// EXPORT TO EXCEL
// $(document).on('click' , '.toExcel' , function (){
//     window.location.href= "{{route('Pur.toExcel')}}";
// });
$(document).on('click' , '.toExcel' , function (){
    $('#toExcelModal').modal('show');
});

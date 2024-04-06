$(document).ready(function() {
    $('#gov').change( function(){
        var id = $(this).val();
        $.ajax({
            url:"/get/cities",
            type:'GET',
            data:{
                '_token': "{{csrf_token()}}",
                'id':id
            },
            success:function(data){
                $('#cities').html(data);
            }
        });
    });
});

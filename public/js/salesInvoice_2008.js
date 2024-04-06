

window.addEventListener('sendToETA', event => {
    $.ajax({
        url:"/invoice/sales/serialize",
        type:'GET',
        data:{
            'X-CSRF-Token': $('meta[name="_token"]').attr('content'),
            'id':event.detail.uuid
        },
        success:function(data){
            /**
             * Egypt Trust Sealing CA
             * Egypt Trust CA G6
             * MCDR CA
             * Fixed Misr Corporate CA G1
             */
            if (data['document'] != null || data['document'] != ''){
                var res;
                var socket = new WebSocket("ws://localhost:18088");
                socket.onopen = () =>  socket.send('{Document:\'{'+data['document']+'}\',TokenCertificate:\''+data['token_cert']+'\'}');
                socket.onmessage = function (response) {
                    res = response.data;
                    if(res == 'No slots found' ||  res == 'Certificate not found' || res == 'no device detected')
                    {
                        window.$wireui.notify({
                            title: 'Error',
                            description: res,
                            icon: 'error'
                        })
                    }else{
                        $('#fullDocument').val(res);
                        $('#uuid').val(event.detail.uuid);
                        $('#up-form').submit();
                    }
                };
            }
            socket.onerror = function (){
                window.$wireui.notify({
                    title: 'Error',
                    description: 'Exeriya Web Signer Not Found !',
                    icon: 'error'
                })
            }
        },
        error:function(){
            window.$wireui.notify({
                title: 'Error',
                description: 'Exeriya Web Signer Not Found !',
                icon: 'error'
            })
        }
    });
})


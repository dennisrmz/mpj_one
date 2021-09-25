(function ($) {
    
    $(document).ready(function () {

        // $("#exampleModal").show();
        var showModal = localStorage.getItem('showModal');
        if(showModal != 1){
            setTimeout(function(){ 
                $("#boton-newsletter-mpj").click();
                localStorage.setItem("showModal",1);
            },7000);
            
        }
    }); // End document.ready
    
    $("#button-subscribe-news").click(function(){


        var get_email = $("#input-email").val();
        var get_phone = $("#input-phone").val();
        var send_data = {
            email : get_email,
            phone : get_phone
        }

        let form = {
            action: 'mpj_send_data_news',
            data:  send_data
        };

        $.post(mpj_obj.ajax_url, form).done(function (data){
            console.log('done');
            $("#exampleModal").modal('hide');
        }).fail(function(data){
            console.log('error');
            $("#exampleModal").modal('hide');
        });
    });

   
})(jQuery);
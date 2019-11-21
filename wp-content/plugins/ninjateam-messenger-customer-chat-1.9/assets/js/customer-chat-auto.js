jQuery(document).ready(function($){
  function popup(url, width, height, cb) {
                var top = top || (screen.height/2)-(height/2),
                    left = left || (screen.width/2)-(width/2),
                    win = window.open(url, 'qưewqewqew', 'location=0,status=1,resizable=yes,width='+width+',height='+height+',top='+top+',left='+left);
                function check() {
                    if (!win || win.closed != false) {
                        cb();
                    } else {
                        setTimeout(check, 100);
                    }
                }
                setTimeout(check, 100);
  }
  $('a.btn-cus-chat-connect').click(function(e){

    var url_site=$(this).data('url_site');
    popup('https://preview.ninjateam.org/token_customer_chat/index.php',600,555,function(){
        jQuery.ajax({
          url: 'https://preview.ninjateam.org/token_customer_chat/gettoken.php',
          type: 'get',
          dataType: 'json',
          crossDomain : true,
          xhrFields: {
            withCredentials: true
          }
        })
        .done(function(data) {
            jQuery.post(url_site+'?cus-chat-ajax=get__info__token', {
            token: data.token_admin,
            app_id: data.app_id_user,
            secret: data.secret_user,
         }, function(data, textStatus, xhr) {
            //console.log(data);
            location.reload();
        });
        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          // console.log("complete");
        });
      });
  });
  $('a#njt-icus-chat-reconnet').click(function(e){
      $.ajax({
                    url: ajaxurl+ '?action=njt_cus_chat_reconnect',            
                    type: 'POST',
                    data: {'reconnect':true},
            }).done(function(status) {
              if(status=="success"){
                    location.reload();
              }else{
                console.log("error");
              }
              }).fail(function() {
                    console.log("error");
               });
  });
});
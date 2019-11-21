jQuery(document).ready(function($){
    // ADD TARGET WITH MENU SUPPORT
    var count=$('#toplevel_page_njt-customer-chat-settings .wp-submenu li a').length;
   
    $('#toplevel_page_njt-customer-chat-settings .wp-submenu li a').each(function(index){
                if(index==(count-1))  // MORE PLUGIN
                {
                    $(this).attr('target','_blank');
                    $(this).attr('href','https://m.me/ninjateam.org?ref=support');
                }

    });
    // ADD TARGET WITH MENU SUPPORT

	$("#ninja-display-messenger").change(function(){
        var id = $(this).val();
        if ( id == "display" ) {
            $("#facebook-messenger-tr-show").removeClass("hidden");
            $("#facebook-messenger-tr-hide").addClass("hidden");
        }else if(id == "except"){
            $("#facebook-messenger-tr-hide").removeClass("hidden");
            $("#facebook-messenger-tr-show").addClass("hidden");
        }else{
        	$("#facebook-messenger-tr-hide").addClass("hidden");
            $("#facebook-messenger-tr-show").addClass("hidden");
        }
    });

	$("#ninja-display-messenger").trigger('change');

	$("#facebook-messenger-checkall").change(function(){
        $(".facebook_messenger_hide_page").prop('checked', $(this).prop("checked"));
    });
    $("#facebook-messenger-checkall-1").change(function(){
        $(".facebook_messenger_show_page").prop('checked', $(this).prop("checked"));
    });

    //
    $('#njt_cus_chat_color_icon').wpColorPicker();


    //===============TAB===============
/*
    $(".njt-customer-chat-mess-settings").click(function(e){
        e.preventDefault();
        if($(".njt-customer-chat-mess-settings").hasClass('nav-tab-active')){
                $(".njt-customer-chat-mess-settings").removeClass('nav-tab-active');
        }
        $(this).addClass('nav-tab-active');

        var _content_tab=$(this).data('content-tab');
       
        if(_content_tab=="general"){
            $(".njt_show_tab_general").show();
            $(".njt_show_tab_display").hide();
            $(".njt_show_tab_cutomize").hide();
        }else if(_content_tab=="display"){
            $(".njt_show_tab_general").hide();
            $(".njt_show_tab_display").show();
            $(".njt_show_tab_cutomize").hide();

        }else if(_content_tab=="customize"){
            $(".njt_show_tab_general").hide();
            $(".njt_show_tab_display").hide();
            $(".njt_show_tab_cutomize").show();

        }
    });
*/
    $('.njt-fb-admin-tab-control').on('click',function(){
        $('.njt-fb-admin-tab-control').removeClass('njt-fb-tab-active');
        $('.njt-fb-admin-tab-content').removeClass('njt-fb-tab-active');
        $(this).addClass('njt-fb-tab-active');
        $($(this).data('target')).addClass('njt-fb-tab-active');
        return false
    });
});
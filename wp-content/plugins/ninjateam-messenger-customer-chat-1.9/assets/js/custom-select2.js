jQuery(document).ready(function($){
	//#njt_customer_chat_display,#ninja-display-messenger,#njt_customer_chat_position_icon
	$("#njt_cus_chat_mess_language").select2();
	if (jQuery("#njt_customer_chat_list_page").length) {
		
        jQuery("#njt_customer_chat_list_page").select2({
             placeholder: 'Select Facebook Page',
            width: 'resolve',
            templateResult: function(state){
                 var page_id = $(state.element).data('image');
               if (typeof page_id != 'undefined') {
                   return $('<span><img width="30" src="'+ page_id +'" class="img-flag" /> ' + state.text + '</span>');
               } else {
                   return state.text;
               }
            }
        })

    }

    // DEFAULT COLOR ICON

    $("#njt_cus_button_default_color").click(function(event){
    		event.preventDefault();
    		
    		$("input.button.button-small.wp-picker-clear").click();
    });


});
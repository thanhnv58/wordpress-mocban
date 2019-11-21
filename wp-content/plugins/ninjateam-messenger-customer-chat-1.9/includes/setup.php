<?php
error_reporting(0);
$link_call_back=add_query_arg(array('page'=>'njt-customer-chat-settings'),admin_url('admin.php'));
$fb_api = new NJT_CUSTOMER_CHAT_API();
$check=-1;

if(get_option('njt_customer_chat_app_token') && get_option('njt_customer_chat_app_id') && get_option('njt_customer_chat_app_serect')){
	$user_token=get_option('njt_customer_chat_app_token');
	$check=$fb_api->check_token_live($user_token);
	if(gettype($check)=="string"){
		$check=-1;
	}
}
?>
<?php if(!get_option('njt_customer_chat_enable') || !get_option('njt_customer_chat_list_page')) {?>
			<?php if(!get_option('njt_customer_chat_app_token')){ ?>
					  <div class="error"><p><strong>Step 1: </strong>Please click "Connect to Facebook" button bellow to connect your pages.</p>
					  </div>
			<?php }else if(get_option('njt_customer_chat_app_token') && get_option('njt_customer_chat_app_id') && get_option('njt_customer_chat_app_serect')){?>
					  <div class="error"><p><strong>Step 2: </strong>Please select a Facebook page!</p>
					  </div>
			<?php }?>
	<?php }else if(get_option('njt_customer_chat_app_token') && get_option('njt_customer_chat_list_page') && get_option('njt_customer_chat_enable')){ ?>
		<?php
				$app_page=get_option('njt_customer_chat_list_page');
				$domain=home_url('/');
				$page_access_token=$fb_api->Get_Access_Token_Page($user_token,$app_page);
				//	if(gettype($list_domain)!="array"){
						?>
					
					  <div class="error"><p><strong>Note: </strong>If the icon does not display at the front end, please whitelist your domain <a style="text-decoration: none;" href="https://facebook.com/<?php echo $app_page; ?>/settings/?tab=messenger_platform" target="_blank"> here</a>, check <a style="text-decoration: none;" href="http://ninjateamorg.s3.amazonaws.com/img/Ninja_Team_Settings_2017-11-30_12-52-54.png" target="_blank">this screenshot</a> for tutorial.</p>
					  </div>
						<?php
				//	}
				if(gettype($page_access_token)=="array"){
					$list_domain=$fb_api->List_Domain_APP($app_page,$page_access_token["access_token"]);
				
					global $count;
					$count=0;
					if(count($list_domain)!=0 && gettype($list_domain)=="array"):
						foreach ($list_domain as $key => $value) {
							if(in_array($domain,$value["whitelisted_domains"])){
										$count++;
										break;
							}
						}
					endif;
					if(count($list_domain)==0 || $count==0){  // if have not.				{
						$fb_api->Set_Domain_APP($app_page,array($domain),$page_access_token["access_token"]);
						$count++;
					}
					$page_sub=$fb_api->NJT_Page_SubScriber_Webhook_APP($page_access_token["access_token"],$app_page);
				}
		?>
<?php } ?>
<?php if( isset($_GET['settings-updated']) ): ?>
		<div style="margin-left: 0px;margin-top: 15px;" class="notice notice-success is-dismissible">
		                            <p><?php _e('Save changed!',NJT_CUSTOMER_CHAT); ?></p>
		</div>
<?php endif;?>
<style type="text/css">
	/*#wpwrap{background: #fff;}*/
	.notice.notice-warning{display: none;}
</style>
<div class="wrap">
<h1>Messenger Customer Chat Settings</h1>
</div>     
<div class="njt-fb-admin-tab-controls">
        	<div class="njt-fb-admin-tab-control njt-fb-tab-active" data-target="#njt-fb-tab-1"><?php echo __("General",NJT_CUSTOMER_CHAT) ?></div>
        	<div class="njt-fb-admin-tab-control" data-target="#njt-fb-tab-2"><?php echo __("Display",NJT_CUSTOMER_CHAT) ?></div>
        	<div class="njt-fb-admin-tab-control" data-target="#njt-fb-tab-3"><?php echo __("Customize",NJT_CUSTOMER_CHAT) ?></div>
</div>
<form  action="options.php" method="post" id="nj-fb-class">
<?php 
	settings_fields('njt_customer_chat');
	$app_id=get_option('njt_customer_chat_app_id');
	$app_serect=get_option('njt_customer_chat_app_serect');
	$app_margin_bottom=get_option('njt_customer_chat_margin_bottom');
	$app_postion_icon=get_option('njt_customer_chat_position_icon');
	$app_icon_color=get_option('njt_customer_chat_color_icon');
	$app_display=get_option('njt_customer_chat_display');
	$app_page=get_option('njt_customer_chat_list_page');
	$enable_plugin=get_option('njt_customer_chat_enable');
	$app_languages=get_option('njt_customer_chat_languages');
	$display=get_option('njt_customer_chat_facebook_messenger_hide_display');
	$array_show=get_option('njt_fb_facebook_messenger_show_page');
	$array_hide=get_option('njt_fb_facebook_messenger_hide_page');
	// update 12/2/18
	$logged_in_greeting=get_option('njt_fb_logged_in_greeting');
    $logged_out_greeting=get_option('njt_fb_logged_out_greeting');
    $enable_minimized = get_option('njt_fb_enable_minimized');
 ?>
	<div style="padding-top: 20px" class="njt-fb-admin-tab-contents">
	<input type="hidden" name="njt_customer_chat_app_id" value="<?php echo $app_id; ?>">
	<input type="hidden" name="njt_customer_chat_app_serect" value="<?php echo $app_serect; ?>">
		<div id="njt-fb-tab-1" class="njt-fb-admin-tab-content njt-fb-tab-active">
		        <table class="form-table">
		            <tr class="njt_show_tab_general">
						<th scope="row">
							<label for="">Enable Messenger Chatbox</label>
						</th>
						<td>
							<label class="njt-switch-button">
								<!-- checked=""-->
								<input <?php if($enable_plugin=="on") echo "checked='checked'"; ?>  name="njt_customer_chat_enable" class="njt-switch-button-input njt_customer_chat_enable" type="checkbox" />
								<span class="njt-switch-button-label" data-on="On" data-off="Off"></span> 
								<span class="njt-switch-button-handle"></span> 
							</label>
							<p class="description">Enable if you want to display Messenger Chatbox on your website.</p>
						</td>
					</tr>
					<tr class="njt_show_tab_general">
						<?php if($check!=1) {?>
							<th scope="row">
								<label for="">Connect Facebook</label>
							</th>
							<td>
								<!--<a class="button button-primary" href="<?php echo $link_login; ?>">Login Facebook</a>-->
								<?php
									$url_site=home_url('/');
									if(check_protocol_siteURL()=="https://"){
										$url_site=str_replace ("http://","https://", $url_site);
									}
								?>
								<a data-url_site="<?php echo $url_site; ?>" class="btn btn-login-fb btn-primary btn-cus-chat-connect" href="javascript:void(0)"><img src="<?php echo NJT_CUSTOMER_CHAT_URL.'assets/images/facebook-icon.svg'?>">Connect to Facebook</a>
								<p class="description">We'll need permissions to manage your Pages to set up your Messenger Chat.</p>
							</td>
						<?php } ?>
					</tr>
					<!--==========================-->
					<?php if($check==1) {
							$list_page=$fb_api->Get_List_Page($user_token);
			    	?>
					<tr class="njt_show_tab_general">
							<th scope="row">
								<label for="">Select a Fan Page</label>
							</th>
							<td>
								<!-- njt_abandoned_cart_page  webmenu-->
			 					<select style="width: 350px;" name="njt_customer_chat_list_page" id="njt_customer_chat_list_page" class="njt_customer_chat_list_page">
										<option value="" >Please select page</option>
			 						<?php foreach ($list_page['accounts'] as $key => $list): $id=$list['id'];$page_name=$list['name']?>
				    						<option <?php if($app_page==$id) echo "selected='selected'"; ?> value="<?php echo $id; ?>" data-image="<?php echo $list['picture']['url'];?>" ><?php echo $page_name; ?></option>
									<?php endforeach; ?>   
		  						</select>
								<p class="description">Select a Fan Page you want your visitors to send message to</p>
							</td>
					</tr>
					<?php } ?>
					<tr class="njt_show_tab_general">
							<th scope="row">
								<label for="">Language</label>
							</th>
							<td>
							<?php 
									$language=njt_customer_chat_languages();
							?>
								<select style="width: 350px;" name="njt_customer_chat_languages" id="njt_cus_chat_mess_language" class="njt_customer_chat_languages">
			  							<?php foreach ($language as $key => $value) { ?>
			  								<option <?php if($app_languages==$key) echo "selected='selected'"; ?> value="<?php echo $key; ?>" data-image="<?php echo $value['picture']['url'];?>" ><?php echo $value; ?></option>
			  							<?php } ?>
		  						</select>
							</td>
					</tr>
					<tr class="njt_show_tab_general">
					<?php if($check==1){?>
							<th scope="row">
								<label for="">Connect Facebook</label>
							</th>
							<td>
								<p class="description"></p>
								<p>Connected to <b><?php echo $fb_api->Me($user_token)['name']; ?></b> successfully! <a href="javascript:void(0)" id="njt-icus-chat-reconnet">Reconnect</a>
								</p>
								<p></p>
							</td>
					<?php } ?>
					</tr>   
		        </table>
		</div>
		<div id="njt-fb-tab-2" class="njt-fb-admin-tab-content">
		        <table class="form-table">
		            <tr  class="njt_show_tab_display">
						<th scope="row">
							<label for="">Devices</label>
						</th>
						<td>
							<select style="width: 300px;" name="njt_customer_chat_display" id="njt_customer_chat_display">
		  								<option <?php if($app_display=="both") echo "selected='selected'"; ?> value="both" ><?php _e("Display on desktop & mobile",NJT_CUSTOMER_CHAT); ?></option>
		  								<option <?php if($app_display=="mobile") echo "selected='selected'"; ?> value="mobile" ><?php _e("Display on mobile only",NJT_CUSTOMER_CHAT); ?></option>
		  								<option <?php if($app_display=="desktop") echo "selected='selected'"; ?> value="desktop" ><?php _e("Hide on mobile",NJT_CUSTOMER_CHAT); ?></option>
	  						</select>
							<p class="description">Select devices you want to display Messenger Chatbox</p>
						</td>
					</tr>
					<tr  valign="top" class="njt_show_tab_display">
				        <th scope="row"><label><?php echo __("Display",NJT_CUSTOMER_CHAT) ?></label></th>
				            <td>
				                <select style="width: 300px;" name="njt_customer_chat_facebook_messenger_hide_display" id="ninja-display-messenger">
				                	<option <?php if($display=="evething") echo "selected='selected'"; ?>  value="evething"><?php echo __("Display on all pages and posts",NJT_CUSTOMER_CHAT) ?></option>
				                   <option <?php if($display=="except") echo "selected='selected'"; ?> value="except"><?php echo __("Display on all pages but except",NJT_CUSTOMER_CHAT) ?></option>
				                    <option <?php if($display=="display") echo "selected='selected'"; ?> value="display"><?php echo __("Display on pages...",NJT_CUSTOMER_CHAT) ?></option>
				                </select>
				                    <p class="description" ><?php echo __("Select type you want to display Messenger (If it is not displayed in WooCommerce pages, please make sure you selected 'Display all pages but except' option)",NJT_CUSTOMER_CHAT) ?></p>
				              </td>
				    </tr>
				    <tr  valign="top" id="facebook-messenger-tr-hide"  class="<?php if ( $display == 1) {echo 'hidden';} ?> ">
				           <th scope="row"><label for="facebook_messenger_hide_page"><?php echo __("Display all pages but except",NJT_CUSTOMER_CHAT) ?></label></th>
				                <td>
				                    <input type="checkbox" id="facebook-messenger-checkall" /> <label for="facebook-messenger-checkall">All</label>
				                        <ul id="facebook_messenger_hide_page" class="facebook_messenger_hide_page">
				                        <?php $new = new WP_Query(array("posts_per_page"=>-1,"post_type"=>"page"));
				                           $array_hide = get_option( "njt_fb_facebook_messenger_hide_page");
				                            if ( !$array_hide ){
				                                $array_hide = array();
				                            }
				                            while ( $new->have_posts() ) : $new->the_post() ;
				                            ?>
				                            <li><input <?php
				                                if ( in_array(get_the_ID(), $array_hide ) ) { echo 'checked="checked"'; }
				                             ?> name="njt_fb_facebook_messenger_hide_page[]" class="facebook_messenger_hide_page" type="checkbox" value="<?php the_ID() ?>" id="facebook_messenger_hide_page_<?php the_ID() ?>" /> <label for="facebook_messenger_hide_page_<?php the_ID() ?>"><?php the_title() ?></label></li>
				                            <?php
				                            endwhile;wp_reset_postdata();
				                         ?>
				                         </ul>
				                         <p class="description"><?php _e("Select where you want to display Messenger Chatbox",NJT_CUSTOMER_CHAT) ?></p>
				                    </td>
				    </tr>
				    <tr  valign="top" id="facebook-messenger-tr-show" class="<?php if ( $display != 1) {echo 'hidden';} ?> ">
				                    <th scope="row"><label for="facebook_messenger_show_page"><?php echo __("Where you want to display",NJT_CUSTOMER_CHAT) ?></label></th>
				                    <td>
				                        <input type="checkbox" id="facebook-messenger-checkall-1" /> <label for="facebook-messenger-checkall-1">All</label>
				                        <ul id="facebook_messenger_show_page" class="facebook_messenger_show_page">
				                        <?php $new = new WP_Query(array("posts_per_page"=>-1,"post_type"=>"page"));
				                           $array_show = get_option( "njt_fb_facebook_messenger_show_page");
				                            if ( !get_option( "njt_fb_facebook_messenger_show_page") ) {
				                               $array_show = array();
				                            }
				                            while ( $new->have_posts() ) : $new->the_post() ;
				                            ?>
				                            <li><input <?php
				                                if ( in_array(get_the_ID(), $array_show ) ) { echo 'checked="checked"'; }
				                            ?> name="njt_fb_facebook_messenger_show_page[]" class="facebook_messenger_show_page" type="checkbox" value="<?php the_ID() ?>" id="facebook_messenger_show_page_<?php the_ID() ?>" /> <label for="facebook_messenger_show_page_<?php the_ID() ?>"><?php the_title() ?></label></li>
				                            <?php
				                            endwhile;wp_reset_postdata();
				                         ?>
				                         </ul>
				                         <p class="description"><?php _e("Select where you want to display Messenger Chatbox",NJT_CUSTOMER_CHAT); ?></p>
				                    </td>
				     </tr>   
					<!-- update 12/2/18 -->
					<tr class="njt_show_tab_display">
						<th scope="row">
							<label for="njt_fb_enable_minimized">Minimized on desktop</label>
						</th>
						<td>
							<label class="njt-switch-button">
								<!-- checked=""-->
								<input id="njt_fb_enable_minimized" <?php if($enable_minimized=="on") echo "checked='checked'"; ?>  name="njt_fb_enable_minimized" class="njt-switch-button-input njt_customer_chat_enable" type="checkbox" />
								<span class="njt-switch-button-label" data-on="On" data-off="Off"></span> 
								<span class="njt-switch-button-handle"></span> 
							</label>
							<p class="description">By default, the greeting bubble will be shown on desktop and minimized on mobile. To minimize on desktop, turn ON this option.</p>
						</td>
					</tr>
		        </table>
		</div>
		<div id="njt-fb-tab-3" class="njt-fb-admin-tab-content">
				<table class="form-table">
		        		<!-- Customize-->
						<tr class="njt_show_tab_cutomize">
								<th scope="row">
									<label for="">Margin Bottom</label>
								</th>
								<td>
								<div style="margin-left: 5px;padding-left: 0px;margin-top: 0px;border: none;background: none;box-shadow: none;" class="card card-transparent">
			                          <div class="card-header ">
			                           <div class="card-title">
			                           </div>
			                        </div>
			                        <div class="card-block">
			                            <div class="col-lg-8 disable-hover-scale no-padding m-t-20">
			                                 <div class="bg-master m-b-10" id="slider-tooltips"></div>
			                                 <div id="number_cus"><input id="njt_customer_chat_margin_bottom" type="number" min="0" max="200" name="njt_customer_chat_margin_bottom" value="<?php if(get_option('njt_customer_chat_margin_bottom')) echo get_option('njt_customer_chat_margin_bottom'); else echo 0; ?>">(px)</div>
			                            </div>
			                        </div>
			                    </div>
									<p class="description">Margin Messenger Chatbox from bottom (in some cases it obscures the to-top button)</p>
								</td>
						</tr>
						<tr class="njt_show_tab_cutomize">
								<th scope="row">
									<label for="">Icon Position</label>
								</th>
								<td>
									<select style="width: 200px" name="njt_customer_chat_position_icon" id="njt_customer_chat_position_icon" class="njt_customer_chat_position_icon">
				  								<option value="" <?php if(empty($app_postion_icon)) echo "selected='selected'"; ?> ><?php _e("Default",NJT_CUSTOMER_CHAT); ?></option>
				  								<option value="left" <?php if($app_postion_icon=="left") echo "selected='selected'"; ?>><?php _e("Left",NJT_CUSTOMER_CHAT); ?></option>
				  								<option value="right" <?php if($app_postion_icon=="right") echo "selected='selected'"; ?> ><?php _e("Right",NJT_CUSTOMER_CHAT); ?></option>
			  						</select>
									<p class="description">Select position for Messenger Icon</p>
								</td>
						</tr>
						<tr class="njt_show_tab_cutomize">
								<th scope="row">
									<label for="">Main color</label>
								</th>
								<td>
									<input id="njt_cus_chat_color_icon" type="text" name="njt_customer_chat_color_icon" value="<?php echo $app_icon_color; ?>"/>
									<a class="button" href="javascript:void(0)" id="njt_cus_button_default_color">Use default color</a>
									<p class="description"><?php _e("The color to use as a theme for the plugin. We highly recommend you choose a color that has a high contrast to white.",NJT_CUSTOMER_CHAT); ?></p>
								</td>
						</tr>
						<!-- update 2/12/18 logged_in_greeting -->
						<tr class="njt_show_tab_cutomize">
								<th scope="row">
									<label for="logged_in_greeting">Logged in greeting text</label>
								</th>
								<td>
								<input  maxlength="80" id="logged_in_greeting" style="width: 50% " type="text" name="njt_fb_logged_in_greeting" value="<?php echo $logged_in_greeting;?>"/>						
								<p class="description">
									<?php _e("The greeting text that will be displayed if the user is currently logged in to Facebook. Maximum 80 characters.<br/>Leave blank to use default text.",NJT_CUSTOMER_CHAT); ?>						
								</p>
							</td>
						</tr>
						<tr class="njt_show_tab_cutomize">
								<th scope="row">
									<label for="">Logged out greeting text</label>
								</th>
								<td>
								<input maxlength="80" id="logged_out_greeting" style="width: 50% " type="text" name="njt_fb_logged_out_greeting" value="<?php echo $logged_out_greeting ; ?>"/>						
								<p class="description">
									<?php _e("The greeting text that will be displayed if the user is currently not logged in to Facebook. Maximum 80 characters.<br/>Leave blank to use default text.",NJT_CUSTOMER_CHAT); ?>						
								</p>
							</td>
						</tr>
		        </table>
		</div>
				<?php submit_button("Save Changes") ?>
	</div>
</form>
<script>
    jQuery(document).ready(function($){
    	function noUiSlider_change(start){
	    	$("#slider-tooltips").noUiSlider({
	            start: start,//10,
	            connect: "lower",
	            step: 1,
	            format: wNumb({
		decimals: 0
	}),
	            range: {
	                'min': 0,
	                'max': 200//1000
	            }});
    	}
        <?php if(empty($app_margin_bottom)) $start=0; else $start=(int)$app_margin_bottom;?>
        noUiSlider_change(<?php echo $start; ?>);
        $("#slider-tooltips").Link('lower').to('-inline-<div class="tooltip fade top in" style="top: -27px;left: -14px;opacity: 0.7;"></div>', function(value) {
            // The tooltip HTML is 'this', so additional
            // markup can be inserted here.
            $(this).html(
                '<input type="hidden" name="njt_customer_chat_hidden_margin_bottom" value="'+value+'"><div class="tooltip-inner">' +
                '<span>' + value + '</span>' +
                '</div>'
            );
            $("#njt_customer_chat_margin_bottom").val(value);
        });
        $("#njt_customer_chat_margin_bottom").on('input',function(){
        		//noUiSlider_change($("#njt_customer_chat_margin_bottom").val());
        		var value=$("#njt_customer_chat_margin_bottom").val();
        		var percent=(value*100)/200;
        		//alert(percent);
        		var style='left:'+percent+'%';
        		//alert($('.noUi-origin.noUi-background').attr('style'));
        		$('.noUi-origin.noUi-background').attr('style',style);
        		//alert($('.noUi-origin.noUi-background').attr('style'));
        		$(".tooltip-inner span").text(value);
        });
    });
</script>
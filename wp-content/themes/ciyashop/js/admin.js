(function($){
	"use strict";
	
	jQuery(document).ready(function($) {
		
		/********************************
		::	Mega Menu Field Dependency
		*********************************/
		
		$(document).on( 'change', '.pgs_menu-custom-fields :input', function() {
			megamenu_field_dependabcy();
		});
			
		var MegaMenuSettings = function(){
			if( $('#cs_megamenu_enable:checked').length > 0 ){
				if( !$('#menu-to-edit').hasClass('cs_mega_menu-enabled')){
					$('#menu-to-edit').addClass('cs_mega_menu-enabled');
				}
				
				$("#menu-to-edit li.menu-item").each(function() {
					var menu_item = $(this);
					var button = '<span class="pgs-megamenu-setting">'+ciyashop_admin.menu_settings+'</span>';
					if( ! $(this).find('.pgs-megamenu-setting').length > 0 ){
						$(".item-title", menu_item).append(button);
					}
				});
			} else {
				$('.pgs-megamenu-setting').remove();
				$('#menu-to-edit').removeClass('cs_mega_menu-enabled');
			}
		};
		
		MegaMenuSettings();
		$( document ).ajaxSuccess(function() {
			MegaMenuSettings();
		});
		
		/*****************************
		::	Mega Menu Enable Setting
		******************************/
		
		$(document).on( 'click', '#cs_megamenu_enable', function(e){
			
			var	menu_id = $('#nav-menu-meta-object-id').val();
			var mm_enable = false;
			var $this = $(this);
			
			$('#ciyashop_mega_menu_enable').find('.spinner').addClass('is-active');
			
			if( $('input[name="cs_megamenu_enable"]:checked').length > 0 ){
				mm_enable = true;
			}
			
			$.ajax({
				url: ajaxurl,
				method: 'POST',
				dataType: 'json',
				data: {
					'action' : 'cs_megamenu_enable',
					'ajax_nonce' : ciyashop_admin.cs_mega_menu_nonce,
					'menu_id' : menu_id,
					'mm_enable' : mm_enable,
				},
				
				success: function (responce) {
					$( "#menu-to-edit" ).load( " #menu-to-edit", function() {
						$('#ciyashop_mega_menu_enable').find('.spinner').removeClass('is-active');
					});
				},
			});
		});
		
		
		/****************************
		::	 Mega Menu Icon Picker
		****************************/
		
		var fontawesome_icons = {};
		var fontawesome_icons_search = {};
		
		jQuery.each( ciyashop_icons, function( icons_key, icons_val ) {
			
			var icons = [];
			var icons_search = [];
			
			jQuery.each( icons_val, function( icon_key, icon_val ) {
				var IconKeys = Object.keys(icon_val);
				var IconValue = Object.values(icon_val);
				
				icons.push(IconKeys[0]); 
				icons_search.push(IconValue[0]); 
			});
			
			fontawesome_icons[icons_key] = icons;
			fontawesome_icons_search[icons_key] = icons_search;
		});
			
		/**************************
		:: 	Mega Menu Get Fields
		***************************/
		
		$(document).on( 'click', '.pgs-megamenu-setting', function(){
			
			if( !$('input[name="cs_megamenu_enable"]:checked').length > 0 ) return;
			
			var MenuDepth = '',
				parentMenuID = '',
				$this = $(this),
				menu_item_id = $(this).closest('.menu-item').find('input[name=nmi_item_id]').val(),
				menu_item_label = $('#edit-menu-item-title-'+menu_item_id).val(),
				menu_parent = $(this).parents('.menu-item'),
				classList = $this.parents('li').attr('class').split(/\s+/);
			
			$.each( classList, function( index, item ) {
				if( item.indexOf('menu-item-depth-') != -1 ){
					MenuDepth = item.replace('menu-item-depth-','');
					parentMenuID = $this.parents('.menu-item').prevAll("li.menu-item-depth-0:first").find('input[name="nmi_item_id"]').val();
				}
			});
			
			jQuery('#wpwrap').append('<div class="cs-overlay"></div>');
			
			$.ajax({
				url: ajaxurl,
				method: 'POST',
				dataType: 'json',
				data: {
					'action' : 'pgs_get_item_field',
					'ajax_nonce' : ciyashop_admin.cs_menu_get_item_nonce,
					'menu_item_id' : menu_item_id,
					'menu_item_label' : menu_item_label,
					'menu_depth' : MenuDepth,
					'parent_menu_id' : parentMenuID
				},
				
				success: function (fields) {
					
					jQuery('.cs-overlay').remove();
					$("#wpwrap").append(fields);
					
					$('.pgs_menu_field').each(function(key, val) {
						
						var field_show = false;
						var $depth = JSON.parse(($(this).attr('data-menu-depth')));
						
						if( $depth !== null ){
							jQuery.each( $depth, function( key, val ) {
								if(menu_parent.hasClass('menu-item-depth-' + val)){
									field_show = true;
								}
							});
						}else{
							field_show = true;
						}
						
						if( !field_show ) $(this).remove();
					});
					
					$('.pgs_menu-iconpicker').fontIconPicker({
						source: fontawesome_icons,
						searchSource: fontawesome_icons_search,
					});
					
					megamenu_field_dependabcy();
					
				},
			});
			
		});
		
		/****************************
		::	 Mega Menu OverLay
		****************************/
		
		$(document).on( 'click', '.pgs_menu_item-popup-close, .pgs_menu-overlay, .pgs_menu-close-element', function(e){
			e.preventDefault();
			
			$(this).closest('.pgs_menu_item-popup').parent('.pgs_menu-custom-fields').remove();
			$(this).closest('.pgs_menu-custom-fields').remove();
			$('.pgs_menu-overlay').remove();
		});
		
		
		/*******************************
		::	 Save Meaga Menu Settings
		*******************************/
		
		$(document).on( 'click', '.pgs_menu-save-element', function() {
			var $this = $(this);
			var menu_id = $('#menu').val();
			var menu_elements = $('.pgs_menu_popup-content');
			var menu_item_data = '';
			var menu_item_id = $(this).parents('.pgs_menu_item-popup').parent('.pgs_menu-custom-fields').data('menu_item_id');
			var menu_open_on_click = 0;
			var enable_menu_link = 0;
			
			var	menu_anchor = menu_elements.find('input[name=menu_anchor]').val(),
				menu_widget_area = menu_elements.find('select[name="menu_widget_area"] option:selected').val(),
				menu_columns = menu_elements.find('select[name="menu_columns"] option:selected').val(),
				html_block = menu_elements.find('select[name=html_block] option:selected').val(),
				menu_label = menu_elements.find('input[name=menu_label]').val(),
				menu_label_color = menu_elements.find('select[name=menu_label_color] option:selected').val(),
				menu_design = menu_elements.find('select[name=menu_design] option:selected').val(),
				menu_color_scheme = menu_elements.find('select[name=menu_color_scheme] option:selected').val(),
				mega_menu_design = menu_elements.find('select[name=mega_menu_design] option:selected').val(),
				mega_menu_width = menu_elements.find('input[name=mega_menu_width]').val(),
				mega_menu_height = menu_elements.find('input[name=mega_menu_height]').val(),
				menu_background_repeat = menu_elements.find('select[name=menu_background_repeat] option:selected').val(),
				menu_background_size = menu_elements.find('select[name=menu_background_size] option:selected').val(),
				menu_background_position = menu_elements.find('select[name=menu_background_position] option:selected').val();
				
			var menu_icon_picker = menu_elements.find('input[name=menu_icon]').prev('.icons-selector').find('.selected-icon > i').attr('class');
			menu_elements.find('input[name=menu_icon]').val(menu_icon_picker);
			
			if ($('input[name=menu_open_on_click]').is(":checked")){
				menu_open_on_click = 1;
			}
			
			if ($('input[name=enable_menu_link]').is(":checked")){
				enable_menu_link = 1;
			}
			
			menu_item_data = {
				menu_anchor : menu_anchor,
				menu_widget_area : menu_widget_area,
				menu_columns : menu_columns,
				html_block : html_block,
				menu_label : menu_label,
				menu_label_color : menu_label_color,
				menu_design : menu_design,
				menu_color_scheme : menu_color_scheme,
				mega_menu_design : mega_menu_design,
				mega_menu_width : mega_menu_width,
				mega_menu_height : mega_menu_height,
				menu_open_on_click : menu_open_on_click,
				enable_menu_link : enable_menu_link,
				menu_icon : menu_icon_picker,
				menu_background_repeat : menu_background_repeat,
				menu_background_size : menu_background_size,
				menu_background_position : menu_background_position,
			};

			$.ajax({
				url: ajaxurl,
				method: 'POST',
				dataType: 'json',
				data: {
					'action' : 'pgs_update_menu_item_data',
					'menu_item_data' : menu_item_data,
					'menu_item_id' : menu_item_id,
					'ajax_nonce' : ciyashop_admin.cs_menu_save_nonce,
				},
				beforeSend: function(){
					$($this).parents('.pgs_menu_item-popup').find('.pgs_menu_popup-content').addClass('loading');
				},
				success: function (data) {
					$($this).parents('.pgs_menu_item-popup').find('.pgs_menu_popup-content').removeClass('loading');
				},
			});
		});
		
		if ( $( "#redux-form-wrapper" ).length != 0 ) {

			  var instagram_api_url  = 'https://instagram.com/oauth/authorize/';
			  var redirect_uri       = ciyashop_admin.theme_options_url;
			  var response_type      = 'token';
			  var access_token_url   = '';
			  var at_received_status = false;
			  var at_received_data   = '';
			  var at_received_token  = '';

			// Fetch hash key
			var url_hash = location.hash.substring( 1 );
			if ( url_hash != '' ) {
				at_received_data = JSON.parse( '{"' + decodeURI( url_hash.replace( /&/g, "\",\"" ).replace( /=/g,"\":\"" ) ) + '"}' );
				if ( at_received_data.access_token != '' ) {
					  at_received_status = true;
					  at_received_token  = at_received_data.access_token;
				}
			}

			if ( at_received_status && at_received_token != '' ) {
				$( '#ciyashop_options-instagram_access_token #instagram_access_token' ).val( at_received_token );
				$( "#redux-sticky #redux_save" ).trigger( "click" );
				var redux_uri = window.location.toString();
				if ( redux_uri.indexOf('#access_token') > 0 ) {
					var new_url = redux_uri.substring( 0, redux_uri.indexOf('#') );
					window.history.replaceState( {}, document.title, new_url );
				}
			}

			$( document ).on(
				'click',
				"#generate_access_token-buttonsetgenerate_access_token",
				function() {
					$( "#redux_save" ).trigger( "click" );
					$( document ).ajaxComplete(
						function( event, xhr, settings ) {

							var client_id = $( '#instagram_client_id' ).val();

							if ( client_id == '' ) {
								alert( ciyashop_admin.cid_msg );
							} else {
								access_token_url      = instagram_api_url + "?" + "client_id=" + client_id + "&" + "redirect_uri=" + redirect_uri + "&" + "response_type=" + response_type;
								window.onbeforeunload = null;
								window.location.replace( access_token_url );
							}
						}
					);
				}
			);
		}

		if($('.system-status-content .cs-status-tooltip').length != 0 ){
			$('.system-status-content .cs-status-tooltip').tooltip({
				'container': '.ciyashop-welcome .system-status-content',
				'placement': 'bottom',
			});
		}
		
		if( $('body').hasClass('appearance_page_ciyashop-options') ){
			$('#redux_ajax_overlay').append('<div class="cs-redux-loader-dual-ring"></div>');
		}
		/***************************************************
			:: Custome Meta Box for Default template
		****************************************************/
		
		$('#page_template').change( function() {
			$('#pgs_custome_sidebar').toggle($(this).val() == 'default');
		}).change();

		/************************************
		:: Theme Option Search
		************************************/

		var $ThemeOptions = $('#redux-header');
		if( $ThemeOptions.length > 0 ) {
			
			var $searchForm = $('<div class="ciyashop-option-search"><form><input id="ciyashop-option-search-input" placeholder="' +  ciyashop_search_config.search_option_placeholder_text + '" type="text" /></form></div>'),
			$searchInput = $searchForm.find('input');
				
			// Add Seach Input Option in Theme options
			$ThemeOptions.find('.display_header').after($searchForm);

			$searchForm.find('form').submit(function(e) {
				e.preventDefault();
			});
				
			// Covert Object To Array
			var OptionsArray = $.map(ciyashop_search_config.reduxThemeOptions, function(value, index) {
				return [value];
			});
				
			var $autocomplete = $searchInput.autocomplete({
				source: function( request, response ) {
					response( OptionsArray.filter( function( value ) {
						return value.title.search( new RegExp(request.term, "i")) != -1;
					}) );
				},
				
				select: function( event, ui ) {
					
					var $field = $('[data-id="' + ui.item.id+ '"]');
					var new_position = 0;
					
					$('#' + ui.item.section_id + '_section_group_li_a').click();
					$('.redux-current-options').removeClass('redux-current-options');
					
					if($($field[0]).hasClass("redux-container-section")){
						new_position = $('#section-' + ui.item.id).offset();
						$('#section-' + ui.item.id).next('table.form-table-section').find('> tbody > tr').each(function(){
							$(this).addClass('redux-current-options');
						});
					} else {
						new_position = $($field).offset();
						$field.parent().parent().find('.redux_field_th').parents('tr').addClass('redux-current-options');
					}
					
					
					if( new_position ){
						$('html, body').stop().animate({ scrollTop: new_position.top - 150 }, 1500);
					}
				}
				
			}).data( "ui-autocomplete" );

			$autocomplete._renderItem = function( ul, item ) {
				
				var $icon = '';
				if( item.icon ){
					$icon = '<i class="el ' + item.icon + '"></i>';
				}
				
				var $SearchItemContent = $icon + item.title + '</span><br><span class="settting-path">' + item.path + '</span>';
				return $( "<li>" )
					.append( $SearchItemContent )
					.appendTo( ul );
			};
				
			$autocomplete._renderMenu = function( ul, items ) {
				var this_var = this;
				$.each( items, function( index, item ) {
					this_var._renderItemData( ul, item );
				});
				$( ul ).addClass( "ciyashop-reduxoptions-result" );
			};
		}
		
		/****************************
		::	 Admin Notice
		****************************/

		jQuery( document ).on( 'click', '.cs-admin-notice.cs-dismiss-admin-notice .notice-dismiss', function() {
			var notice_wrap    = $( this ).parent(),
				dismiss_option = $( notice_wrap ).data( 'dismiss_option' );

			$.ajax( ajaxurl, {
				type: 'POST',
				data: {
					action: 'ciyashop_dismiss_notice_handler',
					dismiss_option: dismiss_option,
					admin_ajax_nonce : ciyashop_admin.ciyashop_admin_nonce
				}
			} );
		});

		// Remove the cookies on change the column in shop page
		jQuery('#redux_save_sticky').on('click', function(){
			$.removeCookie('gridlist_view', { path: '/' });
			$.removeCookie('shop_filter_hide_show', { path: '/' });
		});
	});

})(jQuery);

/*****************************************
::	Mega Menu Field Dependency Function
******************************************/

function megamenu_field_dependabcy(){
	
	var menu_elements = jQuery('.pgs_menu_popup-content');
	var menu_design = menu_elements.find('select[name=menu_design] option:selected').val();
	var mega_menu_design = menu_elements.find('select[name=mega_menu_design] option:selected').val();
	var html_block = menu_elements.find('select[name=html_block] option:selected').val();
	
	if( menu_design != 'mega-menu' ){
		
		menu_elements.find('select[name=mega_menu_design]').parents('.pgs_menu_field').hide();
		menu_elements.find('input[name=mega_menu_width]').parents('.pgs_menu_field').hide();
		menu_elements.find('input[name=mega_menu_height]').parents('.pgs_menu_field').hide();
		menu_elements.find('select[name=menu_columns]').parents('.pgs_menu_field').hide();
		menu_elements.find('select[name=html_block]').parents('.pgs_menu_field').hide();
		
	} else {
		if( !menu_elements.find('select[name=menu_design]').is(":hidden") ){
			menu_elements.find('select[name=mega_menu_design]').parents('.pgs_menu_field').show();
			menu_elements.find('select[name=html_block]').parents('.pgs_menu_field').show();
			
			if( menu_elements.find('select[name=html_block]').length > 0 && html_block == '' && menu_elements.find('select[name=html_block]').is(":hidden") ){
				menu_elements.find('select[name=menu_columns]').parents('.pgs_menu_field').show();
			}
			
			if( !menu_elements.find('select[name=mega_menu_design]').is(":hidden") ){
				if( mega_menu_design != 'custom-size' ){
					menu_elements.find('select[name=mega_menu_width]').parents('.pgs_menu_field').hide();
					menu_elements.find('select[name=mega_menu_height]').parents('.pgs_menu_field').hide();
				}else{
					menu_elements.find('select[name=mega_menu_width]').parents('.pgs_menu_field').show();
					menu_elements.find('select[name=mega_menu_height]').parents('.pgs_menu_field').show();
				}
			}
		}
	}
	
	if( menu_elements.find('select[name=html_block]').length > 0 && html_block != '' && !menu_elements.find('select[name=html_block]').is(":hidden") ){
		menu_elements.find('.pgs_menu_field-image').hide();
		menu_elements.find('select[name=menu_background_repeat]').parents('.pgs_menu_field').hide();
		menu_elements.find('select[name=menu_background_size]').parents('.pgs_menu_field').hide();
		menu_elements.find('select[name=menu_background_position]').parents('.pgs_menu_field').hide();
		menu_elements.find('select[name=menu_columns]').parents('.pgs_menu_field').hide();
		
		
	} else {
		
		menu_elements.find('.pgs_menu_field-image').show();
		menu_elements.find('select[name=menu_background_repeat]').parents('.pgs_menu_field').show();
		menu_elements.find('select[name=menu_background_size]').parents('.pgs_menu_field').show();
		menu_elements.find('select[name=menu_background_position]').parents('.pgs_menu_field').show();
		
		if( menu_design == 'mega-menu' ){
			menu_elements.find('select[name=menu_columns]').parents('.pgs_menu_field').show();
		}
	}
	
	if( mega_menu_design != 'custom-size' ){
		menu_elements.find('input[name=mega_menu_width]').parents('.pgs_menu_field').hide();
		menu_elements.find('input[name=mega_menu_height]').parents('.pgs_menu_field').hide();
	}else{
		if( !menu_elements.find('select[name=mega_menu_design]').is(":hidden") ){
			menu_elements.find('input[name=mega_menu_width]').parents('.pgs_menu_field').show();
			menu_elements.find('input[name=mega_menu_height]').parents('.pgs_menu_field').show();
		}
	}
	
	if (!jQuery('input[name=menu_open_on_click]').is(":checked")){
		menu_elements.find('input[name=enable_menu_link]').parents('.pgs_menu_field').hide();
	}else{
		menu_elements.find('input[name=enable_menu_link]').parents('.pgs_menu_field').show();
	}
}

<script language="JavaScript" type="text/javascript">

var ajax_actions_path = "<?php echo base_url(); ?>users_mod/users_ctr/ajax_actions";
var redirect_path = "<?php echo base_url(); ?>register/step2/";

function ajax_fill_city (country_id)
{
	jQuery.post(ajax_actions_path, {
		action: "get_cities",
		country_id : country_id
		},
		function(data){
			$("#div_city").html(data);
		},
		'html'
	);
}

function get_regions ()
{
	var country_id = $("#country_list option:selected").val();
	
	$.ajax({
		type: "POST",
		url: ajax_actions_path,
		dataType: "html",
		data: {
			'action': 'get_regions_list',
			'country_id': country_id
		},
		beforeSend: function() {
			$("#ajax_loader_country").show();			
		},
		success: function(data) {
			$("#ajax_loader_country").hide();
			$("#region_list").removeAttr("disabled");
			$('#region_list option').remove();
			$("#region_list").append(data);
		},
		error: function(data) {}
	});	
} 

function get_cities ()
{
	var region_id = $("#region_list option:selected").val();
	
	$.ajax({
		type: "POST",
		url: ajax_actions_path,
		dataType: "html",
		data: {
			'action': 'get_cities_list',
			'region_id': region_id
		},
		beforeSend: function() {
			$("#ajax_loader_region").show();			
		},
		success: function(data) {
			$("#ajax_loader_region").hide();
			$("#city_list").removeAttr("disabled");
			$('#city_list option').remove();
			$("#city_list").append(data);
		},
		error: function(data) {}
	});	
}
	
function authorize()
{
		$("#m_reg_box")
		.ajaxStart(function(){
			$(this).show();
            $(this).html('<img alt="loading..." border="0" src="<?php echo base_url(); ?>images/ciraj.gif" valign="middle" />'); 
            $("#m_reg_box_1").hide();
		})
		.ajaxComplete(function(){
			$("#m_reg_box_1").show();	
		    $("#m_reg_box_2").hide();
	        $("#m_reg_box_3").hide();
			$(this).hide();
		});

	var rem_me = (document.getElementById('pp1').checked)? 1: 0;
	
	$.ajax({
			type: "POST", 
			url: ajax_actions_path,
			dataType: "json",
			data: { 'action':'authorize', 
					'login': $("#login").val(), 
					'password': $("#password").val(),
					'rem_me': rem_me 
				  },
			success: function(data)
			{
				var err = '';
				$("#login").css({'background-color':'white'});
				$("#password").css({'background-color':'white'});
				if(data.status<0)
				{
					err = data.login_err+' '+data.password_err;
						
					if( data.login_err!='' )
					{
						$("#login").css({'background-color':'#8bc53f'});
					}
					if( data.password_err!='' )
					{
						$("#password").css({'background-color':'#8bc53f'});
					}
				}
				else if(data.status<1)
				{
					err = '<?php echo lang('error_auth'); ?>';
				}
				else if(data.status==1)
				{
					$("#main_enter_box").hide();
					
					if (data.auth_success_path!='')
					{
						location = data.auth_success_path;
					}
					else
					{
						personifyPage($("#login").val());
					}
					return; 
				}
				
				$("#login_feedback").html(err);	
		   }
		});
}

function register()
{

		$("#m_reg_box")
		.ajaxStart(function(){
			$(this).show();
            $(this).html('<img border="0" src="<?php echo static_url(); ?>images/add-note-loader.gif" valign="middle" />'); 
            $("#m_reg_box_2").hide();
		})
		.ajaxComplete(function(){
			$("#m_reg_box_1").hide();
			$("#m_reg_box_3").hide();
			$("#m_reg_box_2").show();
			$(this).hide();
		});

	$.ajax({
			type: "POST", 
			url: ajax_actions_path,
			dataType: "json",
			data: { 'action':'register', 
					'username': $("#username").val(), 
					'email': $("#email").val()
				  },
			success: function(data)
			{					
				var message = '';
				$("#login").css({'background-color':'white'});
				$("#password").css({'background-color':'white'});
		
				
				if(data.status==1)
				{

					//$("#main_enter_box").hide();
					message = '<?php echo lang('start_using_account_without_active'); ?>';
					
					$("#register_form").hide();
				}
				else //if(data.status<0)
				{
					message = data.login_err+' '+data.email_err;
						
					if( data.login_err!='' )
					{
						$("#username").css({'background-color':'#8bc53f'});
					}
					if( data.email_err!='' )
					{
						$("#email").css({'background-color':'#8bc53f'});
					}	
				}
				
				$("#reg_feedback").html(message);
		   }
		});
}


function register_full()
{
	$.ajax({
		type: "POST",
		url: ajax_actions_path,
		dataType: "json",
		data: { 'action': "register_full",
				'username': $("#username").val(),
				'email': $("#email").val(),
                'pass1': $("#pass1").val(),
                'pass2': $("#pass2").val(),
                'birth_day' : $("#birth_day").val(),
                'birth_month' : $("#birth_month").val(),
                'birth_year' : $("#birth_year").val(),
                'country_id' : $("#country_list").val(),
                'region_id' : $("#region_list option:selected").val(),
                'city_id' : $("#city_list").val(),
                'userinfo' : $("#userinfo").val()
			  },
		success: function(data)
		{
			var message = '';

			if(data.status==1)
			{			
                //window.location.href = redirect_path + "/competition";  
				//  alert ("Вы успешно зарегистрированы!");
				  if ($("#from_where").val() == 'bibb')
				  	window.location = "<?php echo base_url().'bibb/100'?>";
				  else
					window.location = "<?php echo base_url().'profile/edit'?>";  	
		    }
			else //if(data.status<0)
			{     
				$("#error_msg").html('');   
				$("#username").css({'background-color':'white'});
				$("#email").css({'background-color':'white'});
				$("#pass1").css({'background-color':'white'});
				$("#pass1").css({'background-color':'white'});
				         
				if( data.login_err!='' )
				{
                    //alert (data.login_err);
					$("#username").css({'background-color':'#8bc53f'});

					var e = document.getElementById('error_msg');
		             							
					$("#error_msg").html( e.innerHTML + '<br>'+ data.login_err)  ;	
				}
				if( data.email_err!='' )
				{
                    //alert (data.email_err);
                    $("#email").css({'background-color':'#8bc53f'});
                    var e = document.getElementById('error_msg');
					$("#error_msg").html( e.innerHTML + '<br>'+ data.email_err);	
				}
                if( data.pass1_err!='' )
				{
                    //alert ('pass1' + data.pass1_err);
                    $("#pass1").css({'background-color':'#8bc53f'});
                    var e = document.getElementById('error_msg');
					$("#error_msg").html( e.innerHTML + '<br>'+ data.pass1_err);	
				}
                if( data.pass2_err!='' )
				{
                    //alert ('pass2' + data.pass2_err);
                    $("#pass2").css({'background-color':'#8bc53f'});
                    var e = document.getElementById('error_msg');
					$("#error_msg").html( e.innerHTML + '<br>'+ data.pass2_err);	
				}
               

			}
	   }
	});
}

function authorize_full()
{
	$.ajax({
			type: "POST", 
			url: ajax_actions_path,
			dataType: "json",
			data: { 'action':'authorize', 
					'login': $("#login").val(), 
					'password': $("#password").val()
				  },
			success: function(data)
			{
				var err = '';
				
				if(data.status<0)
				{
					err = data.login_err+' '+data.password_err;
						
					if( data.login_err!='' )
					{				        
						$("#login").css({'background-color':'#8BC53F'});
					}
					if( data.password_err!='' )
					{
						$("#password").css({'background-color':'#8BC53F'});
					}
				}
				else if(data.status<1)
				{
					err = '<?php echo lang('error_auth'); ?>';
				}
				else if(data.status==1)
				{
					  //window.location.href = redirect_path + "/competition";  
					  //alert ("Вы успешно вошли в систему");
					  // $("#bigbutton").show(); 
					 if ($("#from_where").val() == 'bibb')
					  	window.location = "<?php echo base_url().'bibb/100'?>";
					 else
						window.location = "<?php echo base_url().'profile/edit'?>";  					
				}
				
				$("#login_feedback").html(err);	
		   }
		});
}

function remember()
{
		$("#m_reg_box")
		.ajaxStart(function(){
			$(this).show();
            $(this).html('<img alt="loading..." border="0" src="<?php echo base_url(); ?>images/ciraj.gif" valign="middle" />'); 
            $("#m_reg_box_3").hide();
		})
		.ajaxComplete(function(){
			$("#m_reg_box_1").hide();
			$("#m_reg_box_2").hide();
			$("#m_reg_box_3").show();
			$(this).hide();
		});
	
	$.ajax({
			type: "POST", 
			url: ajax_actions_path,
			dataType: "json",
			data: { 'action':'remember', 
					'email': $("#email1").val()
				  },
			success: function(data)
			{
				var message = '';		
				if(data.status==1)
				{
					//$("#main_enter_box").hide();
					message = '<?php echo lang('passw_reset'); ?>';
					
					$("#remember_form").hide();
				}
				else //if(data.status<0)
				{
					message = data.email_err;
						
					if( data.email_err!='' )
					{
						$("#email").css({'background-color':'#8bc53f'});
					}	
				}
				
				$("#rem_feedback").html(message);
		   }
		});
}

function authorize_bibb()
{
	$.ajax({
			type: "POST", 
			url: ajax_actions_path,
			dataType: "json",
			data: { 'action':'authorize', 
					'login': $("#login").val(), 
					'password': $("#password").val()
				  },
			success: function(data)
			{
				var err = '';			
				if(data.status<0)
				{		
					if( data.login_err!='' )
					{				        
						alert (data.login_err.substring(6,data.login_err.length - 7));
						
					}
					if( data.password_err!='' )
					{
						alert (data.password_err.substring(6,data.password_err.length - 7));
					
					}
				}
				else if(data.status<1)
				{
					alert ('<?php echo lang('error_auth'); ?>');
				}
				else if(data.status==1)
				{
					  	window.location = "<?php echo base_url().'bibb/100'?>";					 					
				}
				
				
		   }
		});
}


</script>
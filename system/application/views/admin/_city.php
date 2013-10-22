<?php include('head.php'); ?>
<body class="pix">
	
	<div id="pix">
		
		<?php include('header.php'); ?>
	   
		<?php display_errors(); ?>
<script language="JavaScript" type="text/javascript">
var ajax_actions_path = "<?php echo base_url(); ?>users_mod/users_ctr/ajax_actions";
var city_tp="edit";
var region_tp="edit";
var  e_city_id=0;
function ajax_fill_region (country_id)
{
	jQuery.post(ajax_actions_path, {
		action: "get_regions",
        region_id: $("#region_list").val(),
		country_id: country_id
		},
		function(data){
            $("#div_region").html("");
            $("#div_region").html(data);
			},
			'html'
	);
}

function ajax_fill_city (region_id)
{
	jQuery.post(ajax_actions_path, {
		action: "get_cities",
		region_id: region_id,
        city_id:e_city_id
		},
		function(data){
         $("#div_city").html("");
         $("#div_city").html(data);
		},
		'html'
	);
}
function ajax_save_city (tp)
{
    $('#loader').show();
    e_region_id=$("#region_list").val();
    e_city_id=$("#city_list").val();
    jQuery.post(ajax_actions_path, {
		action: "save_city",
        state: city_tp,
        region_id: e_region_id,
		city_id: e_city_id,
        city_name: $("#city_edit_txt").val()
		},
		function(data){
          data = window["eval"]("(" + data + ")");
          alert("Сохранено!"+data.mes);
          if (data.err == '0') {
             city_cancel();
             ajax_fill_city ($("#region_list").val());
          }
          $('#loader').hide();
		},
		'html'
	);
}
function city_edit() {
 if ($("#city_list").val() ==0) {return;}
 $("#div_city_btn").hide();
 $("#div_city").hide();
 $("#city_edit_txt").show().focus();$("#city_edit_btn").show();
 lst=document.getElementById("city_list");
 $("#city_edit_txt").val(lst.options[lst.selectedIndex].text);
 //alert($('#city_list option[value="'+$('#city_list').val()+'"]').attr("text"));
}
function city_add() {
 city_tp="add";
 $("#div_city_btn").hide();
 $("#div_city").hide();
 $("#city_edit_txt").show().focus();$("#city_edit_btn").show();
 $("#city_edit_txt").focus().val("");
}
function city_save() {
 if (city_tp == "edit") {
    ajax_save_city("save");
 } else {
    ajax_save_city("add");
 }
}
function city_del() {
   if ($("#city_list").val() ==0) {return;}
   if (confirm("Удалять?")) {
        jQuery.post(ajax_actions_path, {
		action: "del_city",
		city_id: $("#city_list").val()
		},
		function(data){
          data = window["eval"]("(" + data + ")");
          alert(data.mes); ;
          if (data.err == '0') {
             city_cancel();
             ajax_fill_city ($("#region_list").val());
          }
          $('#loader').hide();
		},
		'html'
	);
   }
}
function city_cancel() {
 city_tp="edit";
 $("#div_city_btn").show();$("#div_city").show();
 $("#city_edit_txt").hide();$("#city_edit_btn").hide();
 $("#city_txt").val("");
 $("#loader").hide();
}
//----------------------- region ----------------
function ajax_save_region (tp)
{
    $('#loader').show();
    e_region_id =$("#region_list").val();
    e_country_id=$("#country_list").val();
    jQuery.post(ajax_actions_path, {
		action: "save_region",
        state: region_tp,
        region_id: e_region_id,
		country_id: e_country_id,
        region_name: $("#region_edit_txt").val()
		},
		function(data){
          data = window["eval"]("(" + data + ")");
          alert(data.mes);
          if (data.err == '0') {
             region_cancel();
             ajax_fill_region ($("#country_list").val());
          }
          $('#loader').hide();
		},
		'html'
	);
}
function region_edit() {
  if ($("#region_list").val() ==0) {return;}
  $("#div_region_btn").hide();$("#div_region").hide();
  $("#region_edit_txt").show().focus();$("#region_edit_btn").show();
  lst=document.getElementById("region_list");
  $("#region_edit_txt").val(lst.options[lst.selectedIndex].text);
}
function region_add() {
 region_tp="add";
 $("#div_region_btn").hide();
 $("#div_region").hide();
 $("#region_edit_txt").show().focus();$("#region_edit_btn").show();
 $("#region_edit_txt").focus().val("");
}
function region_save() {
  //if ($("#region_list").val() == 0) {return;}
  if (region_tp == "edit") {
        ajax_save_region("save");
  } else {
        ajax_save_region("add");
  }
}
function region_del() {
  if ($("#region_list").val() ==0) {return;}
  if (confirm("Удалять регион?\nБудут удалены все города из региона!")) {
      jQuery.post(ajax_actions_path, {
		action: "del_region",
		region_id: $("#region_list").val()
		},
		function(data){
          data = window["eval"]("(" + data + ")");
          alert(data.mes); ;
          if (data.err == '0') {
             city_cancel();
             ajax_fill_region ($("#country_list").val());
          }
          $('#loader').hide();
		},
		'html'
	);
  }
}
function region_cancel() {
 region_tp="edit";
 $("#div_region_btn").show();$("#div_region").show();
 $("#region_edit_txt").hide();$("#region_edit_btn").hide();
 $("#region_txt").val("");
 $("#loader").hide();
}
 </script>
		<div id="mainContent">
		   
			<div id="main_side_right">
			   
				<!-- start main_enter_box -->
				<?php if ( ! $user_id) echo $auth_block; ?>
				
                <!-- end main_enter_box -->
			    
			    <div id="admin_menu_box" <?php if ( ! $user_id) echo 'style="display: none;"'; ?>>    
				<!-- Start Main_side_und_box-->
				<div style="display:none;" id="main_side_und_box" class="nifty">
				   
					
					   
					<!--Center-->
					<div id="ms_center">
						<div id="ms_cen_zag" align="left"><span class="m_zag_txt"></span></div>
						<div id="ms_cen_box">
							<div id="admin_menu">
								
							</div>
						</div>
					</div>
					<!-- End center-->
					  
				
				  
				</div>   
				<!-- End Main_side_und_box-->
			   </div>
			   
			</div>  
			<!-- End main_side_right -->
			
			
			   
			<!-- main_cont_body -->
			<div id="main_cont_body">
			   	
			   	<!-- Start center main-->
				<div id="c_box">
               
                <?php if ($user_id) {?>
				<h3>Города. Место проживание</h3>
                 <div class="roundedBlock" style="width:650px">
                 <!-- b:NEW VERSTKA -->
                 <div class="tr">
                  <div class="br"><div class="br">
                	<table class="registration" width="650px">
            		<tbody>

                    	<tr width="50%"> <!-- b:field country -->
          				<td  class="lCol">
          					<?= lang('user_country'); ?>
          					<div id="div_country">
          						<select  class="s3"  name="country" id="country_list" onchange="javascript:ajax_fill_region(this.value);">
          							<option value=0><?php echo  lang('choose_country'); ?></option>
          							<?php foreach ($countries as $country) : ?>
          							<option value="<?php echo $country->id; ?>" <?php if($country->id==$user->country_id) echo "selected";?>><?php echo $country->country; ?></option>
          							<?php endforeach; ?>
          						</select>
          					</div>
          				</td>
			            </tr><!-- e:field country -->
            			<tr width="50%"> <!-- b:field region -->
            				<td  class="lCol">
            					<?= lang('user_region'); ?>
                                <br>
                                <input id="region_edit_txt" style="display:none;border:solid;border-color:green" type="text" size=30 />
            					<div id="div_region">
            						<select class="s3" name="region" id="region_list" maxlength="30" onchange="javascript:ajax_fill_city(this.value);">
            							<option value=0><?php echo  lang('choose_region'); ?></option>
            							<?php foreach ($regions as $region) : ?>
            							<option value="<?php echo $region->id; ?>" <?php if($region->id==$user->region_id) echo "selected";?>><?php echo $region->region; ?></option>
            							<?php endforeach; ?>
            						</select>
            					</div>
            				</td>
                            <td width="50%" valign="bottom">
                                <div id="div_region_btn" >
                                    <input type="button" title="Изменить регион"  value="Изменить" onclick="region_edit()">&nbsp;
                                    <input type="button" title="Добавить регион"  value="Добавить" onclick="region_add()">
                                    <input type="button" title="Удалить регион"   value="Удалить"  onclick="region_del()">
                                </div>
                                <div id="region_edit_btn" style="display:none">
                                   <input type="button" title="Сохранить регион" value="Сохранить" onclick="region_save()" />&nbsp;
                                   <input type="button" title="Отмена" value="Отмена" onclick="region_cancel()" />
                                </div>
                            </td><br>
            			</tr> <!-- e:field region -->
              			<tr width="50%" valign="bottom"> <!-- b:field city -->
              				<td class="lCol">
              					<?= lang('user_city'); ?>
                                <br>
                                 <input id="city_edit_txt" style="display:none;border:solid;border-color:green" type="text" size=30 />
                                 <div id="div_city">
              						<select class="s3" id="city_list" name="city"  >
              							<option value=0><?php echo  lang('choose_city'); ?></option>
              							<?php foreach ($cities as $city) : ?>
              							<option value="<?php echo $city->id; ?>" <?php if($city->id==$user->city_id) echo "selected";?>><?php echo $city->city; ?></option>
              							<?php endforeach; ?>
              						</select>
              					 </div>
              				</td>
                            <td width="50%" valign="bottom">
                                <div id="div_city_btn" >
                                    <input type="button" title="Изменить город" value="Изменить" onclick="city_edit()">&nbsp;
                                    <input type="button" title="Добавить город"     value="Добавить" onclick="city_add()">
                                    <input type="button" title="Удалить город"      value="Удалить" onclick="city_del()">
                                </div>
                                <div id="city_edit_btn" style="display:none">
                                   <input type="button" title="Сохранить" value="Сохранить" onclick="city_save()" />&nbsp;
                                   <input type="button" title="Отмена" value="Отмена" onclick="city_cancel()" />
                                </div>
                            </td><br>
              			</tr> <!-- e:field city -->
                        <tr></tr>

             </tbody>
             </table>
        	 &nbsp;<img id="loader" alt="saving..." border="0" src="<?php echo base_url() ?>static/images/add-note-loader.gif" style="display:none;" />
                     </div> <!-- class="tr" -->

                   </div></div>
            	 <!-- e:NEW VERSTKA -->
                </div>
               	<br />
				<?php } ?>
					
					
					
				</div>
				<!--End Center main-->
				   
				<br /><br /><br /><br />
			</div>
			<!-- end #main_cont_body -->
		   
		</div>   
		<!-- end #mainContent -->
		  
		<?php include('footer.php'); ?>
		
	</div>
	<!-- end #pix -->

</body>
</html>

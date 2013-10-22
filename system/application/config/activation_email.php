<?php
$config['activation_url'] = url('activate',
								array(
									'user_id'=>(isset($user_id)?$user_id:''),
									'act_code'=>(isset($activation_code)?$activation_code:'')
									)
								);

$config['activation_email'] = "
<p>Hello, ".(isset($login)?$login:'')."</p>
<p>Thanks for your registration at ".(isset($site_name)?$site_name:'').".<br/>
To finish it and activate your account please use the link below:<br/>
<a href=".$config['activation_url'].">".$config['activation_url']."</a><br/>
Hope to hear from you soon again, <br/>
".(isset($site_name)?$site_name:'')." administration team</p>
";
?>
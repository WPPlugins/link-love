<?php
function remove_array_empty_values($array, $remove_null_number = true)
	{	$new_array = array();
		$null_exceptions = array();
		foreach ($array as $key => $value)	{
			$value = trim($value);
			$value = strtolower($value);
			if($remove_null_number)
			{
				$null_exceptions[] = '0';
			}
			if(!in_array($value, $null_exceptions) && $value != "")
			{
				$new_array[] = $value;
			}
		}
		return $new_array;
	}

	if($_POST['ll_hidden'] == 'ok') {
			$ll_num = $_POST['ll_num'];
			$blacklist = $_POST['blacklist'];
			update_option('blacklist', $blacklist);
			
			$blacklist = str_replace(' ', "\n", $blacklist);
			$blacklist = str_replace("\r", "\n", $blacklist);
			$blacklist_email = explode("\n", $blacklist);
			$blacklist_email = remove_array_empty_values($blacklist_email, true);

			update_option('ll_num', $ll_num);
			update_option('blacklist_email', $blacklist_email);
	?>
			<div class="updated"><p><strong><?php _e('Option saved.' ); ?></strong></p></div>
	<?php }
?>

<div class="wrap">
<h2>Link Love Plugin Configuration</h2>
<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">

<p>The Link Love Wordpress plugin will count the number of approved comments each commentator has left on your blog and then reward he/she with a <b>Do-follow</b> link in the <b>Comment Author Link</b> field if the number of approved comments exceed the value below:</p>
<p><?php _e("Number of comments: " ); ?><input type="text" name="ll_num" value="<?php echo get_option('ll_num'); ?>" size="20"></p>  
<p>ex: 10, if the number of approved comments > or = 10, the commentator will have <b>Do-follow</b> link.</p>
<br />
<p><?php _e("Email blacklist: " ); ?></p>
<textarea name="blacklist" cols="70" rows="5"><?php echo (get_option('blacklist')); ?></textarea>
<p><em>(Write one email per line, all these email addresses will be filtered out from any link love)</em></p>
<p><em>(The list is NOT case sensitive, so abc@abc.com is the same as Abc@abc.com...)</em></p>
<input type="hidden" name="ll_hidden" value="ok"> 
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

</form>
</div>
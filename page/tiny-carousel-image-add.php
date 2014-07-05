<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
<?php
$tchsp_errors = array();
$tchsp_success = '';
$tchsp_error_found = FALSE;

// Preset the form fields
$form = array(
	'img_id' => '',
	'img_title' => '',
	'img_imageurl' => '',
	'img_link' => '',
	'img_linktarget' => '',
	'img_display' => '',
	'img_gal_id' => ''
);

// Form submitted, check the data
if (isset($_POST['tchsp_form_submit']) && $_POST['tchsp_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('tchsp_form_add');
	
	$form['img_title'] = isset($_POST['img_title']) ? $_POST['img_title'] : '';
	if ($form['img_title'] == '')
	{
		$tchsp_errors[] = __('Enter image title.', TCHSP_TDOMAIN);
		$tchsp_error_found = TRUE;
	}
	$form['img_imageurl'] = isset($_POST['img_imageurl']) ? $_POST['img_imageurl'] : '';
	if ($form['img_imageurl'] == '')
	{
		$tchsp_errors[] = __('Enter tiny-carousel popup url. url must start with either http or https.', TCHSP_TDOMAIN);
		$tchsp_error_found = TRUE;
	}

	$form['img_link'] = isset($_POST['img_link']) ? $_POST['img_link'] : '';
	$form['img_linktarget'] = isset($_POST['img_linktarget']) ? $_POST['img_linktarget'] : '';
	$form['img_display'] = isset($_POST['img_display']) ? $_POST['img_display'] : '';
	$form['img_gal_id'] = isset($_POST['img_gal_id']) ? $_POST['img_gal_id'] : '';

	//	No errors found, we can add this Group to the table
	if ($tchsp_error_found == FALSE)
	{
		$action = tchsp_cls_dbquery::tchsp_image_details_act($form, "ins");
		if($action == "sus")
		{
			$tchsp_success = __('New details was successfully added.', TCHSP_TDOMAIN);
		}
		elseif($action == "err")
		{
			$tchsp_success = __('Oops unexpected error occurred.', TCHSP_TDOMAIN);
			$tchsp_error_found = TRUE;
		}

		// Reset the form fields
		$form = array(
			'img_id' => '',
			'img_title' => '',
			'img_imageurl' => '',
			'img_link' => '',
			'img_linktarget' => '',
			'img_display' => '',
			'img_gal_id' => ''
		);
	}
}

if ($tchsp_error_found == TRUE && isset($tchsp_errors[0]) == TRUE)
{
	?>
	<div class="error fade">
		<p><strong><?php echo $tchsp_errors[0]; ?></strong></p>
	</div>
	<?php
}
if ($tchsp_error_found == FALSE && strlen($tchsp_success) > 0)
{
	?>
	<div class="updated fade">
		<p><strong><?php echo $tchsp_success; ?> <a href="<?php echo TCHSP_ADMINURL; ?>"><?php _e('Click here', TCHSP_TDOMAIN); ?></a> 
		<?php _e('to view the details', TCHSP_TDOMAIN); ?></strong></p>
	</div>
	<?php
}
?>
<script language="JavaScript" src="<?php echo TCHSP_URL; ?>page/setting.js"></script>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php _e(TCHSP_PLUGIN_DISPLAY, TCHSP_TDOMAIN); ?></h2>
	<form name="tchsp_form" method="post" action="#" onsubmit="return _tchsp_submit()"  >
      <h3><?php _e('Add Image Details', TCHSP_TDOMAIN); ?></h3>
      
		<label for="tag-a"><?php _e('Title', TCHSP_TDOMAIN); ?></label>
		<input name="img_title" type="text" id="img_title" value="" size="60" maxlength="255" />
		<p><?php _e('Enter image title.', TCHSP_TDOMAIN); ?></p>
		
		<label for="tag-a"><?php _e('Image path (URL)', TCHSP_TDOMAIN); ?></label>
		<input name="img_imageurl" type="text" id="img_imageurl" value="" size="60" maxlength="1024" />
		<p><?php _e('Enter picture url. url must start with either http or https.', TCHSP_TDOMAIN); ?></p>			
		
		<label for="tag-a"><?php _e('Link', TCHSP_TDOMAIN); ?></label>
		<input name="img_link" type="text" id="img_link" value="#" size="60" maxlength="1024" />
		<p><?php _e('When someone clicks on the picture, where do you want to send them. Link must start with either http or https.', TCHSP_TDOMAIN); ?></p>
		
		<label for="tag-a"><?php _e('Link target', TCHSP_TDOMAIN); ?></label>
		<select name="img_linktarget" id="img_linktarget">
			<option value='_blank'>Open New Window</option>
			<option value='_self'>Open Same Window</option>
		</select>
		<p><?php _e('Target attribute specifies where to open the link.', TCHSP_TDOMAIN); ?></p>
		
		<label for="tag-a"><?php _e('Display', TCHSP_TDOMAIN); ?></label>
		<select name="img_display" id="img_display">
			<option value='YES'>YES</option>
			<option value='NO'>NO</option>
		</select>
		<p><?php _e('Do you want the picture to show in your galler?', TCHSP_TDOMAIN); ?></p>
		
		<label for="tag-a"><?php _e('Gallery title', TCHSP_TDOMAIN); ?></label>
		<select name="img_gal_id" id="img_gal_id">
			<option value=''>Select</option>
			<?php
			$galleryData = array();
			$galleryData = tchsp_cls_dbquery::tchsp_gallery_details_select(0);
			if(count($galleryData) > 0 )
			{
				foreach ($galleryData as $data)
				{
					?><option value='<?php echo $data['gal_id'] ?>'><?php echo $data['gal_title'] ?></option><?php
				}
			}
			?>
		</select>
		<p><?php _e('Select gallery name for this picture.', TCHSP_TDOMAIN); ?></p>
			  
      <input type="hidden" name="tchsp_form_submit" id="tchsp_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button" value="<?php _e('Submit', TCHSP_TDOMAIN); ?>" type="submit" />
        <input name="publish" lang="publish" class="button" onclick="_tchsp_redirect()" value="<?php _e('Cancel', TCHSP_TDOMAIN); ?>" type="button" />
        <input name="Help" lang="publish" class="button" onclick="_tchsp_help()" value="<?php _e('Help', TCHSP_TDOMAIN); ?>" type="button" />
      </p>
	  <?php wp_nonce_field('tchsp_form_add'); ?>
    </form>
</div>
<p class="description"><?php echo TCHSP_OFFICIAL; ?></p>
</div>
<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
// Form submitted, check the data
if (isset($_POST['frm_tchsp_display']) && $_POST['frm_tchsp_display'] == 'yes')
{
	$did = isset($_GET['did']) ? $_GET['did'] : '0';
	
	$tchsp_success = '';
	$tchsp_success_msg = FALSE;
	
	// First check if ID exist with requested ID
	$result = tchsp_cls_dbquery::tchsp_image_details_count($did);
	
	if ($result != '1')
	{
		?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist.', TCHSP_TDOMAIN); ?></strong></p></div><?php
	}
	else
	{
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('tchsp_form_show');
			
			//	Delete selected record from the table
			tchsp_cls_dbquery::tchsp_image_details_delete($did);
			
			//	Set success message
			$tchsp_success_msg = TRUE;
			$tchsp_success = __('Selected record was successfully deleted.', TCHSP_TDOMAIN);
		}
	}
	
	if ($tchsp_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $tchsp_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php _e(TCHSP_PLUGIN_DISPLAY, TCHSP_TDOMAIN); ?></h2>
    <h3><?php _e('Image Details', TCHSP_TDOMAIN); ?></h3>
	<div class="tool-box">
	<?php
		$myData = array();
		$myData = tchsp_cls_dbquery::tchsp_image_details_select(0);
		?>
		<script language="JavaScript" src="<?php echo TCHSP_URL; ?>page/setting.js"></script>
		<form name="frm_tchsp_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
			<th class="check-column" scope="col">
			<input type="checkbox" name="tchsp_checkall" id="tchsp_checkall" onClick="_tchsp_checkall('frm_tchsp_display', 'chk_delete[]', this.checked);" /></th>
            <th scope="col"><?php _e('Image Title', TCHSP_TDOMAIN); ?></th>
			<th scope="col"><?php _e('Image URL', TCHSP_TDOMAIN); ?></th>
			<th scope="col"><?php _e('Link', TCHSP_TDOMAIN); ?></th>
			<th scope="col"><?php _e('Link Target', TCHSP_TDOMAIN); ?></th>
			<th scope="col"><?php _e('Display', TCHSP_TDOMAIN); ?></th>
			<th scope="col"><?php _e('Gallery Title', TCHSP_TDOMAIN); ?></th>
          </tr>
        </thead>
		<tfoot>
          <tr>
			<th class="check-column" scope="col">
			<input type="checkbox" name="tchsp_checkall" id="tchsp_checkall" onClick="_tchsp_checkall('frm_tchsp_display', 'chk_delete[]', this.checked);" /></th>
            <th scope="col"><?php _e('Image Title', TCHSP_TDOMAIN); ?></th>
			<th scope="col"><?php _e('Image URL', TCHSP_TDOMAIN); ?></th>
			<th scope="col"><?php _e('Link', TCHSP_TDOMAIN); ?></th>
			<th scope="col"><?php _e('Link Target', TCHSP_TDOMAIN); ?></th>
			<th scope="col"><?php _e('Display', TCHSP_TDOMAIN); ?></th>
			<th scope="col"><?php _e('Gallery Title', TCHSP_TDOMAIN); ?></th>
          </tr>
        </tfoot>
		<tbody>
			<?php 
			$i = 0;
			if(count($myData) > 0 )
			{
				foreach ($myData as $data)
				{
					?>
					<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
						<td align="left"><input name="chk_delete[]" id="chk_delete[]" type="checkbox" value="<?php echo $data['img_id'] ?>" /></td>
						<td><?php echo stripslashes($data['img_title']); ?>
						<div class="row-actions">
						<span class="edit">
						<a title="Edit" href="<?php echo TCHSP_ADMINURL; ?>&ac=edit&amp;did=<?php echo $data['img_id']; ?>"><?php _e('Edit', TCHSP_TDOMAIN); ?></a> | </span>
						<span class="trash">
						<a onClick="javascript:_tchsp_delete('<?php echo $data['img_id']; ?>')" href="javascript:void(0);"><?php _e('Delete', TCHSP_TDOMAIN); ?></a>
						</span> 
						</div>
						</td>
						<td><a href="<?php echo $data['img_imageurl']; ?>" target="_blank"><img src="<?php echo TCHSP_URL; ?>inc/image-icon.png" /></a></td>
						<td><a href="<?php echo $data['img_link']; ?>" target="_blank"><img src="<?php echo TCHSP_URL; ?>inc/linkicon.gif" /></a></td>
						<td>
						<?php  
						if($data['img_linktarget'] == "_blank")
						{
							echo "Open New Window";
						}
						else
						{
							echo "Open Same Window";
						}						
						?>
						</td>
						<td><?php echo $data['img_display']; ?></td>
						<td>
						<?php
						$galleryData = array();
						$galleryData = tchsp_cls_dbquery::tchsp_gallery_details_select($data['img_gal_id']);
						if(count($galleryData) > 0 )
						{
							foreach ($galleryData as $data)
							{
								echo $data['gal_title'];
							}
						}
						?>					
						</td>
					</tr>
					<?php 
					$i = $i+1; 
				} 	
			}
			else
			{
				?><tr><td colspan="7" align="center"><?php _e('No records available.', TCHSP_TDOMAIN); ?></td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('tchsp_form_show'); ?>
		<input type="hidden" name="frm_tchsp_display" value="yes"/>
      </form>	
	  <div class="tablenav">
	  <h2>
	  <a class="button add-new-h2" href="<?php echo TCHSP_ADMINURL; ?>&amp;ac=add"><?php _e('Add New', TCHSP_TDOMAIN); ?></a>
	  <a class="button add-new-h2" target="_blank" href="<?php echo TCHSP_FAV; ?>"><?php _e('Help', TCHSP_TDOMAIN); ?></a>
	  </h2>
	  </div>
	  <div style="height:5px"></div>
	<h3><?php _e('Plugin configuration option', TCHSP_TDOMAIN); ?></h3>
	<ol>
		<li><?php _e('Add plugin in the posts or pages using short code.', TCHSP_TDOMAIN); ?></li>
		<li><?php _e('Add directly in to theme using PHP code.', TCHSP_TDOMAIN); ?></li>
	</ol>
	<p class="description"><?php echo TCHSP_OFFICIAL; ?></p>
	</div>
</div>
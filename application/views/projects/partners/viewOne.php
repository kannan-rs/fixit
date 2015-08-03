<?php
	$editFn 		= "projectObj._partners.editForm({'openAs':'popup', 'popupType' : 2})";
	$deleteFn 		= "projectObj._partners.deleteRecord('".$partnerId."')";
	$partner		= $partners[0];
?>
<?php
	if(!$openAs || $openAs != "popup") {
?>
<div class="header-options">
	<h2>Partner Details</h2>
	<span class="options-icon">
		<span><a  class="step fi-page-edit size-21" href="javascript:void(0);" onclick="<?php echo $editFn; ?>" title="Edit Partner"></a></span>
		<span><a  class="step fi-deleteRow size-21 red delete" href="javascript:void(0);" onclick="<?php echo $deleteFn; ?>" title="Delete Partner"></a></span>	
	</span>
</div>
<?php
}
?>
<div class="clear"></div>
<form>
	<div class='form'>
	<!-- List all the Functions from database -->
			<div class='label'>Name:</div>
			<div><?php echo $partner->name; ?></div>
			<div class='label'>Company</div>
			<div><?php echo $partner->company_name; ?></div>
			<div class='label'>Type</div>
			<div><?php echo $partner->type; ?></div>
			<div class='label'>Status</div>
			<div><?php echo $partner->status; ?></div>
			<div class='label'>License</div>
			<div><?php echo $partner->license; ?></div>
			<?php
			echo $addressFile;
			?>
			<div class='label'>Office Email ID</div>
			<div><?php echo $partner->work_email_id; ?></div>
			<div class='label'>Office Number</div>
			<div><?php echo $partner->work_phone; ?></div>
			<div class='label'>Personal Email ID</div>
			<div><?php echo $partner->personal_email_id; ?></div>
			<div class='label'>Personal Mobile Number</div>
			<div><?php echo $partner->mobile_no; ?></div>
			<div class='label'>Prefered Mode for Contact</div>
			<div>
				<input type="hidden" name="prefContactDb" id="prefContactDb" value="<?php echo $partner->contact_pref; ?>" />
				<table class="innerOption">
					<tr>
						<td><input type="checkbox" name="prefContact" id="prefwNumber" value="wNumber" disabled></td>
						<td>Office Phone Number</td>
						<td><input type="checkbox" name="prefContact" id="prefwEmailId" value="wEmailId" disabled></td>
						<td>Office Email ID</td>
					</tr>
					<tr>
						<td><input type="checkbox" name="prefContact" id="prefmNumber" value="mNumber" disabled></td>
						<td>Personal Mobile Number</td>
						<td><input type="checkbox" name="prefContact" id="prefwEmailId" value="pEmailId" disabled></td>
						<td>Personal Email ID</td>
					</tr>
				</table>
			</div>
			<div class='label'>WebSite URL</div>
			<div><?php echo $partner->website_url; ?></div>
			<div class='label'>Created By</div>
			<div><?php echo $partner->created_by; ?></div>
			<div class='label'>Created On</div>
			<div><?php echo $partner->created_on; ?></div>
			<div class='label'>Updated By</div>
			<div><?php echo $partner->updated_by; ?></div>
			<div class='label'>Updated On</div>
			<div><?php echo $partner->updated_on; ?></div>
	<!-- </table> -->
	</div>
</form>
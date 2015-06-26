<?php
	$editFn 		= "projectObj._contractors.edit('".$contractorId."')";
	$deleteFn 		= "projectObj._contractors.deleteRecord('".$contractorId."')";
	$contractor		= $contractors[0];
?>
<?php
	if(!$openAs || $openAs != "popup") {
?>
<div class="header-options">
	<h2>Contractor Details</h2>
	<span class="options-icon">
		<span><a  class="step fi-page-edit size-21" href="javascript:void(0);" onclick="<?php echo $editFn; ?>" title="Edit Contractor"></a></span>
		<span><a  class="step fi-deleteRow size-21 red delete" href="javascript:void(0);" onclick="<?php echo $deleteFn; ?>" title="Delete Contractor"></a></span>	
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
			<div><?php echo $contractor->name; ?></div>
			<div class='label'>Company</div>
			<div><?php echo $contractor->company; ?></div>
			<div class='label'>Type</div>
			<div><?php echo $contractor->type; ?></div>
			<div class='label'>Type</div>
			<div><?php echo $contractor->type; ?></div>
			<div class='label'>License</div>
			<div><?php echo $contractor->license; ?></div>
			<div class='label'>BBB</div>
			<div><?php echo $contractor->bbb; ?></div>
			<div class='label'>Status</div>
			<div><?php echo $contractor->status; ?></div>
			<?php
			echo $addressFile;
			?>
			<div class='label'>Office Email ID</div>
			<div><?php echo $contractor->office_email; ?></div>
			<div class='label'>Office Number</div>
			<div><?php echo $contractor->office_ph; ?></div>
			<div class='label'>Mobile Number</div>
			<div><?php echo $contractor->mobile_ph; ?></div>
			<div class='label'>Prefered Mode for Contact</div>
			<div>
				<input type="hidden" name="prefContactDb" id="prefContactDb" value="<?php echo $contractor->prefer; ?>" />
				<table class="innerOption">
					
						<td><input type="checkbox" name="prefContact" id="prefContactEmailId" value="emailId" disabled></td>
						<td>Email</td>
						<td><input type="checkbox" name="prefContact" id="prefContactofficeNumber" value="officeNumber" disabled></td>
						<td>Office Phone</td>
						<td><input type="checkbox" name="prefContact" id="prefContactMobileNumber" value="mobileNumber" disabled></td>
						<td>Mobile Number</td>
					
				</table>
			</div>
			<div class='label'>WebSite URL</div>
			<div><?php echo $contractor->website_url; ?></div>
			<div class='label'>Serive Provided in ZipCode</div>
			<div><?php echo $contractor->service_area; ?></div>
			<div class='label'>Created By</div>
			<div><?php echo $contractor->created_by; ?></div>
			<div class='label'>Created On</div>
			<div><?php echo $contractor->created_on; ?></div>
			<div class='label'>Updated By</div>
			<div><?php echo $contractor->updated_by; ?></div>
			<div class='label'>Updated On</div>
			<div><?php echo $contractor->updated_on; ?></div>
	<!-- </table> -->
	</div>
</form>
<?php
	$editFn 		= "projectObj._contractors.edit('".$contractorId."')";
	$deleteFn 		= "projectObj._contractors.delete('".$contractorId."')";
	$contractor		= $contractors[0];
?>
<div class="header-options">
	<h2>Contractor Details</h2>
	<span class="options-icon">
		<span><a  class="step fi-page-edit size-21" href="javascript:void(0);" onclick="<?php echo $editFn; ?>" title="Edit Contractor"></a></span>
		<span><a  class="step fi-deleteRow size-21 red delete" href="javascript:void(0);" onclick="<?php echo $deleteFn; ?>" title="Delete Contractor"></a></span>	
	</span>
</div>

<div>
	<!-- List all the Functions from database -->
	<table cellspacing="0" class="viewOne projectViewOne">
		<tr>
			<td class='cell label'>Name:</td>
			<td class='cell' ><?php echo $contractor->name; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Company</td>
			<td class='cell' ><?php echo $contractor->company; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Type</td>
			<td class='cell' ><?php echo $contractor->type; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Type</td>
			<td class='cell' ><?php echo $contractor->type; ?></td>
		</tr>
		<tr>
			<td class='cell label'>License</td>
			<td class='cell' ><?php echo $contractor->license; ?></td>
		</tr>
		<tr>
			<td class='cell label'>BBB</td>
			<td class='cell' ><?php echo $contractor->bbb; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Status</td>
			<td class='cell' ><?php echo $contractor->status; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Flat No</td>
			<td class='cell' ><?php echo $contractor->address1; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Building Name</td>
			<td class='cell' ><?php echo $contractor->address2; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Street</td>
			<td class='cell' ><?php echo $contractor->address3; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Main</td>
			<td class='cell' ><?php echo $contractor->address4; ?></td>
		</tr>
		<tr>
			<td class='cell label'>City</td>
			<td class='cell' ><?php echo $contractor->city; ?></td>
		</tr>
		<tr>
			<td class='cell label'>State</td>
			<td class='cell' ><?php echo $contractor->state; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Country</td>
			<td class='cell' ><?php echo $contractor->country; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Pin Code</td>
			<td class='cell' ><?php echo $contractor->pin_code; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Office Email ID</td>
			<td class='cell' ><?php echo $contractor->office_email; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Office Number</td>
			<td class='cell' ><?php echo $contractor->office_ph; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Mobile Number</td>
			<td class='cell' ><?php echo $contractor->mobile_ph; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Prefered Mode for Contact</td>
			<td class='cell' >
				<input type="hidden" name="prefContactDb" id="prefContactDb" value="<?php echo $contractor->prefer; ?>" />
				<table class="innerOption">
					<tr>
						<td><input type="checkbox" name="prefContact" id="prefContactEmailId" value="emailId" disabled></td>
						<td>Email</td>
						<td><input type="checkbox" name="prefContact" id="prefContactofficeNumber" value="officeNumber" disabled></td>
						<td>Office Phone</td>
						<td><input type="checkbox" name="prefContact" id="prefContactMobileNumber" value="mobileNumber" disabled></td>
						<td>Mobile Number</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class='cell label'>WebSite URL</td>
			<td class='cell' ><?php echo $contractor->website_url; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Created By</td>
			<td class='cell' ><?php echo $contractor->created_by; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Created On</td>
			<td class='cell' ><?php echo $contractor->created_on; ?></td>
		</tr>	
		<tr>
			<td class='cell label'>Updated By</td>
			<td class='cell' ><?php echo $contractor->updated_by; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Updated On</td>
			<td class='cell' ><?php echo $contractor->updated_on; ?></td>
		</tr>
	</table>
</div>
<!-- Add Function Start -->
<?php
if(!$openAs || $openAs != "popup") {
?>
<h2>Create Contractor</h3>
<?php
}
?>
<form id="create_contractor_form" name="create_contractor_form" class="inputForm">
	<div class='form'>
		<div class="label">Name:</div>
		<div>
			<input type="text" name="name" id="name" value="" required placeholder="Contractor Name">
		</div>
		
		<div class="label">Company:</div>
		<div>
			<input type="text" name="company" id="company" value="" required placeholder="Company Name">
		</div>
		
		<div class="label">Type</div>
		<div>
			<input type="text" name="type" id="type" value="" placeholder="Contractor Type">
		</div>
		
		<div class="label">License</div>
		<div>
			<input type="text" name="license" id="license" value="" placeholder="Contractor License">
		</div>
		
		<div class="label">BBB</div>
		<div>
			<input type="text" name="bbb" id="bbb" value="" placeholder="BBB">
		</div>
		
		<div class="label">Status</div>
		<div>
			<select name="status" id="status" required>
				<option value="">--Select Status--</option>
				<option value="active">Active</option>
				<option value="inactive">Inactive</option>
			</select>
		</div>
		
		<?php
		echo $addressFile;
		?>
		<div class="label">Office Email ID:</div>
		<div>
			<input type="email" name="emailId" id="emailId" value="" placeholder="Email ID" required>
		</div>
		<div class="label">Office Number:</div>
		<div>
			<input type="number" name="contactPhoneNumber" id="contactPhoneNumber" value="" placeholder="Contact Phone Number" required>
		</div>
		<div class="label">Mobile Number:</div>
		<div>
				<input type="number" name="mobileNumber" id="mobileNumber" value="" placeholder="Contact Mobile Number" required>
		</div>
		<div class="label prefMode">Prefered Mode for Contact:</div>
		<div>
			<table class="innerOption">
				<tr>
					<td><input type="checkbox" name="prefContact" id="prefContactEmailId" value="emailId"></td>
					<td>Email</td>
					<td><input type="checkbox" name="prefContact" id="prefContactofficeNumber" value="officeNumber"></td>
					<td>Office Phone</td>
					<td><input type="checkbox" name="prefContact" id="prefContactMobileNumber" value="mobileNumber"></td>
					<td>Mobile Number</td>
				</tr>
			</table>
		</div>
		<div class="label">Website URL:</div>
		<div>
			<input type="text" name="websiteURL" id="websiteURL" value="" placeholder="Website URL" required>
		</div>
		<div class="label">Zip codes of Available Service Area:</div>
		<div>
			<textarea name="serviceZip" id="serviceZip" class="small-textarea" required></textarea>
		</div>
		<p class="button-panel">
			<button type="button" id="create_contractor_submit" onclick="projectObj._contractors.createValidate('<?php echo $openAs; ?>', '<?php echo $popupType;?>')">Create Contractor</button>
			<?php
			if($openAs == "popup") {
			?>
			<button type="button" id="cancelButton" onclick="projectObj._projects.closeDialog({popupType: '<?php echo $popupType; ?>'})">Cancel</button>
			<?php
			} else {
			?>
			<button type="reset" id="resetButton" onclick="">Clear</button>
			<?php	
			}
			?>
		</p>
	</div>
</form>
<!-- Add Function Ends -->
<!-- Add Function Start -->
<?php
if(!$openAs || $openAs != "popup") {
?>
<h2>Create Partner</h3>
<?php
}
?>
<form id="create_partner_form" name="create_partner_form" class="inputForm">
	<div class='form'>
		<div class="label">Partner Name:</div>
		<div>
			<input type="text" name="name" id="name" value="" required placeholder="Partner Name">
		</div>
		
		<div class="label">Company Name:</div>
		<div>
			<input type="text" name="company" id="company" value="" required placeholder="Company Name">
		</div>
		
		<div class="label">Type</div>
		<div>
			<input type="text" name="type" id="type" value="" placeholder="Partner Type">
		</div>
		
		<div class="label">License</div>
		<div>
			<input type="text" name="license" id="license" value="" placeholder="Partner License">
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

		<div class="label">Office Number:</div>
		<div>
			<input type="number" name="wNumber" id="wNumber" value="" placeholder="Office Phone Number" required>
		</div>
		<div class="label">Office Email ID:</div>
		<div>
			<input type="email" name="wEmailId" id="wEmailId" value="" placeholder="Office Email ID" required>
		</div>
		<div class="label">Mobile Number:</div>
		<div>
			<input type="number" name="pNumber" id="pNumber" value="" placeholder="Mobile Number" required>
		</div>
		<div class="label">Personal Email ID:</div>
		<div>
			<input type="email" name="pEmailId" id="pEmailId" value="" placeholder="Personal Email ID" required>
		</div>
		<div class="label prefMode">Prefered Mode for Contact:</div>
		<div>
			<table class="innerOption">
				<tr>
					<td><input type="checkbox" name="prefContact" id="prefwNumber" value="wNumber"></td>
					<td>Office Phone Number</td>
					<td><input type="checkbox" name="prefContact" id="prefwEmailId" value="wEmailId"></td>
					<td>Office Email ID</td>
				</tr>
				<tr>
					<td><input type="checkbox" name="prefContact" id="prefmNumber" value="mNumber"></td>
					<td>Personal Mobile Number</td>
					<td><input type="checkbox" name="prefContact" id="prefwEmailId" value="pEmailId"></td>
					<td>Personal Email ID</td>
				</tr>
			</table>
		</div>
		<div class="label">Website URL:</div>
		<div>
			<input type="text" name="websiteURL" id="websiteURL" value="" placeholder="Website URL" required>
		</div>
		<p class="button-panel">
			<button type="button" id="create_partner_submit" onclick="projectObj._partners.createValidate('<?php echo $openAs; ?>', '<?php echo $popupType;?>')">Create Partner</button>
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
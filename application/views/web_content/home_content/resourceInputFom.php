<form class="inputForm">
	<table>
		<tbody>
			<tr>
				<td>
					<textarea name="resourceEditSection" id="resourceEditSection" style="width: 100%; height: 170px;"><?php echo isset($resource) && !empty($resource) ? $resource : "-- No resource content present --"; ?></textarea>
				</td>
			</tr>
			<!-- <tr>
				<td>
					<p class="button-panel">
						<button type="button" id="create_partner_submit" onclick="_home_content.UpdateValidate();">Update News</button>
						<button type="reset" id="resetButton" onclick="_home_content.resetContent();">Reset</button>
					</p>
				</td>
			</tr> -->
		</tbody>
	</table>
</form>
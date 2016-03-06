<?php
// status = "" | warning | error | success
if(isset($status) && isset($statusText)) {
	$css = strtolower($status);
?>
<p class="note <?php echo $css?>"><?php echo $statusText; ?></p>
<?php
}
?>

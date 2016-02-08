<?php
if(isset($testimonialList) && count($testimonialList)) {
?>
<table>
    <tbody>
    <?php
    for($i = 0; $i < count($testimonialList); $i++) {
        $oneRecord = $testimonialList[$i];
    ?>
        <tr>
            <td>
            <table class="viewOne">
                <tbody>
                    <tr>
                        <td colspan="3">Summary : <?php echo $oneRecord->testimonial_summary; ?></td>
                        <td>
                            <span class="options-icon">
                                <span>
                                    <a class="step fi-page-edit size-21" href="javascript:void(0);" 
                                        onclick="_contractor_testimonial.editForm(event, <?php echo $oneRecord->testimonial_id; ?>)" 
                                        title="<?php echo $this->lang->line_arr('contractor->buttons_links->add_main_trend'); ?>">
                                    </a>
                                </span>
                                <span>
                                    <a class="step fi-deleteRow size-21 red delete" href="javascript:void(0);" 
                                        onclick="_contractor_testimonial.delete(event, <?php echo $oneRecord->testimonial_id; ?>)" 
                                        title="<?php echo $this->lang->line_arr('contractor->buttons_links->add_main_trend'); ?>">
                                    </a>
                                </span>
                            </span> 
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">Customer Name : <?php echo $oneRecord->testimonial_anonynomus_name; ?> </td>
                        <td colspan="2">Rating : <?php echo $oneRecord->testimonial_rating; ?> </td>
                    </tr>
                    <tr>
                        <td colspan="4"><?php echo $oneRecord->testimonial_descr; ?></td>
                    </tr>
                    <tr>
                        <td colspan="4"><?php echo explode(" ", $oneRecord->testimonial_date_for_view)[0]; ?></td>
                    </tr>
                </tbody>
            </table>
            </td>
        </tr>
    <?php
    } // For close
    ?>
    </tbody>
</table>
<?php
} else {
?>
    -No Testimonial Found-
<?php
}
?>
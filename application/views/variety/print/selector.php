<?php

defined('BASEPATH') or exit('No direct script access allowed');

// selector.php Chris Dart Mar 16, 2015 2:21:28 PM chrisdart@cerebratorium.com


$formats = array(
        "tabloid" => "Tabloid (12 x 18)",
        "statement" => "Statement (5.5 x 8.5)",
        "letter" => "Letter (8.5 x 5.5)",
        "shovel_foot" => "Shovel Foot (7 x 11)"
)
;
?>
<form
	id="print_posters"
	name="print_posters"
	action="<?=site_url("variety/print_result");?>"
	method="post">
	<input
		type="hidden"
		name="ids"
		id="ids"
		value="<?=$ids;?>" />
<p>
		<label for="format">Format</label>
		<?=form_dropdown("format",$formats);?>
		</p>
		<p>
		<input type="submit" class="button" value="Create"/>
		</p>

</form>
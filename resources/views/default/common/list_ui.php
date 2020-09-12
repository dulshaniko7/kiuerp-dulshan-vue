<?php
$url = $this->url;
$label = $this->label;
?>

var uiText = '<div class="clearfix text-center">';
	
	uiText += '<a href="<?php echo $url; ?>'+data+'" title="<?php echo $label; ?>"	><div class="btn btn-info btn-sm"><?php echo $label; ?></div></a>';

uiText += '</div>';

return uiText;
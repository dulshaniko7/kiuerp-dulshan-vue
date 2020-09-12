<?php
$url_column = $this->url_column;
$label_column = $this->label_column;
$label_prefix = $this->label_prefix;
?>

var url = full["<?php echo $url_column; ?>"];
var label = full["<?php echo $label_column; ?>"];

var uiText = '<div class="clearfix text-center">';
	
	uiText += '<a href="'+url+'" title="<?php echo $label_prefix; ?> '+label+'" target="_blank"><div class="btn btn-info btn-sm"><?php echo $label_prefix; ?> '+label+'</div></a>';

uiText += '</div>';

return uiText;
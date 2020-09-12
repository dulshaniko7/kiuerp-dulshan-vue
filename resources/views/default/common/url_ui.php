<?php
$label = $this->label;
$url_column = $this->url_column;
?>

var id = full["id"];
var url = full["<?php echo $url_column; ?>"];

var uiText = '<div class="clearfix text-center">';
	
	uiText += url+"<br>";
	uiText += '<a href="'+url+'" title="<?php echo $label; ?>" target="_blank"><div class="btn btn-info btn-sm"><?php echo $label; ?></div></a>';

uiText += '</div>';

return uiText;
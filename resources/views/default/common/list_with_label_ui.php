<?php
$url = $this->url;
$id_column = $this->id_column;
$label_prefix = $this->label_prefix;
?>

var id = full["<?php echo $id_column; ?>"];

var uiText = '<div class="clearfix text-center">';
	
	uiText += '<a href="<?php echo $url; ?>'+id+'" title="<?php echo $label_prefix; ?> '+data+'"><div class="btn btn-info btn-sm"><?php echo $label_prefix; ?> '+data+'</div></a>';

uiText += '</div>';

return uiText;
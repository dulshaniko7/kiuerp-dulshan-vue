var record_id = full["id"];

var uiText = '<div class="clearfix text-center">';

    if(full["created_at"] && full["created_at"] != "" && full["created_at"] != "")
    {
        uiText += '<div class="clearfix">';
            uiText += 'Created On :'+full["created_at"];
        uiText += '</div>';
    }
	
	if(full["updated_at"] && full["updated_at"] != "" && full["updated_at"] != "")
	{
		uiText += '<div class="clearfix">';
			uiText += 'Modified On :'+full["updated_at"];
		uiText += '</div>';
	}

	if(full["deleted_at"] && full["deleted_at"] != "" && full["deleted_at"] != "")
	{
		uiText += '<div class="clearfix">';
			uiText += 'Deleted On :'+full["deleted_at"];
		uiText += '</div>';
	}
uiText += '</div>';

return uiText;
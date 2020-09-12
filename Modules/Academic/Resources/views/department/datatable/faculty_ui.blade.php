var faculty = full["faculty"];

var uiText = '<div class="clearfix">';

    if(faculty !== null)
    {
        uiText += faculty.faculty_name;
    }
    uiText += '</div>';

return uiText;
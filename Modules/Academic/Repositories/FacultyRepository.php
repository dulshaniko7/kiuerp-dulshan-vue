<?php
namespace Modules\Academic\Repositories;

use App\Repositories\BaseRepository;
use Modules\Academic\Entities\Faculty;

class FacultyRepository extends BaseRepository
{
    public static function generateFacultyCode()
    {
        //get max faculty code
        $faculty_code = Faculty::withTrashed()->max("faculty_code");

        if($faculty_code!=null)
        {
            $faculty_code = intval($faculty_code);
            $faculty_code++;

            if($faculty_code<10)
            {
                $faculty_code = "0".$faculty_code;
            }
        }
        else
        {
            $faculty_code = "01";
        }

        return $faculty_code;
    }
}
<?php
namespace Modules\Academic\Repositories;

use App\Repositories\BaseRepository;
use Modules\Academic\Entities\Department;

class DepartmentRepository extends BaseRepository
{
    public static function generateDeptCode()
    {
        //get max faculty code
        $dept_code = Department::withTrashed()->max("dept_code");

        if($dept_code!=null)
        {
            $dept_code = intval($dept_code);
            $dept_code++;

            if($dept_code<10)
            {
                $dept_code = "0".$dept_code;
            }
        }
        else
        {
            $dept_code = "01";
        }

        return $dept_code;
    }

    public function display_faculty_as()
    {
        return view("academic::department.datatable.faculty_ui");
    }
}
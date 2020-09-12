<?php

namespace Modules\Academic\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Academic\Entities\Course;
use Modules\Academic\Entities\Department;
use Modules\Academic\Entities\Faculty;

class AcademicDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        factory(Faculty::class, 5)->create();
        factory(Department::class,10)->create();

        factory(Course::class,20)->create();
        // $this->call("OthersTableSeeder");
    }
}

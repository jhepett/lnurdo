<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $involvements = [
            ["involvement"=>"Member","points"=>40],
            ["involvement"=>"Participant","points"=>30],
            ["involvement"=>"Author","points"=>180],
        ];
        $colleges = ["College of Arts and Sciences", "College of Education", "College of Management and Entrepreneurship"];
        $units = [
            ["unit"=>"I.T", "college_id"=>1],
            ["unit"=>"Science", "college_id"=>2],
            ["unit"=>"Filipino", "college_id"=>2],
            ["unit"=>"Mapeh", "college_id"=>1],
            ["unit"=>"English", "college_id"=>2],
            ["unit"=>"Mathematics", "college_id"=>2],
            ["unit"=>"Tourism", "college_id"=>3],
        ];
        $userTypes = ["Admin","Teacher", "Unit Research Coordinator", "College Research Coordinator", "RDO"];
        
        foreach($colleges as $college){
            \App\Models\College::create(["college"=>$college]);
        }
        foreach($units as $unit){
            \App\Models\Unit::create($unit);
        }
        foreach($userTypes as $userType){
            \App\Models\UserType::create(["user_type"=>$userType]);
        }
        foreach($involvements as $involvement){
            \App\Models\Involvement::create($involvement);
        }

        $admin = new \App\Models\User;
        $admin->name = "Admin Rdo";
        $admin->gender = "1";
        $admin->contact_number = "09062919804";
        $admin->address = "Tacloban City";
        $admin->unit_id = "1";
        $admin->user_type_id = "1";
        $admin->email = "admin@admin.com";
        $admin->password = bcrypt('aaaaaaaa');
        $admin->status = "1";
        $admin->save();

        $ep = new \App\Models\EvaluationPeriod;
        $ep->from = "2021-08-6";
        $ep->to = "2021-05-10";
        $ep->save();


        $file = new Filesystem;
        $file->cleanDirectory('storage/app');
    }
}

<?php

namespace Database\Seeders;

use App\Models\RoleHierarchy;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleHierarchySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hierarchies = [
            //admin
            [
                'role_id' => 1,
                'manages_role_id' => 2,
            ],
            [
                'role_id' => 1,
                'manages_role_id' => 3,
            ],
            [
                'role_id' => 1,
                'manages_role_id' => 4,
            ],
            [
                'role_id' => 1,
                'manages_role_id' => 5,
            ],
            [
                'role_id' => 1,
                'manages_role_id' => 6,
            ],
            [
                'role_id' => 1,
                'manages_role_id' => 7,
            ],
            [
                'role_id' => 1,
                'manages_role_id' => 8,
            ],
            [
                'role_id' => 1,
                'manages_role_id' => 9,
            ],
            [
                'role_id' => 1,
                'manages_role_id' => 10,
            ],
            [
                'role_id' => 1,
                'manages_role_id' => 11,
            ],
            [
                'role_id' => 1,
                'manages_role_id' => 12,
            ],
            [
                'role_id' => 1,
                'manages_role_id' => 13,
            ],
            [
                'role_id' => 1,
                'manages_role_id' => 14,
            ],
            [
                'role_id' => 1,
                'manages_role_id' => 15,
            ],
            [
                'role_id' => 1,
                'manages_role_id' => 16,
            ],
            [
                'role_id' => 1,
                'manages_role_id' => 17,
            ],
            [
                'role_id' => 1,
                'manages_role_id' => 18,
            ],
            [
                'role_id' => 1,
                'manages_role_id' => 19,
            ],
            [
                'role_id' => 1,
                'manages_role_id' => 20,
            ],

            //cha tuyên uý
            [
                'role_id' => 2,
                'manages_role_id' => 3,
            ],
            [
                'role_id' => 2,
                'manages_role_id' => 4,
            ],
            [
                'role_id' => 2,
                'manages_role_id' => 5,
            ],
            [
                'role_id' => 2,
                'manages_role_id' => 6,
            ],
            [
                'role_id' => 2,
                'manages_role_id' => 7,
            ],
            [
                'role_id' => 2,
                'manages_role_id' => 8,
            ],
            [
                'role_id' => 2,
                'manages_role_id' => 9,
            ],
            [
                'role_id' => 2,
                'manages_role_id' => 10,
            ],
            [
                'role_id' => 2,
                'manages_role_id' => 11,
            ],
            [
                'role_id' => 2,
                'manages_role_id' => 12,
            ],
            [
                'role_id' => 2,
                'manages_role_id' => 13,
            ],
            [
                'role_id' => 2,
                'manages_role_id' => 14,
            ],
            [
                'role_id' => 2,
                'manages_role_id' => 15,
            ],
            [
                'role_id' => 2,
                'manages_role_id' => 16,
            ],
            [
                'role_id' => 2,
                'manages_role_id' => 17,
            ],
            [
                'role_id' => 2,
                'manages_role_id' => 18,
            ],
            [
                'role_id' => 2,
                'manages_role_id' => 19,
            ],
            [
                'role_id' => 2,
                'manages_role_id' => 20,
            ],

            //Thày phó tế
            [
                'role_id' => 3,
                'manages_role_id' => 4,
            ],
            [
                'role_id' => 3,
                'manages_role_id' => 5,
            ],
            [
                'role_id' => 3,
                'manages_role_id' => 6,
            ],
            [
                'role_id' => 3,
                'manages_role_id' => 7,
            ],
            [
                'role_id' => 3,
                'manages_role_id' => 8,
            ],
            [
                'role_id' => 3,
                'manages_role_id' => 9,
            ],
            [
                'role_id' => 3,
                'manages_role_id' => 10,
            ],
            [
                'role_id' => 3,
                'manages_role_id' => 11,
            ],
            [
                'role_id' => 3,
                'manages_role_id' => 12,
            ],
            [
                'role_id' => 3,
                'manages_role_id' => 13,
            ],
            [
                'role_id' => 3,
                'manages_role_id' => 14,
            ],
            [
                'role_id' => 3,
                'manages_role_id' => 15,
            ],
            [
                'role_id' => 3,
                'manages_role_id' => 16,
            ],
            [
                'role_id' => 3,
                'manages_role_id' => 17,
            ],
            [
                'role_id' => 3,
                'manages_role_id' => 18,
            ],
            [
                'role_id' => 3,
                'manages_role_id' => 19,
            ],
            [
                'role_id' => 3,
                'manages_role_id' => 20,
            ],

            //Trưởng giáo lý
            [
                'role_id' => 4,
                'manages_role_id' => 5,
            ],
            [
                'role_id' => 4,
                'manages_role_id' => 6,
            ],
            [
                'role_id' => 4,
                'manages_role_id' => 20,
            ],

            //Phó Giáo lý
            [
                'role_id' => 5,
                'manages_role_id' => 6,
            ],
            [
                'role_id' => 5,
                'manages_role_id' => 20,
            ],

            //Xứ đoàn trưởng
            [
                'role_id' => 7,
                'manages_role_id' => 8,
            ],
            [
                'role_id' => 7,
                'manages_role_id' => 9,
            ],
            [
                'role_id' => 7,
                'manages_role_id' => 10,
            ],
            [
                'role_id' => 7,
                'manages_role_id' => 11,
            ],
            [
                'role_id' => 7,
                'manages_role_id' => 12,
            ],
            [
                'role_id' => 7,
                'manages_role_id' => 13,
            ],
            [
                'role_id' => 7,
                'manages_role_id' => 14,
            ],
            [
                'role_id' => 7,
                'manages_role_id' => 15,
            ],
            [
                'role_id' => 7,
                'manages_role_id' => 16,
            ],
            [
                'role_id' => 7,
                'manages_role_id' => 17,
            ],
            [
                'role_id' => 7,
                'manages_role_id' => 18,
            ],
            [
                'role_id' => 7,
                'manages_role_id' => 19,
            ],
            [
                'role_id' => 7,
                'manages_role_id' => 20,
            ],

            //Xứ đoàn phó
            [
                'role_id' => 8,
                'manages_role_id' => 9,
            ],
            [
                'role_id' => 8,
                'manages_role_id' => 10,
            ],
            [
                'role_id' => 8,
                'manages_role_id' => 11,
            ],
            [
                'role_id' => 8,
                'manages_role_id' => 12,
            ],
            [
                'role_id' => 8,
                'manages_role_id' => 13,
            ],
            [
                'role_id' => 8,
                'manages_role_id' => 14,
            ],
            [
                'role_id' => 8,
                'manages_role_id' => 15,
            ],
            [
                'role_id' => 8,
                'manages_role_id' => 16,
            ],
            [
                'role_id' => 8,
                'manages_role_id' => 17,
            ],
            [
                'role_id' => 8,
                'manages_role_id' => 18,
            ],
            [
                'role_id' => 8,
                'manages_role_id' => 19,
            ],
            [
                'role_id' => 8,
                'manages_role_id' => 20,
            ],

            //Trưởng ngành nghĩa
            [
                'role_id' => 9,
                'manages_role_id' => 10,
            ],
            [
                'role_id' => 9,
                'manages_role_id' => 17,
            ],
            [
                'role_id' => 9,
                'manages_role_id' => 18,
            ],
            [
                'role_id' => 9,
                'manages_role_id' => 19,
            ],
            [
                'role_id' => 9,
                'manages_role_id' => 20,
            ],

            //Phó ngành nghĩa
            [
                'role_id' => 10,
                'manages_role_id' => 17,
            ],
            [
                'role_id' => 10,
                'manages_role_id' => 18,
            ],
            [
                'role_id' => 10,
                'manages_role_id' => 19,
            ],
            [
                'role_id' => 10,
                'manages_role_id' => 20,
            ],

            // Trưởng ngành thiếu
            [
                'role_id' => 11,
                'manages_role_id' => 12,
            ],
            [
                'role_id' => 11,
                'manages_role_id' => 17,
            ],
            [
                'role_id' => 11,
                'manages_role_id' => 18,
            ],
            [
                'role_id' => 11,
                'manages_role_id' => 19,
            ],
            [
                'role_id' => 11,
                'manages_role_id' => 20,
            ],

            //phó ngành thiếu
            [
                'role_id' => 12,
                'manages_role_id' => 17,
            ],
            [
                'role_id' => 12,
                'manages_role_id' => 18,
            ],
            [
                'role_id' => 12,
                'manages_role_id' => 19,
            ],
            [
                'role_id' => 12,
                'manages_role_id' => 20,
            ],

            // Trưởng ngành Ấu
            [
                'role_id' => 13,
                'manages_role_id' => 14,
            ],
            [
                'role_id' => 13,
                'manages_role_id' => 17,
            ],
            [
                'role_id' => 13,
                'manages_role_id' => 18,
            ],
            [
                'role_id' => 13,
                'manages_role_id' => 19,
            ],
            [
                'role_id' => 13,
                'manages_role_id' => 20,
            ],

            // Phó ngành Ấu
            [
                'role_id' => 14,
                'manages_role_id' => 17,
            ],
            [
                'role_id' => 14,
                'manages_role_id' => 18,
            ],
            [
                'role_id' => 14,
                'manages_role_id' => 19,
            ],
            [
                'role_id' => 14,
                'manages_role_id' => 20,
            ],

            // Trưởng ngành tiền Ấu
            [
                'role_id' => 15,
                'manages_role_id' => 16,
            ],
            [
                'role_id' => 15,
                'manages_role_id' => 17,
            ],
            [
                'role_id' => 15,
                'manages_role_id' => 18,
            ],
            [
                'role_id' => 15,
                'manages_role_id' => 19,
            ],
            [
                'role_id' => 15,
                'manages_role_id' => 20,
            ],
            
            // Phó ngành tiền Ấu
            [
                'role_id' => 16,
                'manages_role_id' => 17,
            ],
            [
                'role_id' => 16,
                'manages_role_id' => 18,
            ],
            
            [
                'role_id' => 16,
                'manages_role_id' => 19,
            ],
            [
                'role_id' => 16,
                'manages_role_id' => 20,
            ],

            // huynh trưởng
            [
                'role_id' => 17,
                'manages_role_id' => 18,
            ],
            [
                'role_id' => 17,
                'manages_role_id' => 19,
            ],
            
            [
                'role_id' => 17,
                'manages_role_id' => 20,
            ],

            // Dự trưởng
            [
                'role_id' => 18,
                'manages_role_id' => 19,
            ],
            [
                'role_id' => 18,
                'manages_role_id' => 20,
            ],

            // Đội trưởng
            [
                'role_id' => 19,
                'manages_role_id' => 20,
            ],
        ];

        foreach ($hierarchies as $hierarchy) {
            RoleHierarchy::create($hierarchy);
        }
    }
}

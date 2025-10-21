<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Regulation;
use Illuminate\Database\Seeder;

class RegulationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            // === ÁP DỤNG CHUNG CHO CẢ 4 ĐỐI TƯỢNG ===
            [
                'academic_year_id' => 1,
                'ordering' => 1,
                'description' => 'Tham dự thánh lễ Chúa nhật hàng tuần',
                'type' => 'plus',
                'points' => 15,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'academic_year_id' => 1,
                'ordering' => 2,
                'description' => 'Tham gia các giờ Chầu lượt, Tĩnh Tâm Thiếu Nhi',
                'type' => 'plus',
                'points' => 10,

                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'academic_year_id' => 1,
                'ordering' => 3,
                'description' => 'Tham gia các giờ Chầu Thánh Thể',
                'type' => 'plus',
                'points' => 5,

                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'academic_year_id' => 1,
                'ordering' => 4,
                'description' => 'Tham gia các hoạt động sinh hoạt ngoại khóa, trại hè, đá bóng, chiến dịch do xứ đoàn phát động',
                'type' => 'plus',
                'points' => 10,

                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'academic_year_id' => 1,
                'ordering' => 5,
                'description' => 'Tham gia các đội văn nghệ, đội lân, đội trống',
                'type' => 'plus',
                'points' => 5,

                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'academic_year_id' => 1,
                'ordering' => 6,
                'description' => 'Tổ chức các chương trình, hoạt động, chiến dịch một cách hiệu quả, thu hút sự tham gia.',
                'type' => 'plus',
                'points' => 10,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'academic_year_id' => 1,
                'ordering' => 7,
                'description' => 'Tổ chức các chương trình, hoạt động, chiến dịch một cách hiệu quả, thu hút sự tham gia.',
                'type' => 'plus',
                'points' => 10,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'academic_year_id' => 1,
                'ordering' => 8,
                'description' => 'Không tham gia các khóa học huấn luyện',
                'type' => 'minus',
                'points' => 30,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'academic_year_id' => 1,
                'ordering' => 9,
                'description' => 'Không tham gia giờ sinh hoạt, giờ họp (không phép). Tham gia không nghiêm túc, sử dụng điện thoại gây mất tập trung',
                'type' => 'minus',
                'points' => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'academic_year_id' => 1,
                'ordering' => 10,
                'description' => 'Không tham gia các hoạt động tết, hè (cắm trại, đá bóng,…), trung thu, … và các công tác chuẩn bị liên quan',
                'type' => 'minus',
                'points' => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'academic_year_id' => 1,
                'ordering' => 11,
                'description' => 'Không thực hiện nhiệm vụ được giao hoặc thực hiện không đúng yêu cầu, không đúng hạn.',
                'type' => 'minus',
                'points' => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'academic_year_id' => 1,
                'ordering' => 12,
                'description' => 'Có hành vi gây chia rẽ, mất đoàn kết trong đoàn, làm ảnh hưởng đến tinh thần chung',
                'type' => 'minus',
                'points' => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'academic_year_id' => 1,
                'ordering' => 13,
                'description' => 'Có hành vi vô lễ, không tôn trọng người khác, có thái độ kiêu ngạo, cứng đầu, hoặc không giữ gìn phẩm hạnh',
                'type' => 'minus',
                'points' => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'academic_year_id' => 1,
                'ordering' => 14,
                'description' => 'Không quan tâm, giúp đỡ các thành viên, không thể hiện tinh thần bác ái và sự hỗ trợ lẫn nhau.',
                'type' => 'minus',
                'points' => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],

            // === CÁC MỤC KỶ LUẬT CHUNG ===       
            [
                'academic_year_id' => 1,
                'ordering' => 15,
                'description' => 'Không ngồi đúng chỗ ngồi',
                'type' => 'minus',
                'points' => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'academic_year_id' => 1,
                'ordering' => 16,
                'description' => 'Không mặc đúng đồng phục TNTT',
                'type' => 'minus',
                'points' => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'academic_year_id' => 1,
                'ordering' => 17,
                'description' => 'Sử dụng lời lẽ tục tĩu, chửi thề, đánh nhau,… gây mất đoàn kết trong khuôn viên nhà thờ, nhà xứ, nhà giáo lý',
                'type' => 'minus',
                'points' => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'academic_year_id' => 1,
                'ordering' => 18,
                'description' => 'Xả rác không đúng nơi quy định, phá hoại môi trường trong khuôn viên nhà thờ, nhà xứ, nhà giáo lý',
                'type' => 'minus',
                'points' => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],

             // === ÁP DỤNG CHUNG CHO CẢ 4 ĐỐI TƯỢNG ===
             [
                'academic_year_id' => 2,
                'ordering' => 1,
                'description' => 'Tham dự thánh lễ Chúa nhật hàng tuần',
                'type' => 'plus',
                'points' => 15,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'academic_year_id' => 2,
                'ordering' => 2,
                'description' => 'Tham gia các giờ Chầu lượt, Tĩnh Tâm Thiếu Nhi',
                'type' => 'plus',
                'points' => 10,

                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'academic_year_id' => 2,
                'ordering' => 3,
                'description' => 'Tham gia các giờ Chầu Thánh Thể',
                'type' => 'plus',
                'points' => 5,

                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'academic_year_id' => 2,
                'ordering' => 4,
                'description' => 'Tham gia các hoạt động sinh hoạt ngoại khóa, trại hè, đá bóng, chiến dịch do xứ đoàn phát động',
                'type' => 'plus',
                'points' => 10,

                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'academic_year_id' => 2,
                'ordering' => 5,
                'description' => 'Tham gia các đội văn nghệ, đội lân, đội trống',
                'type' => 'plus',
                'points' => 5,

                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'academic_year_id' => 2,
                'ordering' => 6,
                'description' => 'Tổ chức các chương trình, hoạt động, chiến dịch một cách hiệu quả, thu hút sự tham gia.',
                'type' => 'plus',
                'points' => 10,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'academic_year_id' => 2,
                'ordering' => 7,
                'description' => 'Tổ chức các chương trình, hoạt động, chiến dịch một cách hiệu quả, thu hút sự tham gia.',
                'type' => 'plus',
                'points' => 10,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'academic_year_id' => 2,
                'ordering' => 8,
                'description' => 'Không tham gia các khóa học huấn luyện',
                'type' => 'minus',
                'points' => 30,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'academic_year_id' => 2,
                'ordering' => 9,
                'description' => 'Không tham gia giờ sinh hoạt, giờ họp (không phép). Tham gia không nghiêm túc, sử dụng điện thoại gây mất tập trung',
                'type' => 'minus',
                'points' => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'academic_year_id' => 2,
                'ordering' => 10,
                'description' => 'Không tham gia các hoạt động tết, hè (cắm trại, đá bóng,…), trung thu, … và các công tác chuẩn bị liên quan',
                'type' => 'minus',
                'points' => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'academic_year_id' => 2,
                'ordering' => 11,
                'description' => 'Không thực hiện nhiệm vụ được giao hoặc thực hiện không đúng yêu cầu, không đúng hạn.',
                'type' => 'minus',
                'points' => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'academic_year_id' => 2,
                'ordering' => 12,
                'description' => 'Có hành vi gây chia rẽ, mất đoàn kết trong đoàn, làm ảnh hưởng đến tinh thần chung',
                'type' => 'minus',
                'points' => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'academic_year_id' => 2,
                'ordering' => 13,
                'description' => 'Có hành vi vô lễ, không tôn trọng người khác, có thái độ kiêu ngạo, cứng đầu, hoặc không giữ gìn phẩm hạnh',
                'type' => 'minus',
                'points' => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'academic_year_id' => 2,
                'ordering' => 14,
                'description' => 'Không quan tâm, giúp đỡ các thành viên, không thể hiện tinh thần bác ái và sự hỗ trợ lẫn nhau.',
                'type' => 'minus',
                'points' => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],

            // === CÁC MỤC KỶ LUẬT CHUNG ===       
            [
                'academic_year_id' => 2,
                'ordering' => 15,
                'description' => 'Không ngồi đúng chỗ ngồi',
                'type' => 'minus',
                'points' => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'academic_year_id' => 2,
                'ordering' => 16,
                'description' => 'Không mặc đúng đồng phục TNTT',
                'type' => 'minus',
                'points' => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'academic_year_id' => 2,
                'ordering' => 17,
                'description' => 'Sử dụng lời lẽ tục tĩu, chửi thề, đánh nhau,… gây mất đoàn kết trong khuôn viên nhà thờ, nhà xứ, nhà giáo lý',
                'type' => 'minus',
                'points' => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'academic_year_id' => 2,
                'ordering' => 18,
                'description' => 'Xả rác không đúng nơi quy định, phá hoại môi trường trong khuôn viên nhà thờ, nhà xứ, nhà giáo lý',
                'type' => 'minus',
                'points' => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'academic_year_id' => 2,
                'ordering' => 19,
                'description' => 'Tham gia vào các tệ nạn xã hội (cờ bạc, hút thuốc lá điện tử, trộm cắp,…), các hành vi phá hoại của công',
                'type' => 'minus',
                'points' => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        foreach ($data as $item) {
            Regulation::create($item);
        }
    }
}

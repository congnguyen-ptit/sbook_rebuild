<?php

use Illuminate\Database\Seeder;
use App\Eloquent\Book;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('books')->truncate();
        $data = [
            [
                'title' => 'Cõi người rung chuông tận thế',
                'slug' => strtoupper(Str::random(6)),
                'author' => 'Hồ Anh Thái',
                'total_pages' => '300',
                'avg_star' => 0,
                'sku' => 'AGSVNJKS',
                'count_viewed' => 0,
                'description' => 'Cõi người rung chuông tận thế được tác giả Hồ Anh Thái viết từ 1996, in lần đầu vào 2002, sau đó tái bản nhiều lần tại Việt Nam; năm 2012 Nhà xuất bản Đại học Tổng hợp Texas - Mỹ ấn hành, cả bản bìa cứng và bản điện tử với nhan đề Apocalypse Hotel - Khách sạn ngày tận thế. Cuốn tiểu thuyết mở đầu bằng một cái chết kỳ lạ ở một bờ biển đẹp. Và tiếp đó là dồn dập những sự kiện hấp dẫn nghẹt thở như truyện trinh thám hình sự. Nhưng nó xuất sắc và có sức sống trong lòng bạn đọc, trong đánh giá của giới chuyên môn ở tầm vóc xã hội của vấn đề: cái xấu, và cái ác sẽ bị trả giá thế nào. Mạch truyện liền tù tì những cái chết, sự trả thù, nhưng xen vào đó là ngôn ngữ người Việt hôm nay. Không lôi thôi lòng thòng. Chi tiết cô đặc và đắt. Nó ám ảnh ở dòng mở đầu như phũ phàng và tiếng thở dài nhẹ nhàng khi cô gái được giải khỏi lời nguyền oan nghiệt, trở lại là người bình thường được sống như người chung quanh cô khi kết sách. Vẫn là cách nghĩ của người Việt. Qua lửa qua máu qua nước... là cõi bình yên.” “Yêu cuộc sống mà như không chịu thừa nhận tình yêu ấy vì nó không được như mình muốn. Nhưng yêu và muốn bảo vệ nó nên tìm ra được cái ý như là có siêu nhân, có con người ảo đi cùng tác giả để bảo vệ chân lý cuộc sống. Người ta gấp sách lại và nghĩ như vậy về Cõi người rung chuông tận thế.” (nhà văn LÊ MINH KHUÊ)',
            ],
            [
                'title' => 'Hài Hước Một Chút Thế Giới Sẽ Khác Đi',
                'slug' => strtoupper(Str::random(6)),
                'author' => 'Lưu Trấn Hồng',
                'total_pages' => '300',
                'avg_star' => 0,
                'sku' => 'AGSVNJKS',
                'count_viewed' => 0,
                'description' => '"Hài hước là bộ trang phục đẹp nhất mà con người có thể khoác lên mình để xử li các mối quan hệ giao tiếp" "Người hài hước luôn có trái tim nóng" "Nếu không biết đến sự hài hước và thú vị thì cuộc sống của con người thực sự quá khổ sợ" "Người hài hước là người có khả năng thích nghi tốt nhất" "Hài hước là ánh sáng của trí tuệ" Bởi vậy, trong cuộc sống, thêm một chút hài hước, bớt vài phần cứng nhắc là chúng ta dã có thểm niềm vui là lòng yêu đời.',
            ],
            [
                'title' => 'Đắc nhân tâm',
                'slug' => strtoupper(Str::random(6)),
                'author' => 'Dale Carnegie',
                'total_pages' => '300',
                'avg_star' => 0,
                'sku' => 'AGSVNJKS',
                'count_viewed' => 0,
                'description' => 'Đắc Nhân Tâm - Được lòng người, là cuốn sách đưa ra các lời khuyên về cách thức cư xử, ứng xử và giao tiếp với mọi người để đạt được thành công trong cuộc sống. Gần 80 năm kể từ khi ra đời, Đắc Nhân Tâm là cuốn sách gối đầu giường của nhiều thế hệ luôn muốn hoàn thiện chính mình để vươn tới một cuộc sống tốt đẹp và thành công. Đắc Nhân Tâm cụ thể và chi tiết với những chỉ dẫn để dẫn đạo người, để gây thiện cảm và dẫn dắt người khác,... những hướng dẫn ấy, qua thời gian, có thể không còn thích hợp trong cuộc sống hiện đại nhưng nếu người đọc có thể cảm và hiểu được những thông điệp tác giả muốn truyền đạt thì việc áp dụng nó vào cuộc sống trở nên dễ dàng và hiệu quả. Đắc Nhân Tâm, từ một cuốn sách, hôm nay đã trở thành một danh từ để chỉ một lối sống mà ở đó con người ta cư xử linh hoạt và thấu tình đạt lý. Lý thuyết muôn thuở vẫn là những quy tắc chết, nhưng Nhân Tâm là sống, là biến đổi. Bạn hãy thử đọc "Đắc Nhân tâm" và tự mình chiêm nghiệm những cái đang diễn ra trong đời thực hiện hữu, chắc chắn bạn sẽ có những bài học cho riêng mình.',
            ],
            [
                'title' => 'Hạ Đỏ',
                'slug' => strtoupper(Str::random(6)),
                'author' => 'Nguyễn Nhật Ánh',
                'total_pages' => '300',
                'avg_star' => 0,
                'sku' => 'AGSVNJKS',
                'count_viewed' => 0,
                'description' => 'Câu chuyện bắt đầu với bối cảnh làng quê quen thuộc của truyện Nguyễn Nhật Ánh. Cậu bé thành phố Chương được mẹ cho về quê ở với dì để nâng cao sức khỏe. Trong những ngày này, cùng ăn cùng ngủ cùng chơi với cậu là 2 người em họ - Nhạn và Dế. Cũng tình cờ mà Chương quen Thơm - bà la sát của xóm nhưng thật ra lại là một cô bé xinh xắn, hay tò mò và luôn thích chơi cùng Chương. Song song với những cuộc dạo chơi trong làng, những trận đánh nhau với tụi xóm Miễu, những ngày học võ với anh Thoảng, Chương đã gặp được mối tình đầu của mình - Út Thêm. Yêu Út, thương Út nhưng lại không thể nói, chỉ có thể thể hiện bằng hành động dạy 2 chị em Út đọc chữ. Truyện kết thúc với nỗi đâu buồn của Chương và Thơm. Chương buồn vì Út, Thơm buồn vì Chương. Tình đầu - thật lãng mạn mà cũng thật xót xa.',
            ],
            [
                'title' => 'Rừng Nauy',
                'slug' => strtoupper(Str::random(6)),
                'author' => 'Murakami Haruki',
                'total_pages' => '300',
                'avg_star' => 0,
                'sku' => 'AGSVNJKS',
                'count_viewed' => 0,
                'description' => 'Rừng Na-Uy (tiếng Nhật: ノルウェイの森, Noruwei no mori) là tiểu thuyết của nhà văn Nhật Bản Murakami Haruki, được xuất bản lần đầu năm 1987. Với thủ pháp dòng ý thức, cốt truyện diễn tiến trong dòng hồi tưởng của nhân vật chính là chàng sinh viên bình thường Watanabe Tōru. Cậu ta đã trải qua nhiều cuộc tình chớp nhoáng với nhiều cô gái trẻ ưa tự do. Nhưng cậu ta cũng có những mối tình sâu nặng, điển hình là với Naoko, người yêu của người bạn thân nhất của cậu, một cô gái không ổn định về cảm xúc, và với Midori, một cô gái thẳng thắn và hoạt bát. Các nhân vật trong truyện hầu hết là những con người cô đơn móc nối với nhau. Có những nhân vật đã phải tìm đến cái chết để giải thoát khỏi nỗi đau đớn ấy',
            ],
            [
                'title' => 'Đêm Đầu Tiên',
                'slug' => strtoupper(Str::random(6)),
                'author' => 'Marc Levy',
                'total_pages' => '300',
                'avg_star' => 0,
                'sku' => 'AGSVNJKS',
                'count_viewed' => 0,
                'description' => '“Truyền thuyết kể rằng đứa trẻ trong bụng mẹ biết toàn bộ bí mật về Sáng thế, về khởi nguồn của thế giới cho đến khi kết thúc. Lúc sinh ra, một vị sứ giả ghé xuống nôi của đứa trẻ và đặt một ngón tay lên môi của nó, để nó không bao giờ tiết lộ điều bí mật đã được phó thác cho nó, bí mật về sự sống. Ngón tay đặt trên môi xóa đi vĩnh viễn ký ức của đứa trẻ để lại một dấu vết. Dấu vết này, tất cả chúng ta đều có ở môi trên, trừ tôi. Ngày tôi sinh ra, vị sứ giả đã quên ghé thăm tôi, và tôi vẫn còn nhớ mọi chuyện...” Từ vùng cao nguyên lồng lộng của Êtiôpia đến thảo nguyên băng giá miền Bắc Uran, Marc Levy, qua cuốn tiểu thuyết mới này, đã kết thúc thiên sử thi giàu trí tưởng tượng khởi nguồn từ Ngày Đầu Tiên.',
            ],
        ];
        foreach ($data as $item) {
            Book::create($item);
        }
    }
}

-- Adminer 4.8.1 MySQL 10.4.32-MariaDB dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `feedback` (`id`, `content`, `path`, `created_at`) VALUES
(1,	'Test lần cuối',	'uploads/6641c160bf0f6.png',	'2024-05-13 14:29:36'),
(2,	'Mong BGĐ xem xét chia từng ô cho nhà xe . Giống như  trong các siêu thị . Để tránh tình trạng để xe xéo . Chèn xe vừa mất thẩm mĩ vừa trầy xe . Nếu có đi trễ vẫn ko phải sợ mất chỗ để xe, hay bị đi dời xe !',	'uploads/666b82cf4cfe7.jpeg',	'2024-06-14 06:37:51'),
(3,	'Test',	'uploads/667cbd33986b0.jpg',	'2024-06-27 08:15:31');

DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `QuestionText` text DEFAULT NULL,
  `AnswerA` varchar(50) DEFAULT NULL,
  `AnswerB` varchar(50) DEFAULT NULL,
  `AnswerC` varchar(50) DEFAULT NULL,
  `CorrectAnswer` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `questions` (`ID`, `QuestionText`, `AnswerA`, `AnswerB`, `AnswerC`, `CorrectAnswer`) VALUES
(1,	'Công ty có bao nhiêu Workshop?',	'5',	'6',	'7',	'B'),
(2,	'Công nhân viên được ăn món nước vào thứ mấy hàng tuần?',	'Thứ 3',	'Thứ 5',	'Thứ 7',	'B'),
(3,	'Giám đốc hiện tại là ai?',	'Mr. Nakashima Jun',	'Mr Takashi Kawashita',	'Mr. Yamauchi',	'A'),
(4,	'Công nhân viên công ty chào lễ những ngày nào trong tuần?',	'Thứ 3 & Thứ 6',	'Thứ 3 & Thứ 5',	'Thứ 2 & Thứ 5',	'C'),
(5,	'Màu nón của nhân viên QA?',	'Xanh da trời',	'Xanh lá',	'Xanh than',	'A'),
(6,	'Màu nón của nhân viên Bảo trì?',	'Xanh da trời',	'Xanh lá',	'Xanh than',	'C'),
(7,	'Công ty trả lương qua tài khoản ngân hàng nào?',	'Vietcombank',	'Sacombank',	'Techcombank',	'A'),
(8,	'Phụ cấp đi lại của công ty là bao nhiêu?',	' 500,000',	' 431,000',	' 360,000',	'B'),
(9,	'Đợt tăng lương của công ty vào tháng mấy?',	'Tháng 1 và Tháng 7',	'Tháng 3 và Tháng 9',	'Tháng 4 và Tháng 10',	'C'),
(10,	'Leader công ty đeo thẻ tên loại nào?',	'Thẻ kim loại',	'Thẻ giấy',	'Không cần đeo thẻ',	'A'),
(11,	'Nón của giám đốc công ty như thế nào?',	'Mũ xanh, 2 vạch vàng',	'Mũ vàng, 2 vạch xanh',	'Mũ xanh, 3 vạch vàng',	'A'),
(12,	'Công nhân viên được phép mang mấy phần ăn sáng vào công ty?',	'1',	'2',	'3',	'B'),
(13,	'Dữ liệu chấm công được đổ vào thứ mấy hàng tuần?',	'Thứ 2',	'Thứ 5',	'Thứ 7',	'A'),
(14,	'Nhà ăn có mấy tivi?',	'1',	'2',	'3',	'B'),
(15,	'Công ty phát sữa vào thứ mấy?',	'Thứ  2 & Thứ 4',	'Thứ 3 & Thứ 6',	'Thứ 4 & Thứ 7',	'A'),
(16,	'Phương thức chấm công tại công ty là gì?',	'Bấm vân tay',	'Chấm công gương mặt',	'Phát thẻ giấy',	'B'),
(17,	'Có mấy khu vực chấm công tại công ty?',	'2',	'3',	'4',	'A'),
(18,	'Công ty có mấy nhà để xe máy?',	'1',	'2',	'3',	'B'),
(19,	'Bảng nội quy công ty được bố trí ở đâu?',	'Khu vực nhà ăn',	'Khu vực chấm công',	'Khu vực kệ giày',	'C'),
(20,	'Công nhân viên công ty truy cập vào đâu để kiểm tra công phép?',	'Lemon Web',	'TimeSOFT',	'Wise Eyes',	'A');

-- 2024-08-28 08:56:23

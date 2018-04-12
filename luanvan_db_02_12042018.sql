/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50719
 Source Host           : localhost:3306
 Source Schema         : luanvan_db_02

 Target Server Type    : MySQL
 Target Server Version : 50719
 File Encoding         : 65001

 Date: 12/04/2018 20:28:19
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for classes
-- ----------------------------
DROP TABLE IF EXISTS `classes`;
CREATE TABLE `classes`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `faculty_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `staff_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `classes_faculty_id_foreign`(`faculty_id`) USING BTREE,
  INDEX `classes_staff_id_foreign`(`staff_id`) USING BTREE,
  CONSTRAINT `classes_faculty_id_foreign` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `classes_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of classes
-- ----------------------------
INSERT INTO `classes` VALUES (1, 'D14-TH01', 1, 1, NULL, NULL);
INSERT INTO `classes` VALUES (2, 'D14-TH02', 1, 1, NULL, NULL);
INSERT INTO `classes` VALUES (3, 'D14-TH03', 1, 2, NULL, NULL);
INSERT INTO `classes` VALUES (4, 'D14-QT01', 2, 4, NULL, NULL);
INSERT INTO `classes` VALUES (5, 'D14-QT02', 2, 4, NULL, NULL);

-- ----------------------------
-- Table structure for comments
-- ----------------------------
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `comments_created_by_foreign`(`created_by`) USING BTREE,
  CONSTRAINT `comments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for evaluation_criterias
-- ----------------------------
DROP TABLE IF EXISTS `evaluation_criterias`;
CREATE TABLE `evaluation_criterias`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `mark_range_display` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `detail` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `mark_range_from` int(11) NOT NULL,
  `mark_range_to` int(11) NULL DEFAULT NULL,
  `topic_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `evaluation_criterias_topic_id_foreign`(`topic_id`) USING BTREE,
  CONSTRAINT `evaluation_criterias_topic_id_foreign` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 29 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of evaluation_criterias
-- ----------------------------
INSERT INTO `evaluation_criterias` VALUES (1, '- Đi học đầy đủ, đúng giờ, nghiêm túc, không bỏ tiết…', '0 - 5 điểm', NULL, 0, 5, 6, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (2, '- Không vi phạm quy chế về thi, kiểm tra', '5 điểm', NULL, 5, NULL, 6, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (3, '- Bị cấm thi kết thúc học phần', '-5 điểm', NULL, -5, NULL, 6, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (4, '- Vi phạm qui chế thi bị lập biên bản', '-2 điểm', NULL, -2, NULL, 6, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (5, NULL, '0 – 10 điểm', 'Yếu, kém:0 điểm ; Trung bình: 2 điểm ; Trung bình khá: 4 điểm ; Khá: 6 điểm ; Giỏi: 8 điểm ; Xuất sắc: 10 điểm', 0, 10, 7, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (6, '- Tham gia các buổi hội thảo học thuật/ Tham gia các hội thi học thuật do Đoàn – Hội, Khoa, Trường tổ chức', '1 điểm/lần', NULL, 0, 20, 8, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (7, '- Tham gia nghiên cứu khoa học (đạt yêu cầu, được giảng viên hướng dẫn xác nhận; không tính các bài tập, tiểu luận, đồ án môn học, luận văn)/ Có bằng khen, giấy khen về nghiên cứu khoa học/ Có bài nghiên cứu khoa học được đăng trên tạp chí, nội san (Nộp minh chứng)', '2 – 6 điểm/lần', 'Cấp Khoa: 2 điểm/lần ; Cấp Trường: 4 điểm/lần ; Trung bình khá: 6 điểm/lần', 0, 20, 8, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (8, '- Có hành vi gây ảnh hưởng xấu đến công tác tổ chức các hoạt động', '-3 điểm/lần', NULL, -3, NULL, 8, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (9, '- Tham gia học tập Tuần sinh hoạt công dân hàng năm:', '0 – 5 điểm', 'Không tham gia: 0 điểm ; Không đầy đủ: 2 điểm ; Đầy đủ: 5 điểm ', 0, 5, 2, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (10, '- Không vi phạm các nội quy, quy chế, quy định trong Nhà Trường', '12 điểm', NULL, 0, 12, 2, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (11, '- Tuyên truyền và tham gia các hoạt động nâng cao ý thức của sinh viên trong việc chấp hành nội quy, quy chế, quy định trong Nhà Trường, các hoạt động giữ gìn môi trường, bảo vệ tài sản, thực hành tiết kiệm, bảo vệ an ninh trật tự…', '0 - 8 điểm', NULL, 0, 8, 2, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (12, '+ Mức khiển trách', '-5 điểm', NULL, -5, NULL, 9, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (13, '+ Mức cảnh cáo', '-10 điểm', NULL, -10, NULL, 9, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (14, '- Tham gia các hoạt động chính trị - xã hội, văn hóa, văn nghệ, thể thao, phòng chống tệ nạn xã hội do Lớp, Khoa, Trường tổ chức', '0 -10 điểm', NULL, 0, 10, 3, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (15, '+ Cấp Khoa (Đội trưởng xác nhận)', '2 điểm', NULL, 2, NULL, 10, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (16, '+ Cấp Trường (Phòng CTSV xác nhận)', '5 điểm', NULL, 5, NULL, 10, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (17, '- Tham gia chiến dịch Mùa hè xanh, Xuân tình nguyện, tiếp sức mùa thi… do Trường, Đoàn – Hội tổ chức', '5 điểm', NULL, 5, NULL, 3, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (18, '-	Được kết nạp Đảng Cộng sản Việt Nam', '15 điểm', NULL, 15, NULL, 3, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (19, '-	Tham gia các hoạt động rèn luyện về chính trị, xã hội, văn nghệ, thể thao và đạt giải thưởng:', '3 –7 điểm/lần', 'Cấp Trường: 3 điểm/lần ; Cấp Thành: 5 điểm/lần ; Cấp Bộ: 7 điểm/lần', 0, 20, 3, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (20, '-	Không bị cơ quan an ninh hoặc cơ quan Nhà nước có thẩm quyền gửi thông báo vi phạm công tác giữ gìn an ninh trật tự, an toàn xã hội, an toàn giao thông tại địa phương về Trường', '10 điểm', NULL, 10, NULL, 4, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (21, '- Có tinh thần giúp đỡ bạn bè gặp khó khăn trong học tập, trong cuộc sống (được tập thể lớp và GVCN xác nhận)', '0 – 5 điểm', NULL, 0, 5, 4, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (22, '-	Không chia rẽ bè phái, gây bất hòa, xích mích trong nội bộ, làm ảnh hưởng đến tinh thần đoàn kết của tập thể', '5 điểm', NULL, 5, NULL, 4, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (23, '-	Được biểu dương, khen thưởng (từ cấp trường trở lên) về tham gia giữ gìn trật tự an toàn xã hội, về thành tích đấu tranh bảo vệ pháp luật, về hành vi giúp người, cứu người ((Nộp minh chứng)', '10 điểm', NULL, 10, NULL, 4, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (24, '-	Vi phạm pháp luật, hành chánh ở địa phương cư trú, Ký túc xá, có công văn gửi về Trường', '-10 điểm', NULL, -10, NULL, 4, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (25, '- Có các hành vi không đúng mực trong lớp, trong Trường, gây chia rẽ bè phái làm mất đoàn kết trong tập thể; bản thân gây ảnh hưởng không tốt đối với tập thể', '-10 điểm', NULL, -10, NULL, 4, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (26, '-	Ban Cán sự lớp; Ban Chấp hành Đoàn – Hội các cấp; Ban Chủ nhiệm các câu lạc bộ, đội, nhóm các cấp hoàn thành nhiệm vụ (theo đề nghị của GVCN/ Cố vấn học tập, các đoàn thể):', '0 – 10 điểm', 'Không hoàn thành: 0 điểm ; Trung bình: 4 điểm ; Khá: 6 điểm ; Tốt: 8 điểm ; Xuất sắc: 10 điểm ', 0, 10, 5, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (27, '- Đạt được thành tích đặc biệt trong học tập, rèn luyện (được khen thưởng cấp: Trường, Thành phố, Quốc gia, Quốc tế) (Nộp minh chứng)', '6 – 10 điểm', NULL, 6, 10, 5, NULL, NULL);
INSERT INTO `evaluation_criterias` VALUES (28, '-	Tích cực tham gia công tác cán bộ lớp, công tác Đoàn TN, Hội SV', '0 – 5 điểm', NULL, 0, 5, 5, NULL, NULL);

-- ----------------------------
-- Table structure for evaluation_forms
-- ----------------------------
DROP TABLE IF EXISTS `evaluation_forms`;
CREATE TABLE `evaluation_forms`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `total` int(11) NOT NULL,
  `semester_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `evaluation_forms_semester_id_foreign`(`semester_id`) USING BTREE,
  INDEX `evaluation_forms_student_id_foreign`(`student_id`) USING BTREE,
  CONSTRAINT `evaluation_forms_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `evaluation_forms_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for evaluation_results
-- ----------------------------
DROP TABLE IF EXISTS `evaluation_results`;
CREATE TABLE `evaluation_results`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `score` int(10) UNSIGNED NOT NULL,
  `evaluation_criteria_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `evaluation_form_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `marker_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `marker_score` int(10) UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `evaluation_results_evaluation_criteria_id_foreign`(`evaluation_criteria_id`) USING BTREE,
  INDEX `evaluation_results_evaluation_form_id_foreign`(`evaluation_form_id`) USING BTREE,
  INDEX `evaluation_results_marker_id_foreign`(`marker_id`) USING BTREE,
  CONSTRAINT `evaluation_results_evaluation_criteria_id_foreign` FOREIGN KEY (`evaluation_criteria_id`) REFERENCES `evaluation_criterias` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `evaluation_results_evaluation_form_id_foreign` FOREIGN KEY (`evaluation_form_id`) REFERENCES `evaluation_forms` (`id`) ON DELETE NO ACTION ON UPDATE RESTRICT,
  CONSTRAINT `evaluation_results_marker_id_foreign` FOREIGN KEY (`marker_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for faculties
-- ----------------------------
DROP TABLE IF EXISTS `faculties`;
CREATE TABLE `faculties`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of faculties
-- ----------------------------
INSERT INTO `faculties` VALUES (1, 'Công nghệ thông tin', NULL, '2018-04-11 17:14:14');
INSERT INTO `faculties` VALUES (2, 'Quản trị kinh doanh', NULL, NULL);
INSERT INTO `faculties` VALUES (4, 'Điện - Điện tử', NULL, NULL);
INSERT INTO `faculties` VALUES (18, 'ASDASDASD', '2018-04-12 12:03:09', '2018-04-12 12:03:09');
INSERT INTO `faculties` VALUES (19, 'Design', '2018-04-12 12:03:41', '2018-04-12 12:03:41');
INSERT INTO `faculties` VALUES (20, 'Kĩ thuật công trình', '2018-04-12 12:34:58', '2018-04-12 12:34:58');
INSERT INTO `faculties` VALUES (21, 'D14-TH01', '2018-04-12 13:14:47', '2018-04-12 13:14:47');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO `migrations` VALUES (2, '2018_03_31_164400_create_students_table', 1);
INSERT INTO `migrations` VALUES (3, '2018_03_31_164413_create_comments_table', 1);
INSERT INTO `migrations` VALUES (4, '2018_03_31_164439_create_semesters_table', 1);
INSERT INTO `migrations` VALUES (5, '2018_03_31_164456_create_proofs_table', 1);
INSERT INTO `migrations` VALUES (6, '2018_03_31_164513_create_topics_table', 1);
INSERT INTO `migrations` VALUES (7, '2018_03_31_164527_create_faculties_table', 1);
INSERT INTO `migrations` VALUES (8, '2018_03_31_164539_create_classes_table', 1);
INSERT INTO `migrations` VALUES (9, '2018_03_31_164609_create_staff_table', 1);
INSERT INTO `migrations` VALUES (10, '2018_03_31_164634_create_notifications_table', 1);
INSERT INTO `migrations` VALUES (11, '2018_03_31_164652_create_notification_students_table', 1);
INSERT INTO `migrations` VALUES (12, '2018_03_31_164701_create_roles_table', 1);
INSERT INTO `migrations` VALUES (13, '2018_03_31_172245_create_evaluation_criterias_table', 1);
INSERT INTO `migrations` VALUES (14, '2018_03_31_172259_create_evaluation_results_table', 1);
INSERT INTO `migrations` VALUES (15, '2018_03_31_172313_create_evaluation_forms_table', 1);
INSERT INTO `migrations` VALUES (16, '2018_03_31_182110_create_foreign_key', 1);
INSERT INTO `migrations` VALUES (17, '2018_04_07_091145_create_users_table', 1);
INSERT INTO `migrations` VALUES (18, '2018_04_08_032934_add_foreign_key_to_users_table', 1);

-- ----------------------------
-- Table structure for notification_students
-- ----------------------------
DROP TABLE IF EXISTS `notification_students`;
CREATE TABLE `notification_students`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` int(10) UNSIGNED NOT NULL,
  `notification_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `notification_students_student_id_foreign`(`student_id`) USING BTREE,
  INDEX `notification_students_notification_id_foreign`(`notification_id`) USING BTREE,
  CONSTRAINT `notification_students_notification_id_foreign` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `notification_students_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for notifications
-- ----------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `notifications_created_by_foreign`(`created_by`) USING BTREE,
  CONSTRAINT `notifications_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `staff` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`  (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  INDEX `password_resets_email_index`(`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for proofs
-- ----------------------------
DROP TABLE IF EXISTS `proofs`;
CREATE TABLE `proofs`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `semester_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `proofs_created_by_foreign`(`created_by`) USING BTREE,
  INDEX `proofs_semester_id_foreign`(`semester_id`) USING BTREE,
  CONSTRAINT `proofs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `proofs_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 'Sinh viên', NULL, NULL);
INSERT INTO `roles` VALUES (2, 'Ban các sự lớp', NULL, NULL);
INSERT INTO `roles` VALUES (3, 'Cố vấn học tập', NULL, NULL);
INSERT INTO `roles` VALUES (4, 'Ban chủ nhiệm khoa', NULL, NULL);
INSERT INTO `roles` VALUES (5, 'Phòng CTSV', NULL, NULL);
INSERT INTO `roles` VALUES (6, 'ADMIN', NULL, NULL);

-- ----------------------------
-- Table structure for semesters
-- ----------------------------
DROP TABLE IF EXISTS `semesters`;
CREATE TABLE `semesters`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `year` int(11) NOT NULL,
  `term` int(11) NOT NULL,
  `date_start_to_mark` date NULL DEFAULT NULL,
  `date_end_to_mark` date NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of semesters
-- ----------------------------
INSERT INTO `semesters` VALUES (1, 2017, 1, NULL, NULL, NULL, NULL);
INSERT INTO `semesters` VALUES (2, 2017, 2, NULL, NULL, NULL, NULL);
INSERT INTO `semesters` VALUES (3, 2018, 1, NULL, NULL, NULL, NULL);
INSERT INTO `semesters` VALUES (4, 2018, 2, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for staff
-- ----------------------------
DROP TABLE IF EXISTS `staff`;
CREATE TABLE `staff`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `staff_user_id_unique`(`user_id`) USING BTREE,
  CONSTRAINT `staff_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of staff
-- ----------------------------
INSERT INTO `staff` VALUES (1, 'GV123123', NULL, NULL);
INSERT INTO `staff` VALUES (2, 'GV124124', NULL, NULL);
INSERT INTO `staff` VALUES (3, 'CT123123', NULL, NULL);
INSERT INTO `staff` VALUES (4, 'GV124125', NULL, NULL);
INSERT INTO `staff` VALUES (5, 'AD123122', NULL, NULL);

-- ----------------------------
-- Table structure for students
-- ----------------------------
DROP TABLE IF EXISTS `students`;
CREATE TABLE `students`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `students_user_id_unique`(`user_id`) USING BTREE,
  INDEX `students_class_id_foreign`(`class_id`) USING BTREE,
  CONSTRAINT `students_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of students
-- ----------------------------
INSERT INTO `students` VALUES (1, 'DH51400250', 1, NULL, NULL);
INSERT INTO `students` VALUES (2, 'DH51400251', 1, NULL, NULL);
INSERT INTO `students` VALUES (3, 'CD51402222', 4, NULL, NULL);

-- ----------------------------
-- Table structure for topics
-- ----------------------------
DROP TABLE IF EXISTS `topics`;
CREATE TABLE `topics`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_score` int(11) NULL DEFAULT NULL,
  `parent_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `topics_parent_id_foreign`(`parent_id`) USING BTREE,
  CONSTRAINT `topics_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of topics
-- ----------------------------
INSERT INTO `topics` VALUES (1, 'I. Ý thức tham gia học tập (0 – 20 điểm)', 20, NULL, NULL, NULL);
INSERT INTO `topics` VALUES (2, 'II. Ý thức chấp hành nội quy, quy chế, quy định trong nhà trường (0 – 25 điểm) ', 25, NULL, NULL, NULL);
INSERT INTO `topics` VALUES (3, 'III. Ý thức tham gia các hoạt động chính trị - xã hội, văn hóa, văn nghệ, thể thao, phòng chống các tệ nạn xã hội (0 – 20 điểm) ', 20, NULL, NULL, NULL);
INSERT INTO `topics` VALUES (4, 'IV. Ý thức công dân trong quan hệ cộng đồng (0 – 25 điểm) ', 25, NULL, NULL, NULL);
INSERT INTO `topics` VALUES (5, 'V. Ý thức và kết quả tham gia công tác cán bộ lớp, các đoàn thể, tổ chức khác trong nhà trường hoặc sinh viên đạt được thành tích đặc biệt trong học tập, rèn luyện (0 – 10 điểm)', 25, NULL, NULL, NULL);
INSERT INTO `topics` VALUES (6, 'a.	Ý thức học tập', NULL, 1, NULL, NULL);
INSERT INTO `topics` VALUES (7, 'b.	Kết quả học tập ', NULL, 1, NULL, NULL);
INSERT INTO `topics` VALUES (8, 'c.	Nghiên cứu khoa học, tham gia các hoạt động học thuật', NULL, 1, NULL, NULL);
INSERT INTO `topics` VALUES (9, '-	Bị xử lý kỷ luật về công tác sinh viên:', NULL, 2, NULL, NULL);
INSERT INTO `topics` VALUES (10, '-  Thành viên tích cực các đội hình văn nghệ, thể thao, công tác xã hội…:', NULL, 3, NULL, NULL);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` tinyint(4) NULL DEFAULT NULL,
  `address` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `phone_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `birthday` date NULL DEFAULT NULL,
  `avatar` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `role_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  UNIQUE INDEX `users_id_unique`(`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email`) USING BTREE,
  INDEX `users_role_id_foreign`(`role_id`) USING BTREE,
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('AD123122', 'V . I . P', 'huynhjduc248@gmail1.com', '$2y$10$oHoXm2x3/V.mNp87.zfu4eVPBs6Qtmlx7MX/csN5A1TfulpDUhgnG', NULL, NULL, NULL, NULL, NULL, 6, NULL, NULL, NULL);
INSERT INTO `users` VALUES ('CD51402222', 'Cao dang', 'thducit3@gmail.com', '$2y$10$I/MeYWrPqnHhpz/p7IDXbuXHqjH5AwMq7Btk8fFBcCe59AYpMoAC2', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL);
INSERT INTO `users` VALUES ('CT123123', 'Giao vien han', 'GV1241325@gmail1.com', '$2y$10$7tJNyGgs.9QvkwOdw77GBuhAJQGfa7eOo3VLtBme8s6xv.eGQVmeq', NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL);
INSERT INTO `users` VALUES ('DH51400250', 'Thai Duc', 'thducit@gmail.com', '$2y$10$hAhfhZZ6AxZbR0VrPbwcLeRtIJDjZinielPLSsdwScly2E1dtZt2q', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL);
INSERT INTO `users` VALUES ('DH51400251', 'Thai Duc 1', 'thducit1@gmail.com', '$2y$10$JbBciH2Qw2VVn.hrdyYhPOBkyb94Zzef1MtdGkGqUxaMcWLeC2w5u', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL);
INSERT INTO `users` VALUES ('GV123123', 'Cố vấn học tập name', 'GV123123@gmail.com', '$2y$10$eb5Xnl5ZRNYs7Cnn0nThbOC.6aXTV6WkDprYjx60FU9SlwASh6ONe', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL);
INSERT INTO `users` VALUES ('GV124124', 'Ban chủ nhiệm khoa', 'GV122223@gmail1.com', '$2y$10$Iy2ofF2VQWScBfZhlAV8Mu7CJXj1b01DuY5DUapTXGjyyMO9cKR5.', NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL);
INSERT INTO `users` VALUES ('GV124125', 'Cố vấn học tập', 'GV1241225@gmail1.com', '$2y$10$FCj7oRe3TSFLww7JITp3qubh4.QICapVZSd/5tgZlmz6v2vIexYh2', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL);

-- ----------------------------
-- Triggers structure for table users
-- ----------------------------
DROP TRIGGER IF EXISTS `after_insert_users`;
delimiter ;;
CREATE TRIGGER `after_insert_users` AFTER INSERT ON `users` FOR EACH ROW BEGIN

		IF (SUBSTRING(NEW.id,1,2) = 'DH' OR SUBSTRING(NEW.id,1,2) = 'CD')
		THEN 
				INSERT INTO students(user_id) VALUES(NEW.id);
		ELSE
				INSERT INTO staff(user_id) VALUES(NEW.id);
		END IF;
END
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;

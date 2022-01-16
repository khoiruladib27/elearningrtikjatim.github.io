/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100604
 Source Host           : localhost:3306
 Source Schema         : lpkmuda

 Target Server Type    : MySQL
 Target Server Version : 100604
 File Encoding         : 65001

 Date: 17/10/2021 16:09:46
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for app_kejuruan
-- ----------------------------
DROP TABLE IF EXISTS `app_kejuruan`;
CREATE TABLE `app_kejuruan`  (
  `kjr_id` char(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `kjr_nama` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kjr_harga` decimal(65, 0) NULL DEFAULT NULL,
  `kjr_deskripsi` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `kjr_slug` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kjr_hit` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kjr_image` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kjr_created_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  `kjr_created_by` char(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kjr_deleted_at` timestamp(0) NULL DEFAULT NULL,
  `kjr_deleted_by` char(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`kjr_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of app_kejuruan
-- ----------------------------
INSERT INTO `app_kejuruan` VALUES ('Rfyil-wgvVjywsva', 'Digital Marketing', 150000, 'deskripsi digital marketing', 'digital-marketing', NULL, NULL, '2021-10-12 22:58:29', '3WzM9-6bGh4bLCyu', '2021-10-12 22:56:06', NULL);
INSERT INTO `app_kejuruan` VALUES ('Rfyil-wgvVjywsvb', 'Teknisi Komputer', 200000, 'deskripsi teknisi komputer', 'teknisi-komputer', NULL, NULL, '2021-10-12 22:58:32', '3WzM9-6bGh4bLCyu', NULL, NULL);
INSERT INTO `app_kejuruan` VALUES ('Rfyil-wgvVjywsvc', 'Tata Boga', 250000, 'deskripsi tata boga', 'tata-boga', '2', NULL, '2021-10-12 22:58:36', '3WzM9-6bGh4bLCyu', NULL, NULL);
INSERT INTO `app_kejuruan` VALUES ('Rfyil-wgvVjywsvd', 'Menjahit', 300000, 'deskripsi menjahit', 'menjahit', '3', NULL, '2021-10-12 22:58:43', '3WzM9-6bGh4bLCyu', NULL, NULL);
INSERT INTO `app_kejuruan` VALUES ('Rfyil-wgvVjywsve', 'Office Perkantoran 2', 100000, 'deskripsi office', 'office-perkantoran2', '7', NULL, '2021-10-12 23:13:22', '3WzM9-6bGh4bLCyu', NULL, NULL);
INSERT INTO `app_kejuruan` VALUES ('Rfyil-wgvVjywsvf', 'Digital Marketing 2', 150000, 'deskripsi digital marketing', 'digital-marketing2', '2', NULL, '2021-10-12 23:13:25', '3WzM9-6bGh4bLCyu', NULL, NULL);
INSERT INTO `app_kejuruan` VALUES ('Rfyil-wgvVjywsvg', 'Teknisi Komputer 2', 200000, 'deskripsi teknisi komputer', 'teknisi-komputer2', '5', NULL, '2021-10-12 23:13:28', '3WzM9-6bGh4bLCyu', NULL, NULL);
INSERT INTO `app_kejuruan` VALUES ('Rfyil-wgvVjywsvh', 'Tata Boga 2', 250000, 'deskripsi tata boga', 'tata-boga2', '26', NULL, '2021-10-12 23:13:33', '3WzM9-6bGh4bLCyu', NULL, NULL);
INSERT INTO `app_kejuruan` VALUES ('Rfyil-wgvVjywsvi', 'Office Perkantoran', 100000, 'deskripsi office', 'office-perkantoran', NULL, NULL, '2021-10-12 22:56:06', '3WzM9-6bGh4bLCyu', '2021-10-12 22:56:06', NULL);
INSERT INTO `app_kejuruan` VALUES ('Rfyil-wgvVjywsvj', 'Menjahit 2', 300000, 'deskripsi menjahit', 'menjahit2', '94', NULL, '2021-10-12 23:13:42', '3WzM9-6bGh4bLCyu', NULL, NULL);

-- ----------------------------
-- Table structure for app_mitra
-- ----------------------------
DROP TABLE IF EXISTS `app_mitra`;
CREATE TABLE `app_mitra`  (
  `mtr_id` char(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `mtr_nama` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `mtr_image` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `mtr_locked` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `mtr_created_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  `mtr_deleted_at` timestamp(0) NULL DEFAULT NULL,
  `mtr_created_by` char(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `mtr_order` decimal(5, 0) NULL DEFAULT NULL,
  `mtr_link` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`mtr_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of app_mitra
-- ----------------------------
INSERT INTO `app_mitra` VALUES ('CrfG-0dRuYjQ4tS', 'Taman Kanak-Kanak Mardi Rahayu Mayangrejo', 'taman-kanak-kanak-mardi-rahayu-mayangrejo-2021_10_17_144052.jpg', '0', '0000-00-00 00:00:00', NULL, NULL, 3, '');
INSERT INTO `app_mitra` VALUES ('CrfG-betvSC3U6m', 'Dikamhost.com', 'dikamhostcom-2021_10_17_143943.png', '0', '0000-00-00 00:00:00', NULL, NULL, 1, 'https://dikamhost.com');
INSERT INTO `app_mitra` VALUES ('CrfG-T5GctrqHSz', 'Kemenaker', 'kemenaker-2021_10_17_144012.jpg', '0', '0000-00-00 00:00:00', NULL, NULL, 2, 'https://kemnaker.go.id/');

-- ----------------------------
-- Table structure for app_slider
-- ----------------------------
DROP TABLE IF EXISTS `app_slider`;
CREATE TABLE `app_slider`  (
  `sld_id` char(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `sld_nama` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sld_image` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sld_locked` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sld_created_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  `sld_deleted_at` timestamp(0) NULL DEFAULT NULL,
  `sld_created_by` char(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sld_order` decimal(5, 0) NULL DEFAULT NULL,
  `sld_link` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`sld_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of app_slider
-- ----------------------------
INSERT INTO `app_slider` VALUES ('hoTVn-4woqvyszlG', 'Sertifikat', 'slider-3-2021_10_17_131126.jpg', '0', '0000-00-00 00:00:00', NULL, NULL, 1, '');
INSERT INTO `app_slider` VALUES ('hoTVn-AerQBHa7dJ', 'Dewi Putri Nur Kotafiya, S.Stat', 'dewi-putri-nur-kotafiya-sstat-2021_10_17_141909.png', '0', '0000-00-00 00:00:00', NULL, NULL, 6, '');
INSERT INTO `app_slider` VALUES ('hoTVn-kHZW1wOlox', 'Didik Abdul Mukmin, S.Kom', 'slider-3-2021_10_17_135520.png', '0', '0000-00-00 00:00:00', NULL, NULL, 3, '');
INSERT INTO `app_slider` VALUES ('hoTVn-O1RnzdCWQ9', 'Ali Muchtarom, S.Kom', 'ali-muchtarom-skom-2021_10_17_141853.png', '0', '0000-00-00 00:00:00', NULL, NULL, 5, '');
INSERT INTO `app_slider` VALUES ('hoTVn-p4giJrvKfO', 'Erlangga Rizki Mura, S.Kom', 'slider-1-2021_10_17_130115.png', '0', '0000-00-00 00:00:00', NULL, NULL, 2, '');
INSERT INTO `app_slider` VALUES ('hoTVn-SFCMqB2v7U', 'Ayu Mauliana Intahaya, S.Kom', 'ayu-mauliana-intahaya-skom-2021_10_17_141816.png', '0', '0000-00-00 00:00:00', NULL, NULL, 4, '');

-- ----------------------------
-- Table structure for app_tentang
-- ----------------------------
DROP TABLE IF EXISTS `app_tentang`;
CREATE TABLE `app_tentang`  (
  `tnt_id` char(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tnt_image` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tnt_isi` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  PRIMARY KEY (`tnt_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of app_tentang
-- ----------------------------
INSERT INTO `app_tentang` VALUES ('DVite-yFfCpfnPIc', 'tentang-2021_10_17_155416.jpg', '<p class=\"mb-3\">LPK Muda Al-Hidayah berada dalam naungan Yayasan Generasi Muda Islami Kab.Bojonegoro. kami berkomitmen untuk:</p>\r\n<ul>\r\n<li>Membantu peserta didik untuk terus berkembang sesuai passionnya</li>\r\n<li>Memberi manfaat dan mengubah pemikiran masyarakat tentang pentingnya pendidikan</li>\r\n<li>Memberikan pelatihan gratis kepada anak yatim piatu dan anak yang kurang mampu</li>\r\n</ul>\r\n<p class=\"mt-3\">LPK Muda Al-Hidayah selalu berinovasi untuk menyediakan pelatihan yang berbobot dan disesuaikan dengan kebutuhan dunia kerja.Setelah penerimaan sertifikat ada sesi dimana peserta didik berkonsultasi dengan para mentor untuk mencari perusahaan atau instansi yang sesuai untuk bekerja setelah selesai pelatihan.</p>');

-- ----------------------------
-- Table structure for artikel
-- ----------------------------
DROP TABLE IF EXISTS `artikel`;
CREATE TABLE `artikel`  (
  `art_id` char(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `art_gambar` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `art_isi` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `art_locked` int(2) NOT NULL DEFAULT 1,
  `art_tgl_upload` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `art_ktg_id` char(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `art_usr_id` char(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `art_judul` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `art_headline` int(1) NOT NULL DEFAULT 0,
  `art_slug` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `art_hit` int(11) NULL DEFAULT 0,
  `art_deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`art_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of artikel
-- ----------------------------
INSERT INTO `artikel` VALUES ('Rfyil-1zeIAd9p5o', 'artikel-4-2021_09_25_092745.jpg', '<p><strong>isi artikel 4</strong></p>\r\n<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p><img src=\"http://dikamhost.test/storage/artikel/artikel-33333-2021_09_25_090953.jpg\" alt=\"\" width=\"427\" height=\"240\" /></p>', 0, '2021-09-25 16:27:45', 'zRciw-cq1iY6b7yD', '3WzM9-6bGh4bLCyu', 'Artikel 4', 0, 'artikel-4', 41, NULL);
INSERT INTO `artikel` VALUES ('Rfyil-D0nS9vc8f4', 'test-didikam-2021_09_27_230903.jpg', '<p><strong><img src=\"http://dikamhost.test/storage/artikel/artikel-3-2021_09_25_092712.jpg\" alt=\"\" width=\"408\" height=\"306\" /></strong></p>\r\n<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p><img src=\"http://dikamhost.test/storage/artikel/artikel-4-2021_09_25_092745.jpg\" alt=\"\" width=\"408\" height=\"306\" /></p>', 1, '2021-09-28 06:09:03', 'zRciw-HCbOD2wTv9', '3WzM9-AmaI8KG7oH', 'Test 7', 0, 'test-7', 22, NULL);
INSERT INTO `artikel` VALUES ('Rfyil-Dbl85QxgJa', 'artikel-33333-2021_09_25_090953.jpg', '<p>isi artikel 6</p>\r\n<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', 0, '2021-09-25 16:06:22', 'zRciw-yNYhBJrZmC', '3WzM9-6bGh4bLCyu', 'Artikel 6', 0, 'artikel-6', 2, NULL);
INSERT INTO `artikel` VALUES ('Rfyil-FGAQCcy9Dq', 'artikel-5-2021_09_27_155934.jpg', '<p><img src=\"http://dikamhost.test/storage/artikel/artikel-4-2021_09_25_092745.jpg\" alt=\"\" width=\"408\" height=\"306\" /></p>\r\n<p>isi artikel 5</p>\r\n<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p><img src=\"http://dikamhost.test/storage/artikel/artikel-33333-2021_09_25_090953.jpg\" alt=\"\" width=\"427\" height=\"240\" /></p>\r\n<p>&nbsp;</p>\r\n<p><iframe title=\"YouTube video player\" src=\"https://www.youtube.com/embed/I2e7RZDRnZI\" width=\"560\" height=\"315\" frameborder=\"0\" allowfullscreen=\"allowfullscreen\"></iframe></p>', 0, '2021-09-27 22:59:34', 'zRciw-yHmuWfSVKN', '3WzM9-6bGh4bLCyu', 'Artikel 5', 0, 'artikel-5', 13, NULL);
INSERT INTO `artikel` VALUES ('Rfyil-oxfz7XFbd9', NULL, '<p>isi artikel 1</p>\r\n<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', 1, '2021-09-25 16:01:09', 'zRciw-cq1iY6b7yD', '3WzM9-6bGh4bLCyu', 'Artikel 1', 0, 'artikel-1', 2, NULL);
INSERT INTO `artikel` VALUES ('Rfyil-qlKFQROe1A', 'artikel-3-2021_09_25_092712.jpg', '<p>isi artikel 3</p>\r\n<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', 0, '2021-09-25 16:27:12', 'zRciw-yNYhBJrZmC', '3WzM9-6bGh4bLCyu', 'Artikel 3', 0, 'artikel-3', 3, NULL);
INSERT INTO `artikel` VALUES ('Rfyil-UT0v7zfFLO', NULL, '<p>isi artikel 2</p>\r\n<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', 0, '2021-09-25 16:02:09', 'zRciw-yNYhBJrZmC', '3WzM9-6bGh4bLCyu', 'Artikel 2', 0, 'artikel-2', 35, NULL);

-- ----------------------------
-- Table structure for artikel_tags
-- ----------------------------
DROP TABLE IF EXISTS `artikel_tags`;
CREATE TABLE `artikel_tags`  (
  `artag_id` char(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `artag_art_id` char(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `artag_tag_id` char(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`artag_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of artikel_tags
-- ----------------------------
INSERT INTO `artikel_tags` VALUES ('a', 'Rfyil-UT0v7zfFLO', 'OjnsH-BvJmhzUMXq');
INSERT INTO `artikel_tags` VALUES ('b', 'Rfyil-UT0v7zfFLO', 'OjnsH-fSgFYnPYKH');
INSERT INTO `artikel_tags` VALUES ('c', 'Rfyil-UT0v7zfFLO', 'OjnsH-hbkPnTEJPv');
INSERT INTO `artikel_tags` VALUES ('d', 'Rfyil-qlKFQROe1A', 'OjnsH-hbkPnTEJPv');

-- ----------------------------
-- Table structure for kategori
-- ----------------------------
DROP TABLE IF EXISTS `kategori`;
CREATE TABLE `kategori`  (
  `ktg_id` char(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `ktg_nama` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `ktg_locked` tinyint(4) NOT NULL,
  `ktg_slug` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `ktg_deleted_at` timestamp(0) NULL DEFAULT NULL,
  `ktg_usr_id` char(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`ktg_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kategori
-- ----------------------------
INSERT INTO `kategori` VALUES ('zRciw-cq1iY6b7yD', 'Kategori 4', 0, 'kategori-4', NULL, '3WzM9-6bGh4bLCyu');
INSERT INTO `kategori` VALUES ('zRciw-HCbOD2wTv9', 'Kategori 2', 0, 'kategori-2', NULL, '3WzM9-6bGh4bLCyu');
INSERT INTO `kategori` VALUES ('zRciw-WjGR4cCxKe', 'Programming', 1, 'programming', NULL, '3WzM9-6bGh4bLCyu');
INSERT INTO `kategori` VALUES ('zRciw-yHmuWfSVKN', 'Kategori 5', 0, 'kategori-5', NULL, '3WzM9-6bGh4bLCyu');
INSERT INTO `kategori` VALUES ('zRciw-yNYhBJrZmC', 'Kategori 3', 0, 'kategori-3', NULL, '3WzM9-6bGh4bLCyu');
INSERT INTO `kategori` VALUES ('zRciw-yOzZF73IEo', 'Kategori 1', 1, 'kategori-1', NULL, '3WzM9-6bGh4bLCyu');

-- ----------------------------
-- Table structure for system_access
-- ----------------------------
DROP TABLE IF EXISTS `system_access`;
CREATE TABLE `system_access`  (
  `acs_group` char(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `acs_menu` char(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of system_access
-- ----------------------------
INSERT INTO `system_access` VALUES ('Fe1sF-RZ3S7JmIwU', 'DVite-rWim9D1FrG');
INSERT INTO `system_access` VALUES ('Fe1sF-RZ3S7JmIwU', 'DVite-rWim9D1FrT');
INSERT INTO `system_access` VALUES ('Fe1sF-RZ3S7JmIwU', 'DVite-rWim9D1FrJ');
INSERT INTO `system_access` VALUES ('Fe1sF-CrKLQ2EhuP', 'DVite-ebRvcSjvDX');
INSERT INTO `system_access` VALUES ('Fe1sF-CrKLQ2EhuP', 'DVite-VxWayaejFP');
INSERT INTO `system_access` VALUES ('Fe1sF-CrKLQ2EhuP', 'DVite-HsNcPiuNdf');
INSERT INTO `system_access` VALUES ('Fe1sF-2ciqv7x3Et', 'DVite-rWim9D1FrG');
INSERT INTO `system_access` VALUES ('Fe1sF-2ciqv7x3Et', 'DVite-rWim9D1FrK');
INSERT INTO `system_access` VALUES ('Fe1sF-2ciqv7x3Et', 'DVite-rWim9D1FrL');
INSERT INTO `system_access` VALUES ('Fe1sF-2ciqv7x3Et', 'DVite-rWim9D1FrM');
INSERT INTO `system_access` VALUES ('Fe1sF-2ciqv7x3Et', 'DVite-rWim9D1FrJ');
INSERT INTO `system_access` VALUES ('Fe1sF-2ciqv7x3Et', 'DVite-rWim9D1FrT');
INSERT INTO `system_access` VALUES ('Fe1sF-2ciqv7x3Et', 'DVite-ebRvcSjvDX');
INSERT INTO `system_access` VALUES ('Fe1sF-2ciqv7x3Et', 'DVite-VxWayaejFP');
INSERT INTO `system_access` VALUES ('Fe1sF-2ciqv7x3Et', 'DVite-HsNcPiuNdf');
INSERT INTO `system_access` VALUES ('Fe1sF-2ciqv7x3Et', 'DVite-m7m5QVSUPU');
INSERT INTO `system_access` VALUES ('Fe1sF-2ciqv7x3Et', 'DVite-kTiUBfWDly');
INSERT INTO `system_access` VALUES ('Fe1sF-2ciqv7x3Et', 'DVite-doCdIe7Lvo');
INSERT INTO `system_access` VALUES ('Fe1sF-2ciqv7x3Et', 'Dvite-H49lOqbyYs');

-- ----------------------------
-- Table structure for system_config
-- ----------------------------
DROP TABLE IF EXISTS `system_config`;
CREATE TABLE `system_config`  (
  `conf_char` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `conf_name` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `conf_type` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `conf_value` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `conf_option` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `conf_descryption` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `conf_order` int(11) NULL DEFAULT NULL,
  `conf_deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`conf_char`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of system_config
-- ----------------------------
INSERT INTO `system_config` VALUES ('app_brand', 'Logo Aplikasi', 'img', 'app_brand.png', NULL, 'Logo Aplikasi', 3, NULL);
INSERT INTO `system_config` VALUES ('app_descryption', 'Deskripsi Applikasi', 'textarea', 'Kursus Berkualitas Terpercaya', NULL, 'Deskripsi singkat tentang Aplikasi', 6, '2021-09-22 19:18:25');
INSERT INTO `system_config` VALUES ('app_icons', 'Icon Applikasi', 'img', 'app_icons.png', NULL, 'Gambar Icon Pada Tab Browser', 1, NULL);
INSERT INTO `system_config` VALUES ('app_keywords', 'Kata Kunci Applikasi', 'textarea', 'Simuda course, LPK Muda Alhidayah, Kursus Kilat, Kursus Bersertifikat, Kursus Kejuruan, Ingin Pintar, Tingkatkan Skill', NULL, 'Penulisan Keywords dipisahkan dengan tanda Koma (,)', 5, NULL);
INSERT INTO `system_config` VALUES ('app_names', 'Nama Applikasi', 'text', 'Simuda Course', NULL, 'Nama Aplikasi', 4, NULL);
INSERT INTO `system_config` VALUES ('app_sidebar_color', 'Sidebar Warna', 'select', 'success', '{\"danger\":\"Merah\",\"success\":\"Hijau\",\"primary\":\"Biru\",\"info\":\"Biru Muda\",\"warning\":\"Orange\",\"purple\":\"Ungu\",\"dark\":\"Hitam\",\"light\":\"Putih\"}', 'Warna pada halaman admin', 10, NULL);
INSERT INTO `system_config` VALUES ('app_sidebar_theme', 'Sidebar Tema', 'select', 'light', '{\"dark\":\"Gelap\",\"light\":\"Cerah\"}', 'Warna pada halaman admin', 9, NULL);
INSERT INTO `system_config` VALUES ('app_title', 'Judul Browser', 'text', 'Simuda Course', NULL, 'Judul Aplikasi pada Tab Browser', 2, NULL);
INSERT INTO `system_config` VALUES ('app_topbar_color', 'Navbar Warna', 'select', 'success', '{\"danger\":\"Merah\",\"success\":\"Hijau\",\"primary\":\"Biru\",\"info\":\"Biru Muda\",\"warning\":\"Orange\",\"purple\":\"Ungu\",\"dark\":\"Hitam\",\"light\":\"Putih\"}', 'Warna pada halaman admin', 8, NULL);
INSERT INTO `system_config` VALUES ('app_topbar_theme', 'Navbar Tema', 'select', 'dark', '{\"dark\":\"Gelap\",\"light\":\"Cerah\"}', 'Warna pada halaman admin', 7, NULL);

-- ----------------------------
-- Table structure for system_group
-- ----------------------------
DROP TABLE IF EXISTS `system_group`;
CREATE TABLE `system_group`  (
  `grp_id` char(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `grp_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `grp_create_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  `grp_create_by` char(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `grp_deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`grp_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of system_group
-- ----------------------------
INSERT INTO `system_group` VALUES ('Fe1sF-2ciqv7x3Et', 'Administrator', '2021-08-19 09:55:16', '3WzM9-6bGh4bLCyu', NULL);
INSERT INTO `system_group` VALUES ('Fe1sF-CrKLQ2EhuP', 'Pemateri', '2021-10-17 14:51:49', NULL, NULL);
INSERT INTO `system_group` VALUES ('Fe1sF-RZ3S7JmIwU', 'Operator', '2021-09-22 19:18:00', NULL, NULL);

-- ----------------------------
-- Table structure for system_menu
-- ----------------------------
DROP TABLE IF EXISTS `system_menu`;
CREATE TABLE `system_menu`  (
  `mnu_id` char(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `mnu_icon` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `mnu_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `mnu_link` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `mnu_index` char(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `mnu_order` int(3) NULL DEFAULT NULL,
  `mnu_create_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  `mnu_create_by` char(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `mnu_deleted_at` timestamp(0) NULL DEFAULT NULL,
  `mnu_deleted_by` char(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`mnu_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of system_menu
-- ----------------------------
INSERT INTO `system_menu` VALUES ('DVite-doCdIe7Lvo', 'fas fa-users', 'Pengguna', 'admin/user', 'DVite-m7m5QVSUPU', 2, '2021-08-19 12:51:31', NULL, NULL, NULL);
INSERT INTO `system_menu` VALUES ('DVite-ebRvcSjvDX', 'fas fa-graduation-cap', 'Kursus', NULL, NULL, 3, '2021-08-19 12:51:31', NULL, NULL, NULL);
INSERT INTO `system_menu` VALUES ('Dvite-H49lOqbyYs', 'fas fa-sliders-h', 'Aplikasi', 'admin/config', 'DVite-m7m5QVSUPU', 3, '2021-08-19 12:51:31', NULL, NULL, NULL);
INSERT INTO `system_menu` VALUES ('DVite-HsNcPiuNdf', 'far fa-circle', 'Kilat', 'admin/kilat', 'DVite-ebRvcSjvDX', 2, '2021-10-17 14:23:32', NULL, NULL, NULL);
INSERT INTO `system_menu` VALUES ('DVite-kTiUBfWDly', 'fas fa-users-cog', 'Role Group', 'admin/role', 'DVite-m7m5QVSUPU', 1, '2021-08-19 12:51:31', NULL, NULL, NULL);
INSERT INTO `system_menu` VALUES ('DVite-kTiUBfWDMn', 'fas fa-align-left', 'Menu', 'admin/menu', 'DVite-m7m5QVSUPU', 4, '2021-10-13 21:48:56', NULL, '2021-10-17 13:58:06', NULL);
INSERT INTO `system_menu` VALUES ('DVite-m7m5QVSUPU', 'fas fa-cogs', 'System', NULL, NULL, 99, '2021-08-19 12:51:31', NULL, NULL, NULL);
INSERT INTO `system_menu` VALUES ('DVite-rWim9D1FrG', 'fas fa-id-card-alt', 'Admin Web', NULL, NULL, 2, '2021-08-19 12:51:31', NULL, NULL, NULL);
INSERT INTO `system_menu` VALUES ('DVite-rWim9D1FrJ', 'far fa-circle', 'Kategori', 'admin/kategori', 'DVite-rWim9D1FrG', 11, '2021-09-18 13:46:22', NULL, NULL, NULL);
INSERT INTO `system_menu` VALUES ('DVite-rWim9D1FrK', 'fas fa-sliders-h', 'Slider', 'admin/slider', 'DVite-rWim9D1FrG', 1, '2021-10-17 12:29:45', NULL, NULL, NULL);
INSERT INTO `system_menu` VALUES ('DVite-rWim9D1FrL', 'fas fa-handshake', 'Mitra', 'admin/mitra', 'DVite-rWim9D1FrG', 2, '2021-10-17 12:29:45', NULL, NULL, NULL);
INSERT INTO `system_menu` VALUES ('DVite-rWim9D1FrM', 'fas fa-address-card', 'Tentang', 'admin/tentang', 'DVite-rWim9D1FrG', 3, '2021-10-17 12:29:45', NULL, NULL, NULL);
INSERT INTO `system_menu` VALUES ('DVite-rWim9D1FrT', 'far fa-circle', 'Artikel', 'admin/artikel', 'DVite-rWim9D1FrG', 12, '2021-09-18 13:46:08', NULL, NULL, NULL);
INSERT INTO `system_menu` VALUES ('DVite-VxWayaejFP', 'far fa-circle', 'Kejuruan', 'admin/kejuruan', 'DVite-ebRvcSjvDX', 1, '2021-10-17 14:23:32', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for system_users
-- ----------------------------
DROP TABLE IF EXISTS `system_users`;
CREATE TABLE `system_users`  (
  `usr_id` char(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `usr_group` char(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `usr_name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `usr_email` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `usr_username` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `usr_password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `usr_locked` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0 = Unlocked user;1 = Loked User;',
  `usr_foto` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `usr_reset` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `usr_last_login` timestamp(0) NULL DEFAULT NULL,
  `usr_create_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  `usr_create_by` char(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `usr_reset_at` timestamp(0) NULL DEFAULT NULL,
  `usr_update_at` timestamp(0) NULL DEFAULT NULL,
  `usr_update_by` char(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `usr_deleted_at` timestamp(0) NULL DEFAULT NULL,
  `usr_deskripsi` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `usr_facebook` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `usr_twitter` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `usr_instagram` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`usr_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of system_users
-- ----------------------------
INSERT INTO `system_users` VALUES ('3WzM9-6bGh4bLCyu', 'Fe1sF-2ciqv7x3Et', 'Administrator System', 'administrator@mail.com', 'administrator', '$2y$10$KlRH85gljPQCun/ayvi3je4sxcMdoI4LDK2j/4bKhVEI/vUA2C89e', 0, 'administrator-2021_10_13_211458.png', NULL, '2021-09-10 08:51:18', NULL, NULL, NULL, '2021-10-17 15:13:20', NULL, NULL, 'Administrator Lorem Ipsum is simply dummy text of the printing and typesetting industry. ', 'https://facebook.com', 'https://twitter.com/', 'https://instagram');
INSERT INTO `system_users` VALUES ('3WzM9-AmaI8KG7oH', 'Fe1sF-CrKLQ2EhuP', 'Didik Abdul Mukmin', 'didikam@gmail.com', 'didikam', '$2y$10$gap0CYRoPpJMGZIBueipQe29GtTDpGip278F5VzZDyNWqHrOlOday', 0, 'didikam-2021_10_17_145803.png', NULL, NULL, '2021-09-22 19:18:25', NULL, NULL, '2021-10-17 15:13:33', NULL, NULL, 'Didikam Lorem Ipsum is simply dummy text of the printing and typesetting industry. ', 'https://facebook.com/didikam', 'https://twitter.com/didikam', 'https://instagram.com/didikam');
INSERT INTO `system_users` VALUES ('3WzM9-BWznw8IEC5', 'Fe1sF-CrKLQ2EhuP', 'Erlangga Rizki Mura, S.Kom', 'erlangga@gmail.com', 'erlangga', '$2y$10$IWrTYN8lih4EQPzi6bR1Q.jq15rDTH9..SDaGX73EV3MJfSCprTgG', 0, 'erlangga-2021_10_17_145414.jpg', NULL, NULL, '2021-10-17 14:54:14', NULL, NULL, '2021-10-17 15:13:40', NULL, NULL, 'Erlangga Lorem Ipsum is simply dummy text of the printing and typesetting industry. ', '', '', '');
INSERT INTO `system_users` VALUES ('3WzM9-fblxtSv6oY', 'Fe1sF-CrKLQ2EhuP', 'Ali Muchtarom, S.Kom', 'tarom@gmail.com', 'tarom', '$2y$10$fEnePKtKIUGzn3eVFrPNlus.jAb3GfRozulBwQlepdRrXudgWO1RK', 0, 'tarom-2021_10_17_145448.png', NULL, NULL, '2021-10-17 14:54:48', NULL, NULL, '2021-10-17 15:13:13', NULL, NULL, 'Tarom Lorem Ipsum is simply dummy text of the printing and typesetting industry. ', 'https://facebook.com/didikam', 'https://twitter.com/didikam', 'https://instagram.com/didikam');
INSERT INTO `system_users` VALUES ('3WzM9-qraFtznOsj', 'Fe1sF-CrKLQ2EhuP', 'Ayu Mauliana Intahaya, S.Kom', 'ayu@gmail.com', 'ayu', '$2y$10$VVXV8T32.hp4ZDl7FIEkOOmBjFEFNl8mjpECcpDoMQtFmOi.hcXSi', 0, 'ayu-2021_10_17_145845.jpg', NULL, NULL, '2021-10-17 14:58:45', NULL, NULL, '2021-10-17 15:13:24', NULL, NULL, 'Ayu Lorem Ipsum is simply dummy text of the printing and typesetting industry. ', '', '', '');

-- ----------------------------
-- Table structure for tags
-- ----------------------------
DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags`  (
  `tag_id` char(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tag_nama` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tag_slug` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tag_created_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  `tag_deleted_at` timestamp(0) NULL DEFAULT NULL,
  `tag_updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`tag_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tags
-- ----------------------------
INSERT INTO `tags` VALUES ('OjnsH-BvJmhzUMXq', 'Hosting', 'hosting', '2021-10-10 00:13:20', NULL, '2021-10-10 00:17:07');
INSERT INTO `tags` VALUES ('OjnsH-fSgFYnPYKH', 'Metode', 'metode', '2021-10-10 00:13:49', NULL, '2021-10-10 00:17:10');
INSERT INTO `tags` VALUES ('OjnsH-hbkPnTEJPv', 'Tugas Akhir', 'tugas-akhir', '2021-10-10 00:13:38', NULL, '2021-10-10 00:17:14');
INSERT INTO `tags` VALUES ('OjnsH-kJlsjKTefP', 'Programming', 'programming', '2021-10-10 00:12:19', NULL, '2021-10-10 00:17:17');
INSERT INTO `tags` VALUES ('OjnsH-sWMwcNQdWv', 'Perhitungan', 'perhitungan', '2021-10-10 00:13:59', NULL, '2021-10-10 00:17:20');

SET FOREIGN_KEY_CHECKS = 1;

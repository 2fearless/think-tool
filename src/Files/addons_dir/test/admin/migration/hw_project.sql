/*
 Navicat Premium Data Transfer

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : newxjrd

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 12/12/2022 09:00:20
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for hw_project
-- ----------------------------
DROP TABLE IF EXISTS `hw_project`;
CREATE TABLE `hw_project`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sys_user_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上报人',
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上报人',
  `depart_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '部门id',
  `street_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '街道',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '项目名称',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '项目简介',
  `money` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '项目金额',
  `duration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '项目工期',
  `lead_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '领导',
  `gw_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '负责人',
  `quarter1` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '一季度',
  `quarter2` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '二季度',
  `quarter3` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '三季度',
  `quarter4` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '四季度',
  `month1` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '月度',
  `month2` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '月度',
  `month3` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '月度',
  `month4` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '月度',
  `month5` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '月度',
  `month6` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '月度',
  `month7` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '月度',
  `month8` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '月度',
  `month9` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '月度',
  `month10` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '月度',
  `month11` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '月度',
  `month12` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '月度',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '0未审核,1正常',
  `view` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0未审核,1完成,2驳回',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '类型',
  `tag` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0基础工作,1量化项目',
  `progress` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '进度',
  `images` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '照片',
  `longitude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '照片',
  `latitude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '照片',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '照片',
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '驳回原因',
  `create_time` timestamp(0) NULL DEFAULT NULL,
  `update_time` timestamp(0) NULL DEFAULT NULL,
  `color` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '0基础工作,1量化项目',
  `score` tinyint(3) UNSIGNED NOT NULL DEFAULT 10 COMMENT '0基础工作,1量化项目',
  `oversee` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0基础工作,1量化项目',
  `lead_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '照片',
  `lead_mobile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '照片',
  `head_leader` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '副县长名字',
  `real_type` tinyint(3) NOT NULL DEFAULT 1 COMMENT '是否实体项目',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sys_user_id`(`sys_user_id`) USING BTREE,
  INDEX `depart_id`(`depart_id`) USING BTREE,
  INDEX `title`(`title`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `type`(`type`) USING BTREE,
  INDEX `tag`(`tag`) USING BTREE,
  INDEX `view`(`view`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 180 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '项目表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of hw_project
-- ----------------------------
INSERT INTO `hw_project` VALUES (20, 276, 12, 19, 1, '抓好130个以上重点建设项目，完成投资80亿元以上', '抓好130个以上重点建设项目，完成投资80亿元以上', '', '12个月', 18, 0, '', '', '', '', '', '138个项目重点建设项目累计完成投资10亿元', '138个项目重点建设项目累计完成投资18亿元', '138个项目重点建设项目累计完成投资23亿元', '138个项目重点建设项目累计完成投资30亿元', '138个项目重点建设项目累计完成投资40亿元', '138个项目重点建设项目累计完成投资45亿元', '138个项目重点建设项目累计完成投资51亿元', '138个项目重点建设项目累计完成投资 60亿元', '138个项目重点建设项目累计完成投资65亿元', '138个项目重点建设项目累计完成投资71亿元', '138个项目重点建设项目累计完成投资80亿元', 1, 1, 1, 1, 0, '', '120.738044', '28.856138', '仙居先发改局', '', '2022-04-22 13:27:48', '2022-06-20 16:07:16', 1, 10, 0, '县发展和改革局', '', '何晔', 1);
INSERT INTO `hw_project` VALUES (21, 278, 12, 19, 21, '攻坚80个以上项目前期，确保重点前期项目转化率40%以上', '攻坚80个以上项目前期，确保重点前期项目转化率40%以上', '', '12个月', 18, 0, '', '', '', '', '', '抓好85个前期项目1-2月份计划节点完成情况', '抓好85个前期项目1-3月份计划节点完成情况', '抓好85个前期项目1-4月份计划节点完成情况', '抓好85个前期项目1-5月份计划节点完成情况', '抓好85个前期项目1-6月份计划节点完成情况', '抓好85个前期项目1-7月份计划节点完成情况', '抓好85个前期项目1-8月份计划节点完成情况', '抓好85个前期项目1-9月份计划节点完成情况', '抓好85个前期项目1-10月份计划节点完成情况', '抓好85个前期项目1-11月份计划节点完成情况', '抓好85个前期项目1-12月份计划节点完成情况，转化率40%以上', 1, 1, 1, 1, 0, '', '120.735081', '28.849213', '仙居县全县', '', '2022-04-22 13:27:48', '2022-06-21 08:56:14', 1, 10, 0, '县发展和改革局', '', '何晔', 1);
INSERT INTO `hw_project` VALUES (22, 276, 12, 19, 2, '新开工项目40个以上', '新开工项目40个以上', '', '12个月', 18, 0, '', '', '', '', '', '累计新开工项目2个', '累计新开工项目14个', '累计新开工项目17个', '累计新开工项目20个', '累计新开工项目29个', '累计新开工项目30个', '累计新开工项目31个', '累计新开工项目36个', '累计新开工项目38个', '累计新开工项目39个', '累计新开工项目40个', 1, 1, 1, 1, 14, '', '120.738108', '28.856374', '仙居县发改局', '', '2022-04-22 13:27:48', '2022-10-24 11:08:57', 1, 10, 0, '县发展和改革局', '', '何晔', 1);
INSERT INTO `hw_project` VALUES (23, 278, 0, 19, 1, '省“152”工程开工率60%以上', '省“152”工程开工率60%以上', '', '12个月', 18, 187, NULL, NULL, NULL, NULL, NULL, '上报省152工程（司太立锂电池电解液系列产品项目、丰安生物化学原料药及制剂项目、神仙居水街项目）四个季度计划节点和年度工作目标', '完成三个项目的一季度计划节点', '开展三个项目的二季度计划节点', '做好三个项目的二季度计划节点', '完成三个项目的二季度计划节点，1个项目开工建设，开工率33.3%', '开展三个项目的三季度计划节点', '做好三个项目的三季度计划节点', '完成三个项目的三季度计划节点，2个项目开工建设，开工率66.7%', '开展三个项目的四季度计划节点', '做好三个项目的四季度计划节点，3个项目开工建设，开工率100%', '完成三个项目的四季度计划节点，加快三个项目工程建设', 1, 1, 1, 1, 50, NULL, '', '', '', NULL, '2022-04-22 13:27:48', '2022-10-24 11:08:57', 1, 10, 0, '', '', '何晔', 1);
INSERT INTO `hw_project` VALUES (179, 253, 0, 87, 3, '非实体', '是的方法', '11', '', 0, 404, NULL, NULL, NULL, NULL, '班干部', '', '', '', '', '', '', '', '', '', '', '', 1, 0, 1, 1, 66, 'project/20221027\\6b4054aaf505a413162076519fe3298e.png', '', '', '', NULL, '2022-10-27 08:50:15', '2022-10-27 08:50:15', 1, 10, 0, '', '', NULL, 0);

SET FOREIGN_KEY_CHECKS = 1;

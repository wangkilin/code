DROP TABLE IF EXISTS `icb_post_module`;
CREATE TABLE `icb_post_module` (
  `id` int(11) NOT NULL COMMENT 'id',
  `title` varchar(20) NOT NULL COMMENT '可发布内容的模块名称',
  `url_token` varchar(20) NOT NULL COMMENT '模块别名',
  `notes` text NOT NULL COMMENT '备注信息'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支持发布内容的模块列表';

ALTER TABLE `icb_post_module`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `icb_post_module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

-- 20181024
alter table icb_category
  add path varchar(255) not null default '/' comment '分类父级路径' after parent_id;

-- 20181119
ALTER TABLE `icb_course_content_table`
ADD `category_id` INT(10) NOT NULL DEFAULT '0' COMMENT '所属分类id' AFTER `description`,
ADD INDEX (`category_id`);

-- 20181202
ALTER TABLE `icb_course` CHANGE `parent_id` `category_id` INT(10) NOT NULL COMMENT '对应 分类id';

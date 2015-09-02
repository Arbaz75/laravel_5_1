ALTER TABLE  `members` CHANGE  `name`  `first_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

ALTER TABLE  `members` ADD  `last_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `first_name` ;

ALTER TABLE  `member_token` CHANGE  `last_updated`  `last_updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ;

ALTER TABLE  `member_token` ADD  `requests` INT NOT NULL DEFAULT  '0';

ALTER TABLE  `member_token` CHANGE  `last_updated`  `last_updated` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ;

ALTER TABLE  `event` CHANGE  `goal_time`  `goal_time` TIME NOT NULL ;
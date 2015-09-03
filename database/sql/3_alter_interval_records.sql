ALTER TABLE  `interval_records` ADD  `lap_id` INT NOT NULL AFTER  `event_id` ;

ALTER TABLE  `interval_records` DROP  `end_time` ;

ALTER TABLE  `interval_records` CHANGE  `start_time`  `lap_time` TIME NOT NULL ;
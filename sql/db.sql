DROP FUNCTION IF EXISTS `ordered_uuid`;

DROP TABLE IF EXISTS `tweet`;
DROP TABLE IF EXISTS `user`;

DELIMITER //
CREATE FUNCTION `ordered_uuid`(uuid BINARY(36))
RETURNS binary(16) DETERMINISTIC
RETURN UNHEX(
  CONCAT(
    SUBSTR(uuid, 15, 4),
    SUBSTR(uuid, 10, 4),
    SUBSTR(uuid, 1, 8),
    SUBSTR(uuid, 20, 4),
    SUBSTR(uuid, 25))
);
//
DELIMITER ;

CREATE TABLE `user` (
  `id` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `joined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY (`joined`)
);

CREATE TABLE `tweet` (
  `id` binary(16) NOT NULL,
  `user_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message` varchar(140) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX (`user_id`, `ts`),
  FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
);

CREATE TRIGGER before_insert_tweet
  BEFORE INSERT ON `tweet`
  FOR EACH ROW SET new.id = ordered_uuid(uuid());

INSERT INTO `user` (`id`, `joined`, `name`) VALUES
  ('Princess_Leia', '2016-12-01 12:00:00', 'Princess Leia'),
  ('Luke', '2016-01-01 12:00:00', 'Luke Skywalker'),
  ('Obi-Wan', '2015-01-03 12:00:00', 'Obi-Wan "Ben" Kenobi'),
  ('Darth_Vader', '2015-01-13 13:13:13', 'Anakin Skywalker'),
  ('Darth_Sidious', '2014-06-06 06:06:06', 'Emperor Palpatine'),
  ('Mace_Windu', '2024-12-19 10:30:00', 'Mace Windu')
;

INSERT INTO `tweet` (`user_id`, `ts`, `message`) VALUES
  ('Luke', '2017-01-01 12:34:56', '@Princess_Leia: I\'m @Luke Skywalker and I\'m here to rescue you!'),
  ('Obi-Wan', '2016-01-01 14:00:00', '@Luke, the Force will be with you'),
  ('Obi-Wan', '2016-03-02 15:00:00', 'Use the Force, @Luke'),
  ('Obi-Wan', '2016-05-08 09:00:00', 'Your clones are very impressive. You must be very proud.'),
  ('Obi-Wan', '2016-05-09 11:30:00', 'Blast. This is why I hate flying.'),
  ('Darth_Vader', '2017-01-13 16:00:00', '@Luke, I am your father'),
  ('Darth_Vader', '2016-05-10 19:00:00', 'I\'ve been waiting for you, @Obi-Wan. We meet again, at last.'),
  ('Darth_Vader', '2016-06-06 23:00:00', 'Your powers are weak, old man.'),
  ('Darth_Sidious', '2015-05-19 18:00:00', 'Good, good. Let the hate flow through you.'),
  ('Darth_Sidious', '2016-07-04 13:00:00', 'Everything is proceeding as I have foreseen.'),
  ('Darth_Sidious', '2017-02-01 20:00:00', '@Darth_Vader: You have done well, my apprentice.'),
  ('Darth_Sidious', '2015-09-09 09:09:09', 'The dark side of the Force is a pathway to many abilities some consider to be unnatural.'),
  ('Mace_Windu', '2015-04-20 11:00:00', 'This party\'s over.'),
  ('Mace_Windu', '2016-02-14 14:00:00', 'The oppression of the Sith will never return. You have lost.'),
  ('Mace_Windu', '2016-08-08 16:30:00', '@Obi-Wan: May the Force be with us all.'),
  ('Mace_Windu', '2015-12-25 12:00:00', 'In the name of the Galactic Senate of the Republic, you are under arrest.'),
  ('Mace_Windu', '2024-12-19 15:00:00', 'I defeated Darth Sidious and brought balance to the Force!')
;

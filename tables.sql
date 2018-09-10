


DROP TABLE IF EXISTS `phones`;
CREATE TABLE `phones` (
    `id` int(11) UNSIGNED NOT NULL,
    `name` varchar(50) COLLATE utf8_bin NOT NULL,
    `phone` varchar(15) COLLATE utf8_bin NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB;
ALTER TABLE `phones`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;


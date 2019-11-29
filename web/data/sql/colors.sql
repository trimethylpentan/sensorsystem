CREATE TABLE IF NOT EXISTS `colors`
(
    `color_id` int(11) NOT NULL AUTO_INCREMENT,
    `red`      float   NOT NULL,
    `green`    float   NOT NULL,
    `blue`     float   NOT NULL,
    PRIMARY KEY (`color_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

INSERT INTO colors (red, blue, green)
VALUES (0, 0, 0);

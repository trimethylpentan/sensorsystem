CREATE TABLE IF NOT EXISTS `measurements`
(
    `timestamp`   datetime NOT NULL,
    `color`       int(11)  NOT NULL,
    `humidity`    float    NOT NULL,
    `temperature` float    NOT NULL,
    `air_pressure`float    NOT NULL,
    PRIMARY KEY (`timestamp`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

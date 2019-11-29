ALTER TABLE `measurements`
    ADD CONSTRAINT `fk_color`
        FOREIGN KEY (`color`)
            REFERENCES `colors` (`color_id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE;

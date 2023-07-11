CREATE TABLE IF NOT EXISTS `cmw_wiki_articles`
(
    `wiki_articles_id`             INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `wiki_articles_category_id`    INT(11) UNSIGNED NOT NULL,
    `wiki_articles_position`       INT(11) UNSIGNED NOT NULL DEFAULT '0',
    `wiki_articles_is_define`      INT(1) UNSIGNED  NOT NULL DEFAULT '1',
    `wiki_articles_title`          VARCHAR(255)     NOT NULL,
    `wiki_articles_content`        LONGTEXT         NOT NULL,
    `wiki_articles_slug`           VARCHAR(255)     NOT NULL,
    `wiki_articles_icon`           VARCHAR(35)               DEFAULT NULL,
    `wiki_articles_date_create`    TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `wiki_articles_date_update`    TIMESTAMP        NULL     DEFAULT CURRENT_TIMESTAMP,
    `wiki_articles_author_id`      INT(11)          NOT NULL,
    `wiki_articles_last_editor_id` INT(11)          NOT NULL,
    PRIMARY KEY (`wiki_articles_id`),
    KEY `cmw_wiki_category_id_fk` (`wiki_articles_category_id`),
    CONSTRAINT `cmw_wiki_categories_id_fk` FOREIGN KEY (`wiki_articles_category_id`)
        REFERENCES `cmw_wiki_categories` (`wiki_categories_id`) ON DELETE CASCADE
) ENGINE = InnoDB
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cmw_wiki_categories`
(
    `wiki_categories_id`          INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `wiki_categories_name`        VARCHAR(255)     NOT NULL,
    `wiki_categories_description` VARCHAR(255)     NOT NULL,
    `wiki_categories_slug`        VARCHAR(255)     NOT NULL,
    `wiki_categories_icon`        VARCHAR(35)               DEFAULT NULL,
    `wiki_categories_date_create` TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `wiki_categories_date_update` TIMESTAMP        NULL     DEFAULT CURRENT_TIMESTAMP,
    `wiki_categories_position`    INT(11) UNSIGNED NOT NULL DEFAULT '0',
    `wiki_categories_is_define`   INT(1) UNSIGNED  NOT NULL DEFAULT '1',
    PRIMARY KEY (`wiki_categories_id`),
    UNIQUE KEY `cmw_wiki_categories_slug_unique` (`wiki_categories_slug`)
) ENGINE = InnoDB
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

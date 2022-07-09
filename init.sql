CREATE TABLE IF NOT EXISTS `cmw_wiki_articles`
(
    `wiki_articles_id`          int(10) UNSIGNED NOT NULL,
    `wiki_articles_category_id` int(10) UNSIGNED NOT NULL,
    `wiki_articles_position`    int(10) UNSIGNED NOT NULL DEFAULT '0',
    `wiki_articles_is_define`   int(1) UNSIGNED  NOT NULL DEFAULT '1',
    `wiki_articles_title`       varchar(255)     NOT NULL,
    `wiki_articles_content`     longtext         NOT NULL,
    `wiki_articles_slug`        varchar(255)     NOT NULL,
    `wiki_articles_icon`        varchar(35)               DEFAULT NULL,
    `wiki_articles_date_create` timestamp        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `wiki_articles_date_update` timestamp        NULL     DEFAULT CURRENT_TIMESTAMP,
    `wiki_articles_author_id`      int(11)          NOT NULL,
    `wiki_articles_last_editor_id` int(11)          NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS `cmw_wiki_categories`
(
    `wiki_categories_id`          int(11) UNSIGNED NOT NULL,
    `wiki_categories_name`        varchar(255)     NOT NULL,
    `wiki_categories_description` varchar(255)     NOT NULL,
    `wiki_categories_slug`        varchar(255)     NOT NULL,
    `wiki_categories_icon`        varchar(35)               DEFAULT NULL,
    `wiki_categories_date_create` timestamp        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `wiki_categories_date_update` timestamp        NULL     DEFAULT CURRENT_TIMESTAMP,
    `wiki_categories_position`    int(11) UNSIGNED NOT NULL DEFAULT '0',
    `wiki_categories_is_define`   int(1) UNSIGNED  NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;


ALTER TABLE `cmw_wiki_categories`
    ADD PRIMARY KEY (`wiki_categories_id`),
    ADD UNIQUE KEY `cmw_wiki_categories_slug_unique` (`wiki_categories_slug`);


ALTER TABLE `cmw_wiki_articles`
    ADD PRIMARY KEY (`wiki_articles_id`),
    ADD KEY `cmw_wiki_category_id_fk` (`wiki_articles_category_id`);


ALTER TABLE `cmw_wiki_articles`
    MODIFY `wiki_articles_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;


ALTER TABLE `cmw_wiki_categories`
    MODIFY `wiki_categories_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;


ALTER TABLE `cmw_wiki_articles`
    ADD CONSTRAINT `cmw_wiki_categories_id_fk` FOREIGN KEY (`wiki_articles_category_id`) REFERENCES `cmw_wiki_categories` (`wiki_categories_id`) ON DELETE CASCADE;
COMMIT;

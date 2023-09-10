<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230904183115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dashboard_config (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, animals_num INTEGER DEFAULT NULL, blog_posts_num INTEGER DEFAULT NULL, events_date_range DATETIME DEFAULT NULL)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__image AS SELECT id, animal_id, gallery_id, blog_post_id, url, figcaption, animal_front_image, post_front_image FROM image');
        $this->addSql('DROP TABLE image');
        $this->addSql('CREATE TABLE image (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, animal_id INTEGER DEFAULT NULL, gallery_id INTEGER DEFAULT NULL, blog_post_id INTEGER DEFAULT NULL, url VARCHAR(255) NOT NULL, figcaption VARCHAR(255) NOT NULL, animal_front_image BOOLEAN DEFAULT NULL, post_front_image BOOLEAN DEFAULT NULL, CONSTRAINT FK_C53D045F8E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C53D045F4E7AF8F FOREIGN KEY (gallery_id) REFERENCES gallery (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C53D045FA77FBEAF FOREIGN KEY (blog_post_id) REFERENCES blog_post (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO image (id, animal_id, gallery_id, blog_post_id, url, figcaption, animal_front_image, post_front_image) SELECT id, animal_id, gallery_id, blog_post_id, url, figcaption, animal_front_image, post_front_image FROM __temp__image');
        $this->addSql('DROP TABLE __temp__image');
        $this->addSql('CREATE INDEX IDX_C53D045FA77FBEAF ON image (blog_post_id)');
        $this->addSql('CREATE INDEX IDX_C53D045F4E7AF8F ON image (gallery_id)');
        $this->addSql('CREATE INDEX IDX_C53D045F8E962C16 ON image (animal_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE dashboard_config');
        $this->addSql('CREATE TEMPORARY TABLE __temp__image AS SELECT id, animal_id, gallery_id, blog_post_id, url, figcaption, animal_front_image, post_front_image FROM image');
        $this->addSql('DROP TABLE image');
        $this->addSql('CREATE TABLE image (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, animal_id INTEGER DEFAULT NULL, gallery_id INTEGER DEFAULT NULL, blog_post_id INTEGER DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, figcaption VARCHAR(255) DEFAULT NULL, animal_front_image BOOLEAN DEFAULT NULL, post_front_image BOOLEAN DEFAULT NULL, CONSTRAINT FK_C53D045F8E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C53D045F4E7AF8F FOREIGN KEY (gallery_id) REFERENCES gallery (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C53D045FA77FBEAF FOREIGN KEY (blog_post_id) REFERENCES blog_post (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO image (id, animal_id, gallery_id, blog_post_id, url, figcaption, animal_front_image, post_front_image) SELECT id, animal_id, gallery_id, blog_post_id, url, figcaption, animal_front_image, post_front_image FROM __temp__image');
        $this->addSql('DROP TABLE __temp__image');
        $this->addSql('CREATE INDEX IDX_C53D045F8E962C16 ON image (animal_id)');
        $this->addSql('CREATE INDEX IDX_C53D045F4E7AF8F ON image (gallery_id)');
        $this->addSql('CREATE INDEX IDX_C53D045FA77FBEAF ON image (blog_post_id)');
    }
}

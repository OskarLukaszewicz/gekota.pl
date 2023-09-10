<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230904190639 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__dashboard_config AS SELECT id, animals_num, blog_posts_num FROM dashboard_config');
        $this->addSql('DROP TABLE dashboard_config');
        $this->addSql('CREATE TABLE dashboard_config (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, animals_num INTEGER DEFAULT NULL, blog_posts_num INTEGER DEFAULT NULL, events_weeks_num INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO dashboard_config (id, animals_num, blog_posts_num) SELECT id, animals_num, blog_posts_num FROM __temp__dashboard_config');
        $this->addSql('DROP TABLE __temp__dashboard_config');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__dashboard_config AS SELECT id, animals_num, blog_posts_num FROM dashboard_config');
        $this->addSql('DROP TABLE dashboard_config');
        $this->addSql('CREATE TABLE dashboard_config (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, animals_num INTEGER DEFAULT NULL, blog_posts_num INTEGER DEFAULT NULL, events_date_range DATETIME DEFAULT NULL)');
        $this->addSql('INSERT INTO dashboard_config (id, animals_num, blog_posts_num) SELECT id, animals_num, blog_posts_num FROM __temp__dashboard_config');
        $this->addSql('DROP TABLE __temp__dashboard_config');
    }
}

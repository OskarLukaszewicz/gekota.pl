<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230910213105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dashboard_config ADD COLUMN chart_days_range INTEGER DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__dashboard_config AS SELECT id, animals_num, blog_posts_num, events_weeks_num, notes FROM dashboard_config');
        $this->addSql('DROP TABLE dashboard_config');
        $this->addSql('CREATE TABLE dashboard_config (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, animals_num INTEGER DEFAULT 4, blog_posts_num INTEGER DEFAULT 4, events_weeks_num INTEGER DEFAULT 4, notes CLOB DEFAULT NULL)');
        $this->addSql('INSERT INTO dashboard_config (id, animals_num, blog_posts_num, events_weeks_num, notes) SELECT id, animals_num, blog_posts_num, events_weeks_num, notes FROM __temp__dashboard_config');
        $this->addSql('DROP TABLE __temp__dashboard_config');
    }
}

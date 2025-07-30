<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250730014423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE video_game_platform (video_game_id INT NOT NULL, platform_id INT NOT NULL, PRIMARY KEY(video_game_id, platform_id))');
        $this->addSql('CREATE INDEX IDX_996C03DD16230A8 ON video_game_platform (video_game_id)');
        $this->addSql('CREATE INDEX IDX_996C03DDFFE6496F ON video_game_platform (platform_id)');
        $this->addSql('ALTER TABLE video_game_platform ADD CONSTRAINT FK_996C03DD16230A8 FOREIGN KEY (video_game_id) REFERENCES video_game (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE video_game_platform ADD CONSTRAINT FK_996C03DDFFE6496F FOREIGN KEY (platform_id) REFERENCES platform (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE video_game_platform DROP CONSTRAINT FK_996C03DD16230A8');
        $this->addSql('ALTER TABLE video_game_platform DROP CONSTRAINT FK_996C03DDFFE6496F');
        $this->addSql('DROP TABLE video_game_platform');
    }
}

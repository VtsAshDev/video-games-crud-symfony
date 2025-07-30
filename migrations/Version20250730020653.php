<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250730020653 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE video_game_video_game (video_game_source INT NOT NULL, video_game_target INT NOT NULL, PRIMARY KEY(video_game_source, video_game_target))');
        $this->addSql('CREATE INDEX IDX_27FEFAD73D631A ON video_game_video_game (video_game_source)');
        $this->addSql('CREATE INDEX IDX_27FEFAD719D83395 ON video_game_video_game (video_game_target)');
        $this->addSql('ALTER TABLE video_game_video_game ADD CONSTRAINT FK_27FEFAD73D631A FOREIGN KEY (video_game_source) REFERENCES video_game (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE video_game_video_game ADD CONSTRAINT FK_27FEFAD719D83395 FOREIGN KEY (video_game_target) REFERENCES video_game (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE video_game_platform DROP CONSTRAINT fk_996c03dd16230a8');
        $this->addSql('ALTER TABLE video_game_platform DROP CONSTRAINT fk_996c03ddffe6496f');
        $this->addSql('DROP TABLE video_game_platform');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE video_game_platform (video_game_id INT NOT NULL, platform_id INT NOT NULL, PRIMARY KEY(video_game_id, platform_id))');
        $this->addSql('CREATE INDEX idx_996c03dd16230a8 ON video_game_platform (video_game_id)');
        $this->addSql('CREATE INDEX idx_996c03ddffe6496f ON video_game_platform (platform_id)');
        $this->addSql('ALTER TABLE video_game_platform ADD CONSTRAINT fk_996c03dd16230a8 FOREIGN KEY (video_game_id) REFERENCES video_game (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE video_game_platform ADD CONSTRAINT fk_996c03ddffe6496f FOREIGN KEY (platform_id) REFERENCES platform (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE video_game_video_game DROP CONSTRAINT FK_27FEFAD73D631A');
        $this->addSql('ALTER TABLE video_game_video_game DROP CONSTRAINT FK_27FEFAD719D83395');
        $this->addSql('DROP TABLE video_game_video_game');
    }
}

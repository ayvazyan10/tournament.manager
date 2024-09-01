<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240901194559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, home_team_id INT NOT NULL, away_team_id INT NOT NULL, tournament_id INT NOT NULL, date DATETIME NOT NULL, home_score INT DEFAULT NULL, away_score INT DEFAULT NULL, INDEX IDX_232B318C9C4C13F6 (home_team_id), INDEX IDX_232B318C45185D02 (away_team_id), INDEX IDX_232B318C33D1A3E7 (tournament_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_BD5FB8D9989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament_teams (tournament_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_5794B24133D1A3E7 (tournament_id), INDEX IDX_5794B241296CD8AE (team_id), PRIMARY KEY(tournament_id, team_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C9C4C13F6 FOREIGN KEY (home_team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C45185D02 FOREIGN KEY (away_team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
        $this->addSql('ALTER TABLE tournament_teams ADD CONSTRAINT FK_5794B24133D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournament_teams ADD CONSTRAINT FK_5794B241296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C9C4C13F6');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C45185D02');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C33D1A3E7');
        $this->addSql('ALTER TABLE tournament_teams DROP FOREIGN KEY FK_5794B24133D1A3E7');
        $this->addSql('ALTER TABLE tournament_teams DROP FOREIGN KEY FK_5794B241296CD8AE');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE tournament');
        $this->addSql('DROP TABLE tournament_teams');
    }
}

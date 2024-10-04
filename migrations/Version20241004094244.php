<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241004094244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cards_theme DROP FOREIGN KEY FK_676F0B0859027487');
        $this->addSql('ALTER TABLE cards_theme DROP FOREIGN KEY FK_676F0B08DC555177');
        $this->addSql('DROP TABLE cards_theme');
        $this->addSql('ALTER TABLE cards ADD theme_id INT NOT NULL');
        $this->addSql('ALTER TABLE cards ADD CONSTRAINT FK_4C258FD59027487 FOREIGN KEY (theme_id) REFERENCES theme (id)');
        $this->addSql('CREATE INDEX IDX_4C258FD59027487 ON cards (theme_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cards_theme (cards_id INT NOT NULL, theme_id INT NOT NULL, INDEX IDX_676F0B0859027487 (theme_id), INDEX IDX_676F0B08DC555177 (cards_id), PRIMARY KEY(cards_id, theme_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cards_theme ADD CONSTRAINT FK_676F0B0859027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cards_theme ADD CONSTRAINT FK_676F0B08DC555177 FOREIGN KEY (cards_id) REFERENCES cards (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cards DROP FOREIGN KEY FK_4C258FD59027487');
        $this->addSql('DROP INDEX IDX_4C258FD59027487 ON cards');
        $this->addSql('ALTER TABLE cards DROP theme_id');
    }
}

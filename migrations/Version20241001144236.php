<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241001144236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cards_theme ADD CONSTRAINT FK_676F0B08DC555177 FOREIGN KEY (cards_id) REFERENCES cards (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cards_theme ADD CONSTRAINT FK_676F0B0859027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient DROP secu, CHANGE discription description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE scores ADD user_id INT NOT NULL, ADD theme_id INT NOT NULL, ADD scores DATETIME NOT NULL');
        $this->addSql('ALTER TABLE scores ADD CONSTRAINT FK_750375EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE scores ADD CONSTRAINT FK_750375E59027487 FOREIGN KEY (theme_id) REFERENCES theme (id)');
        $this->addSql('CREATE INDEX IDX_750375EA76ED395 ON scores (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_750375E59027487 ON scores (theme_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cards_theme DROP FOREIGN KEY FK_676F0B08DC555177');
        $this->addSql('ALTER TABLE cards_theme DROP FOREIGN KEY FK_676F0B0859027487');
        $this->addSql('ALTER TABLE scores DROP FOREIGN KEY FK_750375EA76ED395');
        $this->addSql('ALTER TABLE scores DROP FOREIGN KEY FK_750375E59027487');
        $this->addSql('DROP INDEX IDX_750375EA76ED395 ON scores');
        $this->addSql('DROP INDEX UNIQ_750375E59027487 ON scores');
        $this->addSql('ALTER TABLE scores DROP user_id, DROP theme_id, DROP scores');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBA76ED395');
        $this->addSql('ALTER TABLE patient ADD secu INT NOT NULL, CHANGE description discription VARCHAR(255) NOT NULL');
    }
}

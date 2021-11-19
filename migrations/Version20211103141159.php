<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211103141159 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication ADD grups_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779864CD824 FOREIGN KEY (grups_id) REFERENCES groupe (id)');
        $this->addSql('CREATE INDEX IDX_AF3C6779864CD824 ON publication (grups_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779864CD824');
        $this->addSql('DROP INDEX IDX_AF3C6779864CD824 ON publication');
        $this->addSql('ALTER TABLE publication DROP grups_id');
    }
}

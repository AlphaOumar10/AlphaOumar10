<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211103131001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe ADD publications_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C21AFFB3979 FOREIGN KEY (publications_id) REFERENCES publication (id)');
        $this->addSql('CREATE INDEX IDX_4B98C21AFFB3979 ON groupe (publications_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe DROP FOREIGN KEY FK_4B98C21AFFB3979');
        $this->addSql('DROP INDEX IDX_4B98C21AFFB3979 ON groupe');
        $this->addSql('ALTER TABLE groupe DROP publications_id');
    }
}

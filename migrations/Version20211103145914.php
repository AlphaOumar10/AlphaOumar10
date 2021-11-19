<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211103145914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C21A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_4B98C21A76ED395 ON groupe (user_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497A45358C');
        $this->addSql('DROP INDEX UNIQ_8D93D6497A45358C ON user');
        $this->addSql('ALTER TABLE user DROP groupe_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe DROP FOREIGN KEY FK_4B98C21A76ED395');
        $this->addSql('DROP INDEX IDX_4B98C21A76ED395 ON groupe');
        $this->addSql('ALTER TABLE groupe DROP user_id');
        $this->addSql('ALTER TABLE user ADD groupe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6497A45358C ON user (groupe_id)');
    }
}

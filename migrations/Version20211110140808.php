<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211110140808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCCF18BB82');
        $this->addSql('DROP INDEX IDX_67F068BCCF18BB82 ON commentaire');
        $this->addSql('ALTER TABLE commentaire DROP reponse_id');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E3CF18BB82');
        $this->addSql('DROP INDEX IDX_717E22E3CF18BB82 ON etudiant');
        $this->addSql('ALTER TABLE etudiant DROP reponse_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire ADD reponse_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCCF18BB82 FOREIGN KEY (reponse_id) REFERENCES reponse (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_67F068BCCF18BB82 ON commentaire (reponse_id)');
        $this->addSql('ALTER TABLE etudiant ADD reponse_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E3CF18BB82 FOREIGN KEY (reponse_id) REFERENCES reponse (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_717E22E3CF18BB82 ON etudiant (reponse_id)');
    }
}

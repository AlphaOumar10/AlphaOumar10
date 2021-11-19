<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211023171318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire ADD userss_id INT DEFAULT NULL, ADD etudiants_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCBF7E4280 FOREIGN KEY (userss_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA873A5C6 FOREIGN KEY (etudiants_id) REFERENCES etudiant (id)');
        $this->addSql('CREATE INDEX IDX_67F068BCBF7E4280 ON commentaire (userss_id)');
        $this->addSql('CREATE INDEX IDX_67F068BCA873A5C6 ON commentaire (etudiants_id)');
        $this->addSql('ALTER TABLE user ADD commentaires_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64917C4B2B0 FOREIGN KEY (commentaires_id) REFERENCES commentaire (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64917C4B2B0 ON user (commentaires_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCBF7E4280');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA873A5C6');
        $this->addSql('DROP INDEX IDX_67F068BCBF7E4280 ON commentaire');
        $this->addSql('DROP INDEX IDX_67F068BCA873A5C6 ON commentaire');
        $this->addSql('ALTER TABLE commentaire DROP userss_id, DROP etudiants_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64917C4B2B0');
        $this->addSql('DROP INDEX IDX_8D93D64917C4B2B0 ON user');
        $this->addSql('ALTER TABLE user DROP commentaires_id');
    }
}

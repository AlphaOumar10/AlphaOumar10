<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211110141057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reponse ADD reponse_u_id INT DEFAULT NULL, ADD reponse_e_id INT DEFAULT NULL, ADD comments_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7D8CEDAD3 FOREIGN KEY (reponse_u_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC788D78D4C FOREIGN KEY (reponse_e_id) REFERENCES etudiant (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC763379586 FOREIGN KEY (comments_id) REFERENCES commentaire (id)');
        $this->addSql('CREATE INDEX IDX_5FB6DEC7D8CEDAD3 ON reponse (reponse_u_id)');
        $this->addSql('CREATE INDEX IDX_5FB6DEC788D78D4C ON reponse (reponse_e_id)');
        $this->addSql('CREATE INDEX IDX_5FB6DEC763379586 ON reponse (comments_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7D8CEDAD3');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC788D78D4C');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC763379586');
        $this->addSql('DROP INDEX IDX_5FB6DEC7D8CEDAD3 ON reponse');
        $this->addSql('DROP INDEX IDX_5FB6DEC788D78D4C ON reponse');
        $this->addSql('DROP INDEX IDX_5FB6DEC763379586 ON reponse');
        $this->addSql('ALTER TABLE reponse DROP reponse_u_id, DROP reponse_e_id, DROP comments_id');
    }
}

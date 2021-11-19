<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211103143221 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe DROP FOREIGN KEY FK_4B98C21AFFB3979');
        $this->addSql('DROP INDEX IDX_4B98C21AFFB3979 ON groupe');
        $this->addSql('ALTER TABLE groupe DROP publications_id, DROP pots_id');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C67797A45358C');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779864CD824');
        $this->addSql('DROP INDEX IDX_AF3C67797A45358C ON publication');
        $this->addSql('DROP INDEX IDX_AF3C6779864CD824 ON publication');
        $this->addSql('ALTER TABLE publication DROP groupe_id, CHANGE grups_id communaute_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779C903E5B8 FOREIGN KEY (communaute_id) REFERENCES groupe (id)');
        $this->addSql('CREATE INDEX IDX_AF3C6779C903E5B8 ON publication (communaute_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe ADD publications_id INT DEFAULT NULL, ADD pots_id INT NOT NULL');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C21AFFB3979 FOREIGN KEY (publications_id) REFERENCES publication (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_4B98C21AFFB3979 ON groupe (publications_id)');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779C903E5B8');
        $this->addSql('DROP INDEX IDX_AF3C6779C903E5B8 ON publication');
        $this->addSql('ALTER TABLE publication ADD groupe_id INT NOT NULL, CHANGE communaute_id grups_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C67797A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779864CD824 FOREIGN KEY (grups_id) REFERENCES groupe (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_AF3C67797A45358C ON publication (groupe_id)');
        $this->addSql('CREATE INDEX IDX_AF3C6779864CD824 ON publication (grups_id)');
    }
}

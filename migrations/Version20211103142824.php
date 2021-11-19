<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211103142824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe ADD pots_id INT NOT NULL');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C21647EE785 FOREIGN KEY (pots_id) REFERENCES publication (id)');
        $this->addSql('CREATE INDEX IDX_4B98C21647EE785 ON groupe (pots_id)');
        $this->addSql('ALTER TABLE publication ADD grp_id INT DEFAULT NULL, ADD communaute_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779D51E9150 FOREIGN KEY (grp_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779C903E5B8 FOREIGN KEY (communaute_id) REFERENCES groupe (id)');
        $this->addSql('CREATE INDEX IDX_AF3C6779D51E9150 ON publication (grp_id)');
        $this->addSql('CREATE INDEX IDX_AF3C6779C903E5B8 ON publication (communaute_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe DROP FOREIGN KEY FK_4B98C21647EE785');
        $this->addSql('DROP INDEX IDX_4B98C21647EE785 ON groupe');
        $this->addSql('ALTER TABLE groupe DROP pots_id');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779D51E9150');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779C903E5B8');
        $this->addSql('DROP INDEX IDX_AF3C6779D51E9150 ON publication');
        $this->addSql('DROP INDEX IDX_AF3C6779C903E5B8 ON publication');
        $this->addSql('ALTER TABLE publication DROP grp_id, DROP communaute_id');
    }
}

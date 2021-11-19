<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211029005808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, sender_u_id INT NOT NULL, recipient_u_id INT NOT NULL, sender_e_id INT NOT NULL, recipient_e_id INT NOT NULL, message LONGTEXT NOT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_read TINYINT(1) NOT NULL, INDEX IDX_DB021E961B4F6543 (sender_u_id), INDEX IDX_DB021E9617290166 (recipient_u_id), INDEX IDX_DB021E964B5632DC (sender_e_id), INDEX IDX_DB021E96473056F9 (recipient_e_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E961B4F6543 FOREIGN KEY (sender_u_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E9617290166 FOREIGN KEY (recipient_u_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E964B5632DC FOREIGN KEY (sender_e_id) REFERENCES etudiant (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96473056F9 FOREIGN KEY (recipient_e_id) REFERENCES etudiant (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messages');
    }
}

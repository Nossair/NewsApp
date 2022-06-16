<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220608082451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address_mail (id INT AUTO_INCREMENT NOT NULL, mail VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE GroupMailsByUser (user_id INT NOT NULL, group_mail_id INT NOT NULL, INDEX IDX_FB74A7F2A76ED395 (user_id), INDEX IDX_FB74A7F24E773B0D (group_mail_id), PRIMARY KEY(user_id, group_mail_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE GroupMailsByUser ADD CONSTRAINT FK_FB74A7F2A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE GroupMailsByUser ADD CONSTRAINT FK_FB74A7F24E773B0D FOREIGN KEY (group_mail_id) REFERENCES group_mail (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE option_date_event CHANGE event_id event_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE address_mail');
        $this->addSql('DROP TABLE GroupMailsByUser');
        $this->addSql('ALTER TABLE option_date_event CHANGE event_id event_id INT DEFAULT NULL');
    }
}

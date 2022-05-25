<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220525135059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address_mail (id INT AUTO_INCREMENT NOT NULL, mail VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mailByGroupMail (address_mail_id INT NOT NULL, group_mail_id INT NOT NULL, INDEX IDX_362C1EC93929899 (address_mail_id), INDEX IDX_362C1EC4E773B0D (group_mail_id), PRIMARY KEY(address_mail_id, group_mail_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categoty_event (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, category_event_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, nbr_vote VARCHAR(255) DEFAULT NULL, is_archived TINYINT(1) NOT NULL, date_end_vote DATE NOT NULL, INDEX IDX_3BAE0AA7A76ED395 (user_id), INDEX IDX_3BAE0AA7C68D6CF0 (category_event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupMailByEvent (event_id INT NOT NULL, group_mail_id INT NOT NULL, INDEX IDX_4888736C71F7E88B (event_id), INDEX IDX_4888736C4E773B0D (group_mail_id), PRIMARY KEY(event_id, group_mail_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_mail (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE option_date_event (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, option_date DATE NOT NULL, nbr_vote INT NOT NULL, INDEX IDX_6C37FA1B71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Vote (user_id INT NOT NULL, option_date_event_id INT NOT NULL, INDEX IDX_FA222A5AA76ED395 (user_id), INDEX IDX_FA222A5A20DC25F5 (option_date_event_id), PRIMARY KEY(user_id, option_date_event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mailByGroupMail ADD CONSTRAINT FK_362C1EC93929899 FOREIGN KEY (address_mail_id) REFERENCES address_mail (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mailByGroupMail ADD CONSTRAINT FK_362C1EC4E773B0D FOREIGN KEY (group_mail_id) REFERENCES group_mail (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7C68D6CF0 FOREIGN KEY (category_event_id) REFERENCES categoty_event (id)');
        $this->addSql('ALTER TABLE groupMailByEvent ADD CONSTRAINT FK_4888736C71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupMailByEvent ADD CONSTRAINT FK_4888736C4E773B0D FOREIGN KEY (group_mail_id) REFERENCES group_mail (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE option_date_event ADD CONSTRAINT FK_6C37FA1B71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE Vote ADD CONSTRAINT FK_FA222A5AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Vote ADD CONSTRAINT FK_FA222A5A20DC25F5 FOREIGN KEY (option_date_event_id) REFERENCES option_date_event (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mailByGroupMail DROP FOREIGN KEY FK_362C1EC93929899');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7C68D6CF0');
        $this->addSql('ALTER TABLE groupMailByEvent DROP FOREIGN KEY FK_4888736C71F7E88B');
        $this->addSql('ALTER TABLE option_date_event DROP FOREIGN KEY FK_6C37FA1B71F7E88B');
        $this->addSql('ALTER TABLE mailByGroupMail DROP FOREIGN KEY FK_362C1EC4E773B0D');
        $this->addSql('ALTER TABLE groupMailByEvent DROP FOREIGN KEY FK_4888736C4E773B0D');
        $this->addSql('ALTER TABLE Vote DROP FOREIGN KEY FK_FA222A5A20DC25F5');
        $this->addSql('DROP TABLE address_mail');
        $this->addSql('DROP TABLE mailByGroupMail');
        $this->addSql('DROP TABLE categoty_event');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE groupMailByEvent');
        $this->addSql('DROP TABLE group_mail');
        $this->addSql('DROP TABLE option_date_event');
        $this->addSql('DROP TABLE Vote');
    }
}

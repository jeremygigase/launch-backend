<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200615150350 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, tag_usr_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_389B783E5289AD7 (tag_usr_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created DATE NOT NULL, birthday DATE NOT NULL, lastlogin DATE NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, tsk_usr_id INT NOT NULL, tsk_prj_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, created DATETIME NOT NULL, status VARCHAR(255) NOT NULL, tocomplete DATE NOT NULL, datecompleted DATE DEFAULT NULL, weight VARCHAR(255) NOT NULL, public TINYINT(1) NOT NULL, INDEX IDX_527EDB2530B1E56F (tsk_usr_id), INDEX IDX_527EDB25C8B1221C (tsk_prj_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE friend (id INT AUTO_INCREMENT NOT NULL, frn_usr1_id INT NOT NULL, frn_usr2_id INT NOT NULL, request VARCHAR(255) NOT NULL, INDEX IDX_55EEAC61589EB3C4 (frn_usr1_id), INDEX IDX_55EEAC614A2B1C2A (frn_usr2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE score (id INT AUTO_INCREMENT NOT NULL, scr_usr_id INT NOT NULL, amount INT NOT NULL, date DATE NOT NULL, INDEX IDX_329937511D599667 (scr_usr_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, img_usr_id INT NOT NULL, name VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, uploadtime DATETIME NOT NULL, INDEX IDX_C53D045FD14C639B (img_usr_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B783E5289AD7 FOREIGN KEY (tag_usr_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2530B1E56F FOREIGN KEY (tsk_usr_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25C8B1221C FOREIGN KEY (tsk_prj_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC61589EB3C4 FOREIGN KEY (frn_usr1_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC614A2B1C2A FOREIGN KEY (frn_usr2_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_329937511D599667 FOREIGN KEY (scr_usr_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FD14C639B FOREIGN KEY (img_usr_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B783E5289AD7');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB2530B1E56F');
        $this->addSql('ALTER TABLE friend DROP FOREIGN KEY FK_55EEAC61589EB3C4');
        $this->addSql('ALTER TABLE friend DROP FOREIGN KEY FK_55EEAC614A2B1C2A');
        $this->addSql('ALTER TABLE score DROP FOREIGN KEY FK_329937511D599667');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FD14C639B');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25C8B1221C');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE friend');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE score');
        $this->addSql('DROP TABLE image');
    }
}

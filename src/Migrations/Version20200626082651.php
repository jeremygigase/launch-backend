<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200626082651 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE friend DROP FOREIGN KEY FK_55EEAC614A2B1C2A');
        $this->addSql('ALTER TABLE friend DROP FOREIGN KEY FK_55EEAC61589EB3C4');
        $this->addSql('DROP INDEX IDX_55EEAC614A2B1C2A ON friend');
        $this->addSql('DROP INDEX IDX_55EEAC61589EB3C4 ON friend');
        $this->addSql('ALTER TABLE friend ADD sender_id INT NOT NULL, ADD receiver_id INT NOT NULL, DROP frn_usr1_id, DROP frn_usr2_id');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC61F624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC61CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_55EEAC61F624B39D ON friend (sender_id)');
        $this->addSql('CREATE INDEX IDX_55EEAC61CD53EDB6 ON friend (receiver_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE friend DROP FOREIGN KEY FK_55EEAC61F624B39D');
        $this->addSql('ALTER TABLE friend DROP FOREIGN KEY FK_55EEAC61CD53EDB6');
        $this->addSql('DROP INDEX IDX_55EEAC61F624B39D ON friend');
        $this->addSql('DROP INDEX IDX_55EEAC61CD53EDB6 ON friend');
        $this->addSql('ALTER TABLE friend ADD frn_usr1_id INT NOT NULL, ADD frn_usr2_id INT NOT NULL, DROP sender_id, DROP receiver_id');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC614A2B1C2A FOREIGN KEY (frn_usr2_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC61589EB3C4 FOREIGN KEY (frn_usr1_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_55EEAC614A2B1C2A ON friend (frn_usr2_id)');
        $this->addSql('CREATE INDEX IDX_55EEAC61589EB3C4 ON friend (frn_usr1_id)');
    }
}

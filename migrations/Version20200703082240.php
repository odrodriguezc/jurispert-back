<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200703082240 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE password_update_key DROP FOREIGN KEY FK_880C637B3256915B');
        $this->addSql('DROP INDEX UNIQ_880C637B3256915B ON password_update_key');
        $this->addSql('ALTER TABLE password_update_key CHANGE relation_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE password_update_key ADD CONSTRAINT FK_880C637BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_880C637BA76ED395 ON password_update_key (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE password_update_key DROP FOREIGN KEY FK_880C637BA76ED395');
        $this->addSql('DROP INDEX UNIQ_880C637BA76ED395 ON password_update_key');
        $this->addSql('ALTER TABLE password_update_key CHANGE user_id relation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE password_update_key ADD CONSTRAINT FK_880C637B3256915B FOREIGN KEY (relation_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_880C637B3256915B ON password_update_key (relation_id)');
    }
}

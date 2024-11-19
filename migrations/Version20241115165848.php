<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241115165848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment ADD contable_account_id INT NOT NULL');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D1B995AD8 FOREIGN KEY (contable_account_id) REFERENCES contable_account (id)');
        $this->addSql('CREATE INDEX IDX_6D28840D1B995AD8 ON payment (contable_account_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D1B995AD8');
        $this->addSql('DROP INDEX IDX_6D28840D1B995AD8 ON payment');
        $this->addSql('ALTER TABLE payment DROP contable_account_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241118222337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE journal_entry ADD associated_payment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_entry ADD CONSTRAINT FK_C8FAAE5AEF7482FA FOREIGN KEY (associated_payment_id) REFERENCES payment (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C8FAAE5AEF7482FA ON journal_entry (associated_payment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE journal_entry DROP FOREIGN KEY FK_C8FAAE5AEF7482FA');
        $this->addSql('DROP INDEX UNIQ_C8FAAE5AEF7482FA ON journal_entry');
        $this->addSql('ALTER TABLE journal_entry DROP associated_payment_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241115183719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE journal_entry ADD transaction_category_id INT NOT NULL, DROP transaction_type');
        $this->addSql('ALTER TABLE journal_entry ADD CONSTRAINT FK_C8FAAE5AAECF88CF FOREIGN KEY (transaction_category_id) REFERENCES transaction_category (id)');
        $this->addSql('CREATE INDEX IDX_C8FAAE5AAECF88CF ON journal_entry (transaction_category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE journal_entry DROP FOREIGN KEY FK_C8FAAE5AAECF88CF');
        $this->addSql('DROP INDEX IDX_C8FAAE5AAECF88CF ON journal_entry');
        $this->addSql('ALTER TABLE journal_entry ADD transaction_type VARCHAR(255) NOT NULL, DROP transaction_category_id');
    }
}

<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180904171538 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE invoices ADD approved_by_id INT DEFAULT NULL, ADD processed_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F952D234F6A FOREIGN KEY (approved_by_id) REFERENCES users (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F952FFD4FD3 FOREIGN KEY (processed_by_id) REFERENCES users (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_6A2F2F952D234F6A ON invoices (approved_by_id)');
        $this->addSql('CREATE INDEX IDX_6A2F2F952FFD4FD3 ON invoices (processed_by_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE invoices DROP FOREIGN KEY FK_6A2F2F952D234F6A');
        $this->addSql('ALTER TABLE invoices DROP FOREIGN KEY FK_6A2F2F952FFD4FD3');
        $this->addSql('DROP INDEX IDX_6A2F2F952D234F6A ON invoices');
        $this->addSql('DROP INDEX IDX_6A2F2F952FFD4FD3 ON invoices');
        $this->addSql('ALTER TABLE invoices DROP approved_by_id, DROP processed_by_id');
    }
}

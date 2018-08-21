<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180819154230 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE invoices (id INT AUTO_INCREMENT NOT NULL, carrier_id INT DEFAULT NULL, seller_id INT DEFAULT NULL, buyer_id INT DEFAULT NULL, loading_point_id INT DEFAULT NULL, unloading_point_id INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, order_date DATETIME DEFAULT NULL, delivery_date DATETIME DEFAULT NULL, attached_document VARCHAR(255) NOT NULL, INDEX IDX_6A2F2F9521DFC797 (carrier_id), INDEX IDX_6A2F2F958DE820D9 (seller_id), INDEX IDX_6A2F2F956C755722 (buyer_id), INDEX IDX_6A2F2F9537752631 (loading_point_id), INDEX IDX_6A2F2F95A386F81E (unloading_point_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F9521DFC797 FOREIGN KEY (carrier_id) REFERENCES companies (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F958DE820D9 FOREIGN KEY (seller_id) REFERENCES companies (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F956C755722 FOREIGN KEY (buyer_id) REFERENCES companies (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F9537752631 FOREIGN KEY (loading_point_id) REFERENCES company_addresses (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F95A386F81E FOREIGN KEY (unloading_point_id) REFERENCES company_addresses (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE users ADD company_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_1483A5E9979B1AD6 ON users (company_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE invoices');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9979B1AD6');
        $this->addSql('DROP INDEX IDX_1483A5E9979B1AD6 ON users');
        $this->addSql('ALTER TABLE users DROP company_id');
    }
}

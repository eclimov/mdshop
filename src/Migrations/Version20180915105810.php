<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180915105810 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE invoices ADD approved_by_employee_id INT DEFAULT NULL, ADD processed_by_employee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F958E8FB63B FOREIGN KEY (approved_by_employee_id) REFERENCES company_employees (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F95DC7C19EE FOREIGN KEY (processed_by_employee_id) REFERENCES company_employees (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_6A2F2F958E8FB63B ON invoices (approved_by_employee_id)');
        $this->addSql('CREATE INDEX IDX_6A2F2F95DC7C19EE ON invoices (processed_by_employee_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE invoices DROP FOREIGN KEY FK_6A2F2F958E8FB63B');
        $this->addSql('ALTER TABLE invoices DROP FOREIGN KEY FK_6A2F2F95DC7C19EE');
        $this->addSql('DROP INDEX IDX_6A2F2F958E8FB63B ON invoices');
        $this->addSql('DROP INDEX IDX_6A2F2F95DC7C19EE ON invoices');
        $this->addSql('ALTER TABLE invoices DROP approved_by_employee_id, DROP processed_by_employee_id');
    }
}

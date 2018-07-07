<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180707081726 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE banks (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company_addresses (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, address VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, INDEX IDX_5CD1FFE6979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bank_affiliates (id INT AUTO_INCREMENT NOT NULL, bank_id INT NOT NULL, affiliate_number VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, INDEX IDX_992CAC3911C8FB41 (bank_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE company_addresses ADD CONSTRAINT FK_5CD1FFE6979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)');
        $this->addSql('ALTER TABLE bank_affiliates ADD CONSTRAINT FK_992CAC3911C8FB41 FOREIGN KEY (bank_id) REFERENCES banks (id)');
        $this->addSql('ALTER TABLE companies ADD bank_affiliate_id INT NOT NULL, ADD iban VARCHAR(255) NOT NULL, ADD fiscal_code VARCHAR(255) NOT NULL, ADD vat VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE companies ADD CONSTRAINT FK_8244AA3A5F8A2381 FOREIGN KEY (bank_affiliate_id) REFERENCES bank_affiliates (id)');
        $this->addSql('CREATE INDEX IDX_8244AA3A5F8A2381 ON companies (bank_affiliate_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bank_affiliates DROP FOREIGN KEY FK_992CAC3911C8FB41');
        $this->addSql('ALTER TABLE companies DROP FOREIGN KEY FK_8244AA3A5F8A2381');
        $this->addSql('DROP TABLE banks');
        $this->addSql('DROP TABLE company_addresses');
        $this->addSql('DROP TABLE bank_affiliates');
        $this->addSql('DROP INDEX IDX_8244AA3A5F8A2381 ON companies');
        $this->addSql('ALTER TABLE companies DROP bank_affiliate_id, DROP iban, DROP fiscal_code, DROP vat');
    }
}

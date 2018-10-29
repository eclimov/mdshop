<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181029180552 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE companies ADD short_name VARCHAR(40) NOT NULL');
        $this->addSql('UPDATE companies SET companies.short_name = LEFT(companies.name, 25)');
        $this->addSql('ALTER TABLE companies DROP FOREIGN KEY FK_8244AA3A1032367E');
        $this->addSql('DROP TABLE company_kinds');
        $this->addSql('DROP INDEX IDX_8244AA3A1032367E ON companies');
        $this->addSql('ALTER TABLE companies DROP company_kind_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE company_kinds (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, created_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE companies ADD company_kind_id INT DEFAULT NULL, DROP short_name');
        $this->addSql('ALTER TABLE companies ADD CONSTRAINT FK_8244AA3A1032367E FOREIGN KEY (company_kind_id) REFERENCES company_kinds (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_8244AA3A1032367E ON companies (company_kind_id)');
    }
}

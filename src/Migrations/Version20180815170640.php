<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180815170640 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bank_affiliates CHANGE bank_id bank_id INT NOT NULL');
        $this->addSql('ALTER TABLE company_addresses DROP FOREIGN KEY FK_5CD1FFE6979B1AD6');
        $this->addSql('ALTER TABLE company_addresses ADD CONSTRAINT FK_5CD1FFE6979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bank_affiliates CHANGE bank_id bank_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company_addresses DROP FOREIGN KEY FK_5CD1FFE6979B1AD6');
        $this->addSql('ALTER TABLE company_addresses ADD CONSTRAINT FK_5CD1FFE6979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)');
    }
}

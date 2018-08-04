<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180802152256 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bank_affiliates DROP FOREIGN KEY FK_992CAC3911C8FB41');
        $this->addSql('ALTER TABLE bank_affiliates ADD CONSTRAINT FK_992CAC3911C8FB41 FOREIGN KEY (bank_id) REFERENCES banks (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bank_affiliates DROP FOREIGN KEY FK_992CAC3911C8FB41');
        $this->addSql('ALTER TABLE bank_affiliates ADD CONSTRAINT FK_992CAC3911C8FB41 FOREIGN KEY (bank_id) REFERENCES banks (id)');
    }
}

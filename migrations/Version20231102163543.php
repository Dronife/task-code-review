<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231102163543 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE customer MODIFY notification_type ENUM("sms", "email") NOT NULL DEFAULT "email"');
        $this->addSql('INSERT INTO customer (code, notification_type) VALUES ("test_code", "sms")');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM customer WHERE code = "test_code"');
        $this->addSql('ALTER TABLE customer MODIFY notification_type VARCHAR(32) NOT NULL');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250430040458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE client (
                id INT AUTO_INCREMENT NOT NULL, 
                first_name VARCHAR(100) NOT NULL, 
                last_name VARCHAR(100) NOT NULL, 
                phone_number VARCHAR(20) DEFAULT NULL, 
                email VARCHAR(100) DEFAULT NULL, 
                education VARCHAR(255) DEFAULT NULL, 
                give_agreement TINYINT(1) DEFAULT NULL, 
                PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE client
        SQL);
    }
}

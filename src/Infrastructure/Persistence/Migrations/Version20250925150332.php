<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250925150332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create exchange_rates table for storing currency exchange rates';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE exchange_rates (
            id VARCHAR(255) NOT NULL,
            currency_from VARCHAR(3) NOT NULL,
            currency_to VARCHAR(3) NOT NULL,
            rate DECIMAL(10,6) NOT NULL,
            date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE INDEX idx_currencies_date ON exchange_rates (currency_from, currency_to, date)');
        $this->addSql('CREATE INDEX idx_date ON exchange_rates (date)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE exchange_rates');
    }
}

<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160511193203 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        /** @noinspection SqlNoDataSourceInspection */
        /** @noinspection SqlResolve */
        $this->addSql('ALTER TABLE repairing_item ADD price DOUBLE PRECISION NOT NULL');
        /** @noinspection SqlNoDataSourceInspection */
        /** @noinspection SqlResolve */
        $this->addSql('ALTER TABLE selling_item ADD is_warranty_claimed TINYINT(1) NOT NULL, ADD warranty_expiration DATETIME NOT NULL, ADD price DOUBLE PRECISION NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        /** @noinspection SqlNoDataSourceInspection */
        /** @noinspection SqlResolve */
        $this->addSql('ALTER TABLE repairing_item DROP price');
        /** @noinspection SqlNoDataSourceInspection */
        /** @noinspection SqlResolve */
        $this->addSql('ALTER TABLE selling_item DROP is_warranty_claimed, DROP warranty_expiration, DROP price');
    }
}

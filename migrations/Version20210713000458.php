<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210713000458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add user data for authentification';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer ADD password VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE customer ADD user_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product CHANGE price price DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE product RENAME INDEX idx_b3ba5a5a2d98bf9 TO IDX_D34A04AD2D98BF9');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer DROP password');
        $this->addSql('ALTER TABLE customer DROP user_name');
        $this->addSql('ALTER TABLE product CHANGE price price NUMERIC(7, 2) NOT NULL');
        $this->addSql('ALTER TABLE product RENAME INDEX idx_d34a04ad2d98bf9 TO IDX_B3BA5A5A2D98BF9');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210713010917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'prebuild data for Product and Constructor table';
    }

    public function up(Schema $schema): void
    {
        $this->addSQl('INSERT INTO constructor (name) value("Pear"),("Sumsing")');
        $this->addSQL(
            'INSERT INTO product (constructor_id, name, description, price) value'.
            '(1,"Jphone 10","un téléphone bien puissant avec une autonomie améliorée par rapport à ses prédessesseurs", 1249.99),'.
            '(2,"Universe T2","L\'un des meilleurs appareil photo du marché",899.99),'.
            '(2,"Universe B60", "Un téléphone bien adapté pour le jeu",759.99)'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_B3BA5A5A2D98BF9');
        $this->addSql('TRUNCATE product');
        $this->addSql('TRUNCATE constructor');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_B3BA5A5A2D98BF9 FOREIGN KEY (constructor_id) REFERENCES constructor (id)');
    }
}

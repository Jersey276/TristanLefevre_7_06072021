<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210713030000 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'prebuild data for customer table';
    }

    // information : generated user :
    // Danube Marketplace | login : mileBoCust1 | pass : test1
    // Green telecom      | login : mileBoCust2 | pass : test2

    public function up(Schema $schema): void
    {
        $this->addSQL('INSERT INTO customer (name, user_name, password) value'.
            '("Danube Marketplace","mileBoCust1","$2y$13$RursWsNq15X54IoUfgAOuusAQ3H89BXAad/dAiG5YwB37T0Dr5r2O"),'.
            '("Green telecom","mileBoCust2","$2y$13$YMA1FOOMCNym09LuanskZuOzuzVu/2DbvBPrjxFxlMTaYWbyLX62a")'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6499395C3F3');
        $this->addSQL('TRUNCATE user');
        $this->addSql('TRUNCATE customer');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6499395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
    }
}

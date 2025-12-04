<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251129214119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment_method (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, card_type VARCHAR(255) NOT NULL, cardholder_name VARCHAR(255) NOT NULL, last_four_digits VARCHAR(4) NOT NULL, expiry_month INT NOT NULL, expiry_year INT NOT NULL, is_default TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_7B61A1F6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wishlist (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, product_id INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_9CE12A31A76ED395 (user_id), INDEX IDX_9CE12A314584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payment_method ADD CONSTRAINT FK_7B61A1F6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE wishlist ADD CONSTRAINT FK_9CE12A31A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE wishlist ADD CONSTRAINT FK_9CE12A314584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE order_issue ADD user_id INT NOT NULL, ADD order_id INT NOT NULL, ADD issue_type VARCHAR(255) NOT NULL, ADD description LONGTEXT NOT NULL, ADD status VARCHAR(20) NOT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE order_issue ADD CONSTRAINT FK_913357C3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE order_issue ADD CONSTRAINT FK_913357C38D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_913357C3A76ED395 ON order_issue (user_id)');
        $this->addSql('CREATE INDEX IDX_913357C38D9F6D38 ON order_issue (order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment_method DROP FOREIGN KEY FK_7B61A1F6A76ED395');
        $this->addSql('ALTER TABLE wishlist DROP FOREIGN KEY FK_9CE12A31A76ED395');
        $this->addSql('ALTER TABLE wishlist DROP FOREIGN KEY FK_9CE12A314584665A');
        $this->addSql('DROP TABLE payment_method');
        $this->addSql('DROP TABLE wishlist');
        $this->addSql('ALTER TABLE order_issue DROP FOREIGN KEY FK_913357C3A76ED395');
        $this->addSql('ALTER TABLE order_issue DROP FOREIGN KEY FK_913357C38D9F6D38');
        $this->addSql('DROP INDEX IDX_913357C3A76ED395 ON order_issue');
        $this->addSql('DROP INDEX IDX_913357C38D9F6D38 ON order_issue');
        $this->addSql('ALTER TABLE order_issue DROP user_id, DROP order_id, DROP issue_type, DROP description, DROP status, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81A76ED395');
    }
}

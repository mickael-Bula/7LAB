<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220921084302 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            CREATE TABLE constructeur (
                id SERIAL PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                country VARCHAR(100) NOT NULL,
                site VARCHAR(150) NOT NULL
            )'
        );
        $this->addSql('
            CREATE TABLE voiture (
                id SERIAL PRIMARY KEY,
                constructor_id INT NOT NULL,
                model VARCHAR(100) NOT NULL,
                length DOUBLE PRECISION NOT NULL,
                width DOUBLE PRECISION NOT NULL,
                weight DOUBLE PRECISION NOT NULL,
                seat INT NOT NULL,
                energy VARCHAR(100) NOT NULL,
                CONSTRAINT FK_constructor FOREIGN KEY (constructor_id) REFERENCES constructeur (id)
            )'
        );
        $this->addSql('
            CREATE TABLE messenger_messages (
                id BIGSERIAL PRIMARY KEY,
                body TEXT NOT NULL,
                headers TEXT NOT NULL,
                queue_name VARCHAR(190) NOT NULL,
                created_at TIMESTAMP NOT NULL,
                available_at TIMESTAMP NOT NULL,
                delivered_at TIMESTAMP DEFAULT NULL
            )'
        );
        $this->addSql('
            CREATE TABLE "user" (
                id SERIAL NOT NULL,
                email VARCHAR(180) NOT NULL UNIQUE,
                roles JSON NOT NULL,
                password VARCHAR(255) NOT NULL,
                PRIMARY KEY (id)
            )'
        );
        $this->addSql('CREATE INDEX IDX_voiture_constructor_id ON voiture (constructor_id)');
        $this->addSql('CREATE INDEX IDX_messenger_queue_name ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_messenger_available_at ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_messenger_delivered_at ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE constructeur');
        $this->addSql('DROP TABLE voiture');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP INDEX IDX_voiture_constructor_id');
        $this->addSql('DROP INDEX IDX_messenger_queue_name');
        $this->addSql('DROP INDEX IDX_messenger_available_at');
        $this->addSql('DROP INDEX IDX_messenger_delivered_at');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250516135451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE ingredient (id SERIAL NOT NULL, recette_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, quantity VARCHAR(100) DEFAULT NULL, unit VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6BAF787089312FE9 ON ingredient (recette_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE recette (id SERIAL NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, preparation_time INT NOT NULL, difficulty VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, create_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, update_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_49BB6390F675F31B ON recette (author_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE review (id SERIAL NOT NULL, author_id INT DEFAULT NULL, recette_id INT DEFAULT NULL, rating INT NOT NULL, comment TEXT DEFAULT NULL, create_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_794381C6F675F31B ON review (author_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_794381C689312FE9 ON review (recette_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE step (id SERIAL NOT NULL, recette_id INT DEFAULT NULL, position INT NOT NULL, description TEXT NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_43B9FE3C89312FE9 ON step (recette_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(50) NOT NULL, profile_picture VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.available_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.delivered_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
                BEGIN
                    PERFORM pg_notify('messenger_messages', NEW.queue_name::text);
                    RETURN NEW;
                END;
            $$ LANGUAGE plpgsql;
        SQL);
        $this->addSql(<<<'SQL'
            DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF787089312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recette ADD CONSTRAINT FK_49BB6390F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE review ADD CONSTRAINT FK_794381C6F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE review ADD CONSTRAINT FK_794381C689312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE step ADD CONSTRAINT FK_43B9FE3C89312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient DROP CONSTRAINT FK_6BAF787089312FE9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recette DROP CONSTRAINT FK_49BB6390F675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE review DROP CONSTRAINT FK_794381C6F675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE review DROP CONSTRAINT FK_794381C689312FE9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE step DROP CONSTRAINT FK_43B9FE3C89312FE9
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ingredient
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE recette
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE review
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE step
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "user"
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}

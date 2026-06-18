<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260616142336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE enseignant (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, specialite VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE etudiant (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, filiere VARCHAR(255) NOT NULL, theme_mémoire VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE salle (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, code VARCHAR(255) NOT NULL, capacite INTEGER NOT NULL, localisation VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE soutenance (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATE NOT NULL, heure TIME NOT NULL, etudiant_id INTEGER DEFAULT NULL, president_id INTEGER DEFAULT NULL, rapporteur_id INTEGER DEFAULT NULL, examinateur_id INTEGER DEFAULT NULL, salle_id INTEGER DEFAULT NULL, CONSTRAINT FK_4D59FF6EDDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4D59FF6EB40A33C7 FOREIGN KEY (president_id) REFERENCES enseignant (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4D59FF6E2AF5D182 FOREIGN KEY (rapporteur_id) REFERENCES enseignant (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4D59FF6E9D8D68C0 FOREIGN KEY (examinateur_id) REFERENCES enseignant (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4D59FF6EDC304035 FOREIGN KEY (salle_id) REFERENCES salle (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_4D59FF6EDDEAB1A3 ON soutenance (etudiant_id)');
        $this->addSql('CREATE INDEX IDX_4D59FF6EB40A33C7 ON soutenance (president_id)');
        $this->addSql('CREATE INDEX IDX_4D59FF6E2AF5D182 ON soutenance (rapporteur_id)');
        $this->addSql('CREATE INDEX IDX_4D59FF6E9D8D68C0 ON soutenance (examinateur_id)');
        $this->addSql('CREATE INDEX IDX_4D59FF6EDC304035 ON soutenance (salle_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 ON messenger_messages (queue_name, available_at, delivered_at, id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE enseignant');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('DROP TABLE salle');
        $this->addSql('DROP TABLE soutenance');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

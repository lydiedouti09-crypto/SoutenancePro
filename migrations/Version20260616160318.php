<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260616160318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__etudiant AS SELECT id, nom, prenom, email, filiere, theme_mémoire FROM etudiant');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('CREATE TABLE etudiant (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, filiere VARCHAR(255) NOT NULL, theme_memoire VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO etudiant (id, nom, prenom, email, filiere, theme_memoire) SELECT id, nom, prenom, email, filiere, theme_mémoire FROM __temp__etudiant');
        $this->addSql('DROP TABLE __temp__etudiant');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__etudiant AS SELECT id, nom, prenom, email, filiere, theme_memoire FROM etudiant');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('CREATE TABLE etudiant (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, filiere VARCHAR(255) NOT NULL, theme_mémoire VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO etudiant (id, nom, prenom, email, filiere, theme_mémoire) SELECT id, nom, prenom, email, filiere, theme_memoire FROM __temp__etudiant');
        $this->addSql('DROP TABLE __temp__etudiant');
    }
}

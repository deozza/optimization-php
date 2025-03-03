<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250303150145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE galaxy RENAME COLUMN modele TO modele_id');
        $this->addSql('ALTER TABLE galaxy ADD CONSTRAINT FK_F6BB1376AC14B70A FOREIGN KEY (modele_id) REFERENCES modeles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F6BB1376AC14B70A ON galaxy (modele_id)');
        $this->addSql('ALTER TABLE modeles_files ADD CONSTRAINT FK_9BD3EEA708408C FOREIGN KEY (modeles_id) REFERENCES modeles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE modeles_files ADD CONSTRAINT FK_9BD3EEA93CB796C FOREIGN KEY (directus_files_id) REFERENCES directus_files (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9BD3EEA708408C ON modeles_files (modeles_id)');
        $this->addSql('CREATE INDEX IDX_9BD3EEA93CB796C ON modeles_files (directus_files_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE galaxy DROP CONSTRAINT FK_F6BB1376AC14B70A');
        $this->addSql('DROP INDEX IDX_F6BB1376AC14B70A');
        $this->addSql('ALTER TABLE galaxy ADD modele VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE galaxy DROP modele_id');
        $this->addSql('ALTER TABLE modeles_files DROP CONSTRAINT FK_9BD3EEA708408C');
        $this->addSql('ALTER TABLE modeles_files DROP CONSTRAINT FK_9BD3EEA93CB796C');
        $this->addSql('DROP INDEX IDX_9BD3EEA708408C');
        $this->addSql('DROP INDEX IDX_9BD3EEA93CB796C');
        $this->addSql('ALTER TABLE modeles_files DROP file_id');
    }
}

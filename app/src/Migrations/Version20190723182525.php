<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190723182525 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE blog_posts (id INT NOT NULL, category_id INT DEFAULT NULL, user_id UUID DEFAULT NULL, title VARCHAR(64) NOT NULL, slug VARCHAR(64) NOT NULL, body TEXT NOT NULL, status VARCHAR(16) NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_78B2F932989D9B62 ON blog_posts (slug)');
        $this->addSql('CREATE INDEX IDX_78B2F93212469DE2 ON blog_posts (category_id)');
        $this->addSql('CREATE INDEX IDX_78B2F932A76ED395 ON blog_posts (user_id)');
        $this->addSql('COMMENT ON COLUMN blog_posts.id IS \'(DC2Type:blog_post_id)\'');
        $this->addSql('COMMENT ON COLUMN blog_posts.user_id IS \'(DC2Type:user_user_id)\'');
        $this->addSql('COMMENT ON COLUMN blog_posts.status IS \'(DC2Type:blog_post_status)\'');
        $this->addSql('COMMENT ON COLUMN blog_posts.created IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN blog_posts.updated IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE blog_posts ADD CONSTRAINT FK_78B2F93212469DE2 FOREIGN KEY (category_id) REFERENCES blog_categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE blog_posts ADD CONSTRAINT FK_78B2F932A76ED395 FOREIGN KEY (user_id) REFERENCES user_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE blog_posts');
    }
}

<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20210325220516 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ext_log_entries (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(8) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(191) NOT NULL, version INT NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', username VARCHAR(191) DEFAULT NULL, INDEX log_class_lookup_idx (object_class), INDEX log_date_lookup_idx (logged_at), INDEX log_user_lookup_idx (username), INDEX log_version_lookup_idx (object_id, object_class, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE ext_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX translations_lookup_idx (locale, object_class, foreign_key), UNIQUE INDEX lookup_unique_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE users_user (id INT AUTO_INCREMENT NOT NULL, uuid CHAR(36) NOT NULL, player_name VARCHAR(32) DEFAULT NULL, email VARCHAR(129) DEFAULT NULL, password VARCHAR(60) NOT NULL, role VARCHAR(255) NOT NULL, remember_token VARCHAR(100) DEFAULT NULL, activitycreated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', activityupdated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', activitydeleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', activityis_confirmed TINYINT(1) DEFAULT \'0\' NOT NULL, activityemail_verified TINYINT(1) DEFAULT \'0\' NOT NULL, UNIQUE INDEX UNIQ_421A9847D17F50A6 (uuid), UNIQUE INDEX UNIQ_421A9847E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_email_confirmation_token (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(100) NOT NULL, email VARCHAR(129) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_72A73CE75F37A13B (token), INDEX IDX_72A73CE7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_permission (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_37223D3E989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_user_permission (user_id INT NOT NULL, permission_id INT NOT NULL, INDEX IDX_FC5BABDCA76ED395 (user_id), INDEX IDX_FC5BABDCFED90CCA (permission_id), PRIMARY KEY(user_id, permission_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_user_personal (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, first_name VARCHAR(120) DEFAULT NULL, last_name VARCHAR(120) DEFAULT NULL, patronymic VARCHAR(120) DEFAULT NULL, birthday DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', gender VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_18E28189A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_user_social_account (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, credential TINYINT(1) DEFAULT \'0\' NOT NULL, provider_name VARCHAR(255) NOT NULL, provider_account_id VARCHAR(255) NOT NULL, INDEX IDX_FB568EFAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users_email_confirmation_token ADD CONSTRAINT FK_72A73CE7A76ED395 FOREIGN KEY (user_id) REFERENCES users_user (id)');
        $this->addSql('ALTER TABLE users_user_permission ADD CONSTRAINT FK_FC5BABDCA76ED395 FOREIGN KEY (user_id) REFERENCES users_user (id)');
        $this->addSql('ALTER TABLE users_user_permission ADD CONSTRAINT FK_FC5BABDCFED90CCA FOREIGN KEY (permission_id) REFERENCES users_permission (id)');
        $this->addSql('ALTER TABLE users_user_personal ADD CONSTRAINT FK_18E28189A76ED395 FOREIGN KEY (user_id) REFERENCES users_user (id)');
        $this->addSql('ALTER TABLE users_user_social_account ADD CONSTRAINT FK_FB568EFAA76ED395 FOREIGN KEY (user_id) REFERENCES users_user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users_user_permission DROP FOREIGN KEY FK_FC5BABDCFED90CCA');
        $this->addSql('ALTER TABLE users_email_confirmation_token DROP FOREIGN KEY FK_72A73CE7A76ED395');
        $this->addSql('ALTER TABLE users_user_permission DROP FOREIGN KEY FK_FC5BABDCA76ED395');
        $this->addSql('ALTER TABLE users_user_personal DROP FOREIGN KEY FK_18E28189A76ED395');
        $this->addSql('ALTER TABLE users_user_social_account DROP FOREIGN KEY FK_FB568EFAA76ED395');
        $this->addSql('DROP TABLE ext_log_entries');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP TABLE users_email_confirmation_token');
        $this->addSql('DROP TABLE users_permission');
        $this->addSql('DROP TABLE users_user_permission');
        $this->addSql('DROP TABLE users_user_personal');
        $this->addSql('DROP TABLE users_user_social_account');
        $this->addSql('DROP TABLE users_user');
    }
}

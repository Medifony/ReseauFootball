<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201018135938 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE championnat (id INT AUTO_INCREMENT NOT NULL, payss_id INT DEFAULT NULL, nom VARCHAR(30) NOT NULL, logo VARCHAR(100) NOT NULL, navbar VARCHAR(15) DEFAULT NULL, slug VARCHAR(30) NOT NULL, api_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_AB8C2209666DCEE (payss_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classement (id INT AUTO_INCREMENT NOT NULL, clubs_id INT NOT NULL, place INT NOT NULL, total INT NOT NULL, victoires INT NOT NULL, nuls INT NOT NULL, defaites INT NOT NULL, points INT NOT NULL, buts INT NOT NULL, encaisse INT NOT NULL, UNIQUE INDEX UNIQ_55EE9D6D2FC7F5AF (clubs_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE club (id INT AUTO_INCREMENT NOT NULL, championnats_id INT DEFAULT NULL, nom VARCHAR(150) NOT NULL, logo VARCHAR(100) NOT NULL, navbar VARCHAR(80) DEFAULT NULL, slug VARCHAR(80) NOT NULL, api_id INT DEFAULT NULL, INDEX IDX_B8EE38723F1EECF2 (championnats_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversation (id INT AUTO_INCREMENT NOT NULL, dernier_mess_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_8A8E26E9AE80E3C9 (dernier_mess_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande_ami (id INT AUTO_INCREMENT NOT NULL, user_ajout_id INT NOT NULL, user_rec_id INT NOT NULL, ami_statut TINYINT(1) NOT NULL, envoi_date DATETIME NOT NULL, validation_date DATETIME DEFAULT NULL, INDEX IDX_2A51AD883E5713E (user_ajout_id), INDEX IDX_2A51AD88733BAAA6 (user_rec_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, conversations_id INT NOT NULL, contenu LONGTEXT NOT NULL, cont_date DATETIME NOT NULL, INDEX IDX_B6BD307F67B3B43D (users_id), INDEX IDX_B6BD307FFE142757 (conversations_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participant (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, conversations_id INT NOT NULL, INDEX IDX_D79F6B1167B3B43D (users_id), INDEX IDX_D79F6B11FE142757 (conversations_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pays (id INT AUTO_INCREMENT NOT NULL, nom_fr VARCHAR(100) NOT NULL, nom_en VARCHAR(100) NOT NULL, code INT NOT NULL, alpha2 VARCHAR(5) NOT NULL, alpha3 VARCHAR(5) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rencontre (id INT AUTO_INCREMENT NOT NULL, championnats_id INT NOT NULL, club_dom_id INT NOT NULL, club_ext_id INT NOT NULL, match_date DATETIME NOT NULL, INDEX IDX_460C35ED3F1EECF2 (championnats_id), INDEX IDX_460C35ED4C468305 (club_dom_id), INDEX IDX_460C35ED78CB0327 (club_ext_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, sujets_id INT NOT NULL, commentaire LONGTEXT NOT NULL, rep_date DATETIME NOT NULL, INDEX IDX_5FB6DEC767B3B43D (users_id), INDEX IDX_5FB6DEC750B0AC57 (sujets_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(60) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_user (role_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_332CA4DDD60322AC (role_id), INDEX IDX_332CA4DDA76ED395 (user_id), PRIMARY KEY(role_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE signalement (id INT AUTO_INCREMENT NOT NULL, sujets_id INT DEFAULT NULL, reponses_id INT DEFAULT NULL, users_id INT NOT NULL, raison VARCHAR(255) NOT NULL, etat TINYINT(1) NOT NULL, INDEX IDX_F4B5511450B0AC57 (sujets_id), INDEX IDX_F4B55114E4084792 (reponses_id), INDEX IDX_F4B5511467B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sujet (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, championnats_id INT DEFAULT NULL, clubs_id INT DEFAULT NULL, titre VARCHAR(100) NOT NULL, details LONGTEXT NOT NULL, sujet_date DATETIME NOT NULL, slug VARCHAR(255) NOT NULL, date_modif DATETIME NOT NULL, INDEX IDX_2E13599D67B3B43D (users_id), INDEX IDX_2E13599D3F1EECF2 (championnats_id), INDEX IDX_2E13599D2FC7F5AF (clubs_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, clubs_id INT DEFAULT NULL, payss_id INT DEFAULT NULL, filename VARCHAR(255) DEFAULT NULL, prenom VARCHAR(35) NOT NULL, nom VARCHAR(35) NOT NULL, pseudo VARCHAR(30) NOT NULL, email VARCHAR(100) NOT NULL, hash VARCHAR(100) NOT NULL, slug VARCHAR(255) NOT NULL, date_crea DATETIME NOT NULL, presentation VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_8D93D6492FC7F5AF (clubs_id), INDEX IDX_8D93D6499666DCEE (payss_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE championnat ADD CONSTRAINT FK_AB8C2209666DCEE FOREIGN KEY (payss_id) REFERENCES pays (id)');
        $this->addSql('ALTER TABLE classement ADD CONSTRAINT FK_55EE9D6D2FC7F5AF FOREIGN KEY (clubs_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE club ADD CONSTRAINT FK_B8EE38723F1EECF2 FOREIGN KEY (championnats_id) REFERENCES championnat (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9AE80E3C9 FOREIGN KEY (dernier_mess_id) REFERENCES message (id)');
        $this->addSql('ALTER TABLE demande_ami ADD CONSTRAINT FK_2A51AD883E5713E FOREIGN KEY (user_ajout_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE demande_ami ADD CONSTRAINT FK_2A51AD88733BAAA6 FOREIGN KEY (user_rec_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F67B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FFE142757 FOREIGN KEY (conversations_id) REFERENCES conversation (id)');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B1167B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11FE142757 FOREIGN KEY (conversations_id) REFERENCES conversation (id)');
        $this->addSql('ALTER TABLE rencontre ADD CONSTRAINT FK_460C35ED3F1EECF2 FOREIGN KEY (championnats_id) REFERENCES championnat (id)');
        $this->addSql('ALTER TABLE rencontre ADD CONSTRAINT FK_460C35ED4C468305 FOREIGN KEY (club_dom_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE rencontre ADD CONSTRAINT FK_460C35ED78CB0327 FOREIGN KEY (club_ext_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC767B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC750B0AC57 FOREIGN KEY (sujets_id) REFERENCES sujet (id)');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDD60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE signalement ADD CONSTRAINT FK_F4B5511450B0AC57 FOREIGN KEY (sujets_id) REFERENCES sujet (id)');
        $this->addSql('ALTER TABLE signalement ADD CONSTRAINT FK_F4B55114E4084792 FOREIGN KEY (reponses_id) REFERENCES reponse (id)');
        $this->addSql('ALTER TABLE signalement ADD CONSTRAINT FK_F4B5511467B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sujet ADD CONSTRAINT FK_2E13599D67B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sujet ADD CONSTRAINT FK_2E13599D3F1EECF2 FOREIGN KEY (championnats_id) REFERENCES championnat (id)');
        $this->addSql('ALTER TABLE sujet ADD CONSTRAINT FK_2E13599D2FC7F5AF FOREIGN KEY (clubs_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6492FC7F5AF FOREIGN KEY (clubs_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499666DCEE FOREIGN KEY (payss_id) REFERENCES pays (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE club DROP FOREIGN KEY FK_B8EE38723F1EECF2');
        $this->addSql('ALTER TABLE rencontre DROP FOREIGN KEY FK_460C35ED3F1EECF2');
        $this->addSql('ALTER TABLE sujet DROP FOREIGN KEY FK_2E13599D3F1EECF2');
        $this->addSql('ALTER TABLE classement DROP FOREIGN KEY FK_55EE9D6D2FC7F5AF');
        $this->addSql('ALTER TABLE rencontre DROP FOREIGN KEY FK_460C35ED4C468305');
        $this->addSql('ALTER TABLE rencontre DROP FOREIGN KEY FK_460C35ED78CB0327');
        $this->addSql('ALTER TABLE sujet DROP FOREIGN KEY FK_2E13599D2FC7F5AF');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6492FC7F5AF');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FFE142757');
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B11FE142757');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E9AE80E3C9');
        $this->addSql('ALTER TABLE championnat DROP FOREIGN KEY FK_AB8C2209666DCEE');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499666DCEE');
        $this->addSql('ALTER TABLE signalement DROP FOREIGN KEY FK_F4B55114E4084792');
        $this->addSql('ALTER TABLE role_user DROP FOREIGN KEY FK_332CA4DDD60322AC');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC750B0AC57');
        $this->addSql('ALTER TABLE signalement DROP FOREIGN KEY FK_F4B5511450B0AC57');
        $this->addSql('ALTER TABLE demande_ami DROP FOREIGN KEY FK_2A51AD883E5713E');
        $this->addSql('ALTER TABLE demande_ami DROP FOREIGN KEY FK_2A51AD88733BAAA6');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F67B3B43D');
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B1167B3B43D');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC767B3B43D');
        $this->addSql('ALTER TABLE role_user DROP FOREIGN KEY FK_332CA4DDA76ED395');
        $this->addSql('ALTER TABLE signalement DROP FOREIGN KEY FK_F4B5511467B3B43D');
        $this->addSql('ALTER TABLE sujet DROP FOREIGN KEY FK_2E13599D67B3B43D');
        $this->addSql('DROP TABLE championnat');
        $this->addSql('DROP TABLE classement');
        $this->addSql('DROP TABLE club');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE demande_ami');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE pays');
        $this->addSql('DROP TABLE rencontre');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_user');
        $this->addSql('DROP TABLE signalement');
        $this->addSql('DROP TABLE sujet');
        $this->addSql('DROP TABLE user');
    }
}

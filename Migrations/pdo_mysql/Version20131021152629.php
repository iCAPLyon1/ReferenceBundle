<?php

namespace Icap\ReferenceBundle\Migrations\pdo_mysql;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2013/10/21 03:26:36
 */
class Version20131021152629 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE icap__referencebundle_customfield (
                id INT AUTO_INCREMENT NOT NULL, 
                reference_id INT DEFAULT NULL, 
                fieldKey VARCHAR(255) NOT NULL, 
                fieldValue LONGTEXT NOT NULL, 
                INDEX IDX_BB78E7BA1645DEA9 (reference_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ");
        $this->addSql("
            CREATE TABLE icap__referencebundle_reference (
                id INT AUTO_INCREMENT NOT NULL, 
                referencebank_id INT DEFAULT NULL, 
                title VARCHAR(255) NOT NULL, 
                imageUrl VARCHAR(255) DEFAULT NULL, 
                description LONGTEXT DEFAULT NULL, 
                type VARCHAR(255) NOT NULL, 
                url VARCHAR(255) DEFAULT NULL, 
                iconName VARCHAR(255) NOT NULL, 
                data LONGTEXT DEFAULT NULL COMMENT '(DC2Type:json_array)', 
                INDEX IDX_8A18F724F59AADC6 (referencebank_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ");
        $this->addSql("
            CREATE TABLE icap__referencebundle_referencebank (
                id INT AUTO_INCREMENT NOT NULL, 
                resourceNode_id INT DEFAULT NULL, 
                UNIQUE INDEX UNIQ_C8CD1021B87FAB32 (resourceNode_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ");
        $this->addSql("
            CREATE TABLE icap__referencebundle_referencebankoptions (
                id INT AUTO_INCREMENT NOT NULL, 
                referenceByPage INT NOT NULL, 
                amazonApiKey VARCHAR(255) DEFAULT NULL, 
                amazonSecretKey VARCHAR(255) DEFAULT NULL, 
                amazonAssociateTag VARCHAR(255) DEFAULT NULL, 
                amazonCountry VARCHAR(255) DEFAULT NULL, 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ");
        $this->addSql("
            ALTER TABLE icap__referencebundle_customfield 
            ADD CONSTRAINT FK_BB78E7BA1645DEA9 FOREIGN KEY (reference_id) 
            REFERENCES icap__referencebundle_reference (id)
        ");
        $this->addSql("
            ALTER TABLE icap__referencebundle_reference 
            ADD CONSTRAINT FK_8A18F724F59AADC6 FOREIGN KEY (referencebank_id) 
            REFERENCES icap__referencebundle_referencebank (id)
        ");
        $this->addSql("
            ALTER TABLE icap__referencebundle_referencebank 
            ADD CONSTRAINT FK_C8CD1021B87FAB32 FOREIGN KEY (resourceNode_id) 
            REFERENCES claro_resource_node (id) 
            ON DELETE CASCADE
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE icap__referencebundle_customfield 
            DROP FOREIGN KEY FK_BB78E7BA1645DEA9
        ");
        $this->addSql("
            ALTER TABLE icap__referencebundle_reference 
            DROP FOREIGN KEY FK_8A18F724F59AADC6
        ");
        $this->addSql("
            DROP TABLE icap__referencebundle_customfield
        ");
        $this->addSql("
            DROP TABLE icap__referencebundle_reference
        ");
        $this->addSql("
            DROP TABLE icap__referencebundle_referencebank
        ");
        $this->addSql("
            DROP TABLE icap__referencebundle_referencebankoptions
        ");
    }
}
<?php

namespace Icap\ReferenceBundle\Migrations\pdo_pgsql;

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
                id SERIAL NOT NULL, 
                reference_id INT DEFAULT NULL, 
                fieldKey VARCHAR(255) NOT NULL, 
                fieldValue TEXT NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_BB78E7BA1645DEA9 ON icap__referencebundle_customfield (reference_id)
        ");
        $this->addSql("
            CREATE TABLE icap__referencebundle_reference (
                id SERIAL NOT NULL, 
                referencebank_id INT DEFAULT NULL, 
                title VARCHAR(255) NOT NULL, 
                imageUrl VARCHAR(255) DEFAULT NULL, 
                description TEXT DEFAULT NULL, 
                type VARCHAR(255) NOT NULL, 
                url VARCHAR(255) DEFAULT NULL, 
                iconName VARCHAR(255) NOT NULL, 
                data TEXT DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_8A18F724F59AADC6 ON icap__referencebundle_reference (referencebank_id)
        ");
        $this->addSql("
            COMMENT ON COLUMN icap__referencebundle_reference.data IS '(DC2Type:json_array)'
        ");
        $this->addSql("
            CREATE TABLE icap__referencebundle_referencebank (
                id SERIAL NOT NULL, 
                resourceNode_id INT DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_C8CD1021B87FAB32 ON icap__referencebundle_referencebank (resourceNode_id)
        ");
        $this->addSql("
            CREATE TABLE icap__referencebundle_referencebankoptions (
                id SERIAL NOT NULL, 
                referenceByPage INT NOT NULL, 
                amazonApiKey VARCHAR(255) DEFAULT NULL, 
                amazonSecretKey VARCHAR(255) DEFAULT NULL, 
                amazonAssociateTag VARCHAR(255) DEFAULT NULL, 
                amazonCountry VARCHAR(255) DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            ALTER TABLE icap__referencebundle_customfield 
            ADD CONSTRAINT FK_BB78E7BA1645DEA9 FOREIGN KEY (reference_id) 
            REFERENCES icap__referencebundle_reference (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ");
        $this->addSql("
            ALTER TABLE icap__referencebundle_reference 
            ADD CONSTRAINT FK_8A18F724F59AADC6 FOREIGN KEY (referencebank_id) 
            REFERENCES icap__referencebundle_referencebank (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ");
        $this->addSql("
            ALTER TABLE icap__referencebundle_referencebank 
            ADD CONSTRAINT FK_C8CD1021B87FAB32 FOREIGN KEY (resourceNode_id) 
            REFERENCES claro_resource_node (id) 
            ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE icap__referencebundle_customfield 
            DROP CONSTRAINT FK_BB78E7BA1645DEA9
        ");
        $this->addSql("
            ALTER TABLE icap__referencebundle_reference 
            DROP CONSTRAINT FK_8A18F724F59AADC6
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
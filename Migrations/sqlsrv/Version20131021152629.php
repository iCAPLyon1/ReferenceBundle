<?php

namespace Icap\ReferenceBundle\Migrations\sqlsrv;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2013/10/21 03:26:37
 */
class Version20131021152629 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE icap__referencebundle_customfield (
                id INT IDENTITY NOT NULL, 
                reference_id INT, 
                fieldKey NVARCHAR(255) NOT NULL, 
                fieldValue VARCHAR(MAX) NOT NULL, 
                PRIMARY KEY (id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_BB78E7BA1645DEA9 ON icap__referencebundle_customfield (reference_id)
        ");
        $this->addSql("
            CREATE TABLE icap__referencebundle_reference (
                id INT IDENTITY NOT NULL, 
                referencebank_id INT, 
                title NVARCHAR(255) NOT NULL, 
                imageUrl NVARCHAR(255), 
                description VARCHAR(MAX), 
                type NVARCHAR(255) NOT NULL, 
                url NVARCHAR(255), 
                iconName NVARCHAR(255) NOT NULL, 
                data VARCHAR(MAX), 
                PRIMARY KEY (id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_8A18F724F59AADC6 ON icap__referencebundle_reference (referencebank_id)
        ");
        $this->addSql("
            CREATE TABLE icap__referencebundle_referencebank (
                id INT IDENTITY NOT NULL, 
                resourceNode_id INT, 
                PRIMARY KEY (id)
            )
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_C8CD1021B87FAB32 ON icap__referencebundle_referencebank (resourceNode_id) 
            WHERE resourceNode_id IS NOT NULL
        ");
        $this->addSql("
            CREATE TABLE icap__referencebundle_referencebankoptions (
                id INT IDENTITY NOT NULL, 
                referenceByPage INT NOT NULL, 
                amazonApiKey NVARCHAR(255), 
                amazonSecretKey NVARCHAR(255), 
                amazonAssociateTag NVARCHAR(255), 
                amazonCountry NVARCHAR(255), 
                PRIMARY KEY (id)
            )
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
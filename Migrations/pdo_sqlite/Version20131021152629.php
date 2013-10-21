<?php

namespace Icap\ReferenceBundle\Migrations\pdo_sqlite;

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
                id INTEGER NOT NULL, 
                reference_id INTEGER DEFAULT NULL, 
                fieldKey VARCHAR(255) NOT NULL, 
                fieldValue CLOB NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_BB78E7BA1645DEA9 ON icap__referencebundle_customfield (reference_id)
        ");
        $this->addSql("
            CREATE TABLE icap__referencebundle_reference (
                id INTEGER NOT NULL, 
                referencebank_id INTEGER DEFAULT NULL, 
                title VARCHAR(255) NOT NULL, 
                imageUrl VARCHAR(255) DEFAULT NULL, 
                description CLOB DEFAULT NULL, 
                type VARCHAR(255) NOT NULL, 
                url VARCHAR(255) DEFAULT NULL, 
                iconName VARCHAR(255) NOT NULL, 
                data CLOB DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_8A18F724F59AADC6 ON icap__referencebundle_reference (referencebank_id)
        ");
        $this->addSql("
            CREATE TABLE icap__referencebundle_referencebank (
                id INTEGER NOT NULL, 
                resourceNode_id INTEGER DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_C8CD1021B87FAB32 ON icap__referencebundle_referencebank (resourceNode_id)
        ");
        $this->addSql("
            CREATE TABLE icap__referencebundle_referencebankoptions (
                id INTEGER NOT NULL, 
                referenceByPage INTEGER NOT NULL, 
                amazonApiKey VARCHAR(255) DEFAULT NULL, 
                amazonSecretKey VARCHAR(255) DEFAULT NULL, 
                amazonAssociateTag VARCHAR(255) DEFAULT NULL, 
                amazonCountry VARCHAR(255) DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ");
    }

    public function down(Schema $schema)
    {
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
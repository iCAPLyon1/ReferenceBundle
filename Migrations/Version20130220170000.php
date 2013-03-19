<?php

namespace ICAP\ReferenceBundle\Migrations;

use Claroline\CoreBundle\Library\Installation\BundleMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20130220170000 extends BundleMigration
{
    /**
     * Will be fired at the plugin installation
     * @param Doctrine\DBAL\Schema\Schema
     */
    public function up(Schema $schema)
    {
        $this->createReferenceBankTable($schema);
        $this->createReferenceTable($schema);
        $this->createCustomFieldTable($schema);
        $this->createReferenceBankOptionsTable($schema);
    }

    /**
     * Will be fired at the plugin uninstallation
     * @param Doctrine\DBAL\Schema\Schema
     */
    public function down(Schema $schema)
    {
         $schema->dropTable('icap__referencebundle_referencebank');
         $schema->dropTable('icap__referencebundle_reference');
         $schema->dropTable('icap__referencebundle_customfield');
         $schema->dropTable('icap__referencebundle_referencebankoptions');
    }

    /**
     * Creates the icap__referencebundle_referencebank table
     * @param Doctrine\DBAL\Schema\Schema
     */
    public function createReferenceBankTable(Schema $schema)
    {
        $table = $schema->createTable('icap__referencebundle_referencebank');

        $this->addId($table);
        $this->storeTable($table);
        $table->addForeignKeyConstraint(
            $schema->getTable('claro_resource'), array('id'), array('id'), array("onDelete" => "CASCADE")
        );
    }
 
    /**
     * Creates the icap__referencebundle_reference table
     * @param Doctrine\DBAL\Schema\Schema
     */
    public function createReferenceTable(Schema $schema)
    {
        $table = $schema->createTable('icap__referencebundle_reference');

        $this->addId($table);
        $table->addColumn('title', 'string', array('length' => 255));
        $table->addColumn('imageUrl', 'string', array('length' => 255, 'notnull' => false));
        $table->addColumn('description', 'text', array('notnull' => false));
        $table->addColumn('type', 'string', array('length' => 255));
        $table->addColumn('url', 'string', array('length' => 255, 'notnull' => false));
        $table->addColumn('iconName', 'string', array('length' => 255));
        $table->addColumn('data', 'json_array', array('notnull' => false));

        $table->addColumn('referencebank_id', 'integer');

        $this->storeTable($table);
        $table->addForeignKeyConstraint(
            $schema->getTable('icap__referencebundle_referencebank'), array('referencebank_id'), array('id'), array("onDelete" => "CASCADE")
        );
    }

    /**
     * Creates the icap__referencebundle_reference table
     * @param Doctrine\DBAL\Schema\Schema
     */
    public function createCustomFieldTable(Schema $schema)
    {
        $table = $schema->createTable('icap__referencebundle_customfield');

        $this->addId($table);
        $table->addColumn('fieldKey', 'string', array('length' => 255));
        $table->addColumn('fieldValue', 'text');

        $table->addColumn('reference_id', 'integer');

        $this->storeTable($table);
        $table->addForeignKeyConstraint(
            $schema->getTable('icap__referencebundle_reference'), array('reference_id'), array('id'), array("onDelete" => "CASCADE")
        );
    }

    /**
     * Creates the icap__referencebundle_referencebankoptions table
     * @param Doctrine\DBAL\Schema\Schema
     */
    public function createReferenceBankOptionsTable(Schema $schema)
    {
        $table = $schema->createTable('icap__referencebundle_referencebankoptions');

        $this->addId($table);
        $table->addColumn('referenceByPage', 'integer');
        $table->addColumn('amazonApiKey', 'string', array('length' => 255, 'notnull' => false));
        $table->addColumn('amazonSecretKey', 'string', array('length' => 255, 'notnull' => false));
        $table->addColumn('amazonAssociateTag', 'string', array('length' => 255, 'notnull' => false));
        $table->addColumn('amazonCountry', 'string', array('length' => 255, 'notnull' => false));
        $this->storeTable($table);
    }
}
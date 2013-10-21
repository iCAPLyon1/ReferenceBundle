<?php

namespace Icap\ReferenceBundle\Migrations\oci8;

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
                id NUMBER(10) NOT NULL, 
                reference_id NUMBER(10) DEFAULT NULL, 
                fieldKey VARCHAR2(255) NOT NULL, 
                fieldValue CLOB NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            DECLARE constraints_Count NUMBER; BEGIN 
            SELECT COUNT(CONSTRAINT_NAME) INTO constraints_Count 
            FROM USER_CONSTRAINTS 
            WHERE TABLE_NAME = 'ICAP__REFERENCEBUNDLE_CUSTOMFIELD' 
            AND CONSTRAINT_TYPE = 'P'; IF constraints_Count = 0 
            OR constraints_Count = '' THEN EXECUTE IMMEDIATE 'ALTER TABLE ICAP__REFERENCEBUNDLE_CUSTOMFIELD ADD CONSTRAINT ICAP__REFERENCEBUNDLE_CUSTOMFIELD_AI_PK PRIMARY KEY (ID)'; END IF; END;
        ");
        $this->addSql("
            CREATE SEQUENCE ICAP__REFERENCEBUNDLE_CUSTOMFIELD_ID_SEQ START WITH 1 MINVALUE 1 INCREMENT BY 1
        ");
        $this->addSql("
            CREATE TRIGGER ICAP__REFERENCEBUNDLE_CUSTOMFIELD_AI_PK BEFORE INSERT ON ICAP__REFERENCEBUNDLE_CUSTOMFIELD FOR EACH ROW DECLARE last_Sequence NUMBER; last_InsertID NUMBER; BEGIN 
            SELECT ICAP__REFERENCEBUNDLE_CUSTOMFIELD_ID_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; IF (
                : NEW.ID IS NULL 
                OR : NEW.ID = 0
            ) THEN 
            SELECT ICAP__REFERENCEBUNDLE_CUSTOMFIELD_ID_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; ELSE 
            SELECT NVL(Last_Number, 0) INTO last_Sequence 
            FROM User_Sequences 
            WHERE Sequence_Name = 'ICAP__REFERENCEBUNDLE_CUSTOMFIELD_ID_SEQ'; 
            SELECT : NEW.ID INTO last_InsertID 
            FROM DUAL; WHILE (last_InsertID > last_Sequence) LOOP 
            SELECT ICAP__REFERENCEBUNDLE_CUSTOMFIELD_ID_SEQ.NEXTVAL INTO last_Sequence 
            FROM DUAL; END LOOP; END IF; END;
        ");
        $this->addSql("
            CREATE INDEX IDX_BB78E7BA1645DEA9 ON icap__referencebundle_customfield (reference_id)
        ");
        $this->addSql("
            CREATE TABLE icap__referencebundle_reference (
                id NUMBER(10) NOT NULL, 
                referencebank_id NUMBER(10) DEFAULT NULL, 
                title VARCHAR2(255) NOT NULL, 
                imageUrl VARCHAR2(255) DEFAULT NULL, 
                description CLOB DEFAULT NULL, 
                type VARCHAR2(255) NOT NULL, 
                url VARCHAR2(255) DEFAULT NULL, 
                iconName VARCHAR2(255) NOT NULL, 
                data CLOB DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            DECLARE constraints_Count NUMBER; BEGIN 
            SELECT COUNT(CONSTRAINT_NAME) INTO constraints_Count 
            FROM USER_CONSTRAINTS 
            WHERE TABLE_NAME = 'ICAP__REFERENCEBUNDLE_REFERENCE' 
            AND CONSTRAINT_TYPE = 'P'; IF constraints_Count = 0 
            OR constraints_Count = '' THEN EXECUTE IMMEDIATE 'ALTER TABLE ICAP__REFERENCEBUNDLE_REFERENCE ADD CONSTRAINT ICAP__REFERENCEBUNDLE_REFERENCE_AI_PK PRIMARY KEY (ID)'; END IF; END;
        ");
        $this->addSql("
            CREATE SEQUENCE ICAP__REFERENCEBUNDLE_REFERENCE_ID_SEQ START WITH 1 MINVALUE 1 INCREMENT BY 1
        ");
        $this->addSql("
            CREATE TRIGGER ICAP__REFERENCEBUNDLE_REFERENCE_AI_PK BEFORE INSERT ON ICAP__REFERENCEBUNDLE_REFERENCE FOR EACH ROW DECLARE last_Sequence NUMBER; last_InsertID NUMBER; BEGIN 
            SELECT ICAP__REFERENCEBUNDLE_REFERENCE_ID_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; IF (
                : NEW.ID IS NULL 
                OR : NEW.ID = 0
            ) THEN 
            SELECT ICAP__REFERENCEBUNDLE_REFERENCE_ID_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; ELSE 
            SELECT NVL(Last_Number, 0) INTO last_Sequence 
            FROM User_Sequences 
            WHERE Sequence_Name = 'ICAP__REFERENCEBUNDLE_REFERENCE_ID_SEQ'; 
            SELECT : NEW.ID INTO last_InsertID 
            FROM DUAL; WHILE (last_InsertID > last_Sequence) LOOP 
            SELECT ICAP__REFERENCEBUNDLE_REFERENCE_ID_SEQ.NEXTVAL INTO last_Sequence 
            FROM DUAL; END LOOP; END IF; END;
        ");
        $this->addSql("
            CREATE INDEX IDX_8A18F724F59AADC6 ON icap__referencebundle_reference (referencebank_id)
        ");
        $this->addSql("
            COMMENT ON COLUMN icap__referencebundle_reference.data IS '(DC2Type:json_array)'
        ");
        $this->addSql("
            CREATE TABLE icap__referencebundle_referencebank (
                id NUMBER(10) NOT NULL, 
                resourceNode_id NUMBER(10) DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            DECLARE constraints_Count NUMBER; BEGIN 
            SELECT COUNT(CONSTRAINT_NAME) INTO constraints_Count 
            FROM USER_CONSTRAINTS 
            WHERE TABLE_NAME = 'ICAP__REFERENCEBUNDLE_REFERENCEBANK' 
            AND CONSTRAINT_TYPE = 'P'; IF constraints_Count = 0 
            OR constraints_Count = '' THEN EXECUTE IMMEDIATE 'ALTER TABLE ICAP__REFERENCEBUNDLE_REFERENCEBANK ADD CONSTRAINT ICAP__REFERENCEBUNDLE_REFERENCEBANK_AI_PK PRIMARY KEY (ID)'; END IF; END;
        ");
        $this->addSql("
            CREATE SEQUENCE ICAP__REFERENCEBUNDLE_REFERENCEBANK_ID_SEQ START WITH 1 MINVALUE 1 INCREMENT BY 1
        ");
        $this->addSql("
            CREATE TRIGGER ICAP__REFERENCEBUNDLE_REFERENCEBANK_AI_PK BEFORE INSERT ON ICAP__REFERENCEBUNDLE_REFERENCEBANK FOR EACH ROW DECLARE last_Sequence NUMBER; last_InsertID NUMBER; BEGIN 
            SELECT ICAP__REFERENCEBUNDLE_REFERENCEBANK_ID_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; IF (
                : NEW.ID IS NULL 
                OR : NEW.ID = 0
            ) THEN 
            SELECT ICAP__REFERENCEBUNDLE_REFERENCEBANK_ID_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; ELSE 
            SELECT NVL(Last_Number, 0) INTO last_Sequence 
            FROM User_Sequences 
            WHERE Sequence_Name = 'ICAP__REFERENCEBUNDLE_REFERENCEBANK_ID_SEQ'; 
            SELECT : NEW.ID INTO last_InsertID 
            FROM DUAL; WHILE (last_InsertID > last_Sequence) LOOP 
            SELECT ICAP__REFERENCEBUNDLE_REFERENCEBANK_ID_SEQ.NEXTVAL INTO last_Sequence 
            FROM DUAL; END LOOP; END IF; END;
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_C8CD1021B87FAB32 ON icap__referencebundle_referencebank (resourceNode_id)
        ");
        $this->addSql("
            CREATE TABLE icap__referencebundle_referencebankoptions (
                id NUMBER(10) NOT NULL, 
                referenceByPage NUMBER(10) NOT NULL, 
                amazonApiKey VARCHAR2(255) DEFAULT NULL, 
                amazonSecretKey VARCHAR2(255) DEFAULT NULL, 
                amazonAssociateTag VARCHAR2(255) DEFAULT NULL, 
                amazonCountry VARCHAR2(255) DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            DECLARE constraints_Count NUMBER; BEGIN 
            SELECT COUNT(CONSTRAINT_NAME) INTO constraints_Count 
            FROM USER_CONSTRAINTS 
            WHERE TABLE_NAME = 'ICAP__REFERENCEBUNDLE_REFERENCEBANKOPTIONS' 
            AND CONSTRAINT_TYPE = 'P'; IF constraints_Count = 0 
            OR constraints_Count = '' THEN EXECUTE IMMEDIATE 'ALTER TABLE ICAP__REFERENCEBUNDLE_REFERENCEBANKOPTIONS ADD CONSTRAINT ICAP__REFERENCEBUNDLE_REFERENCEBANKOPTIONS_AI_PK PRIMARY KEY (ID)'; END IF; END;
        ");
        $this->addSql("
            CREATE SEQUENCE ICAP__REFERENCEBUNDLE_REFERENCEBANKOPTIONS_ID_SEQ START WITH 1 MINVALUE 1 INCREMENT BY 1
        ");
        $this->addSql("
            CREATE TRIGGER ICAP__REFERENCEBUNDLE_REFERENCEBANKOPTIONS_AI_PK BEFORE INSERT ON ICAP__REFERENCEBUNDLE_REFERENCEBANKOPTIONS FOR EACH ROW DECLARE last_Sequence NUMBER; last_InsertID NUMBER; BEGIN 
            SELECT ICAP__REFERENCEBUNDLE_REFERENCEBANKOPTIONS_ID_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; IF (
                : NEW.ID IS NULL 
                OR : NEW.ID = 0
            ) THEN 
            SELECT ICAP__REFERENCEBUNDLE_REFERENCEBANKOPTIONS_ID_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; ELSE 
            SELECT NVL(Last_Number, 0) INTO last_Sequence 
            FROM User_Sequences 
            WHERE Sequence_Name = 'ICAP__REFERENCEBUNDLE_REFERENCEBANKOPTIONS_ID_SEQ'; 
            SELECT : NEW.ID INTO last_InsertID 
            FROM DUAL; WHILE (last_InsertID > last_Sequence) LOOP 
            SELECT ICAP__REFERENCEBUNDLE_REFERENCEBANKOPTIONS_ID_SEQ.NEXTVAL INTO last_Sequence 
            FROM DUAL; END LOOP; END IF; END;
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
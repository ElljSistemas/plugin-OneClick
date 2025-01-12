<?php
$app = MapasCulturais\App::i();
$em = $app->em;
$conn = $em->getConnection();

return [
    'create table settings' => function () use ($app, $em, $conn) {
        $conn->executeQuery("CREATE SEQUENCE settings_id_seq INCREMENT BY 1 MINVALUE 1 START 1");
        $conn->executeQuery("CREATE SEQUENCE settings_meta_id_seq INCREMENT BY 1 MINVALUE 1 START 1");

        $conn->executeQuery("
            CREATE TABLE settings (
                id INT NOT NULL, 
                status SMALLINT NOT NULL, 
                metadata JSON DEFAULT '{}' NOT NULL, 
                create_timestamp TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                update_timestamp TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
                subsite_id SMALLINT NULL, 
            PRIMARY KEY(id))");

        $conn->executeQuery("CREATE TABLE settings_meta (
                object_id integer NOT NULL,
                key character varying(32) NOT NULL,
                value text,
                id integer NOT NULL);");

        $conn->executeQuery(" INSERT INTO settings ( id, status, metadata, create_timestamp, update_timestamp, subsite_id) VALUES (nextval('settings_id_seq'::regclass), 1, '{}',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP, null)");
    }
];

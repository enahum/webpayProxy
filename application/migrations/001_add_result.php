<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_result extends CI_Migration
{

    public function up()
    {
        $this->dbforge->add_field(array(
            'Id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'date' => array(
                'type' => 'DATETIME',
                'null' => FALSE,
                'default' => 'now()'
            ),
            'sessionId' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => FALSE
            ),
            'initRequest' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'initResponse' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'resultRequest' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'resultResponse' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'akwRequest' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'akwResponse' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'token_ws' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE,
            ),
            'request' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'response' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'paid' => array(
                'type' => 'BOOLEAN',
                'default' => 'FALSE'
            ),
            'acknowledge' => array(
                'type' => 'BOOLEAN',
                'default' => 'FALSE'
            )
        ));

        $this->dbforge->add_key('Id', TRUE);
        $this->dbforge->create_table('transactions');

        $this->db->query('CREATE INDEX session_index ON transactions USING btree ("sessionId" ASC NULLS LAST);');
        $this->db->query('CREATE UNIQUE INDEX token_index ON transactions USING btree (token_ws ASC NULLS LAST);');
    }

    public function down()
    {
        $this->dbforge->drop_table('transactions');
    }
}
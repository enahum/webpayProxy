<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transactionmodel extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function is_paid_by_session($id){
        $query = $this->db->get_where('transactions', array('sessionId' => $id,
            'paid' => 'true',
            'acknowledge' => 'true'));

        if($query->num_rows() > 0){
            return true;
        } else {
            return false;
        }
    }

    public function is_paid_by_token($token){
        $query = $this->db->get_where('transactions', array('token_ws' => $token,
            'paid' => 'true',
            'acknowledge' => 'true'));

        if($query->num_rows() > 0){
            return true;
        } else {
            return false;
        }
    }

    public function get_by_session($id) {
        $rows = array();
        $query = $this->db->get_where('transactions', array('sessionId' => $id));
        if($query->num_rows() > 0){
            foreach ($query->result() as $row) {
                $rows[] = $row;
            }
            return $rows;
        } else {
            return null;
        }
    }

    public function get_by_token($token) {
        $query = $this->db->get_where('transactions', array('token_ws' => $token));
        if($query->num_rows() > 0){
          return $query->row();
        } else {
            return null;
        }
    }

    public function log_init_request($id, $initRequest) {
        $data = array(
            'date' => date("Y-m-d H:i:s", time()),
            'sessionId' => $id,
            'initRequest' => $initRequest
        );

        $this->db->insert('transactions', $data);
    }

    public function log_init_response($id, $initResponse) {
        $data = array(
            'initResponse' => $initResponse
        );

        $this->db->where('sessionId', $id);
        $this->db->update('transactions', $data);
    }

    public function insert($id, $token, $request)
    {
        $data = array(
            'token_ws' => $token,
            'request' => $request
        );

        $this->db->where('sessionId', $id);
        $this->db->update('transactions', $data);

    }

    public function log_result_request($token, $resultRequest) {
        $data = array(
            'resultRequest' => $resultRequest
        );

        $this->db->where('token_ws', $token);
        $this->db->update('transactions', $data);
    }

    public function log_result_response($token, $resultResponse) {
        $data = array(
            'resultResponse' => $resultResponse
        );

        $this->db->where('token_ws', $token);
        $this->db->update('transactions', $data);
    }

    public function log_akw_request($token, $akwRequest) {
        $data = array(
            'akwRequest' => $akwRequest
        );

        $this->db->where('token_ws', $token);
        $this->db->update('transactions', $data);
    }

    public function log_awk_response($token, $akwResponse) {
        $data = array(
            'akwResponse' => $akwResponse
        );

        $this->db->where('token_ws', $token);
        $this->db->update('transactions', $data);
    }


    public function set_response($token, $response) {
        $data = array(
            'response' => $response
        );

        $this->db->where('token_ws', $token);
        $this->db->update('transactions', $data);
    }

    public function set_acknowledge ($token) {
        $data = array(
            'acknowledge' => 'true'
        );

        $this->db->where('token_ws', $token);
        $this->db->update('transactions', $data);
    }

    public function set_paid ($token) {
        $data = array(
            'paid' => 'true'
        );

        $this->db->where('token_ws', $token);
        $this->db->update('transactions', $data);
    }

}
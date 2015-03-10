<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log extends CI_Controller {

    public function session($id)
    {
        $this->load->model('transactionmodel');

        echo json_encode($this->transactionmodel->get_by_session($id));
        return;
    }

    public function token($token)
    {
        $this->load->model('transactionmodel');

        echo json_encode($this->transactionmodel->get_by_token($token));
        return;
    }

}

/* End of file log.php */
/* Location: ./application/controllers/log.php */
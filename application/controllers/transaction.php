<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaction extends CI_Controller {

    public function init()
    {
        $this->load->helper('url');
        $this->load->library('webpayservice');
        $this->load->model('transactionmodel');
        $post = $this->input->post(NULL, TRUE);

        if($this->transactionmodel->is_paid_by_session($post['sessionId'])) {
            $data = array(
                'error' => 'Esta orden de compra ya fue pagada.',
                'sessionId' => $post['sessionId'],
                'url' => $post['finalUrl']
            );

            $this->load->view('init_transaction', $data);

        }
        else {
            $wsInitTransactionInput = new wsInitTransactionInput();
            $wsTransactionDetail = new wsTransactionDetail();

            $wsInitTransactionInput->wSTransactionType = 'TR_NORMAL_WS';
            $wsInitTransactionInput->sessionId = $post['sessionId'];
            $wsInitTransactionInput->returnURL = $this->config->base_url('transaction/process');
            $wsInitTransactionInput->finalURL = $post['finalUrl'];
            $wsTransactionDetail->commerceCode = COMMERCE_ID;
            $wsTransactionDetail->buyOrder = $post['buyOrder'];
            $wsTransactionDetail->amount = $post['amount'];
            $wsInitTransactionInput->transactionDetails = $wsTransactionDetail;

            $id = $this->transactionmodel->log_init_request($post['sessionId'], json_encode($wsInitTransactionInput));

            $initTransactionResponse = $this->webpayservice->initTransaction(
                array("wsInitTransactionInput" => $wsInitTransactionInput)
            );
            $xmlResponse = $this->webpayservice->soapClient->__getLastResponse();
            $params = array('xmlSoap' => $xmlResponse, 'certServerPath' => SERVER_CERT);
            $this->load->library('SoapValidation', $params);

            $validationResult = $this->soapvalidation->getValidationResult();
            /*Invocar sólo sí $validationResult es TRUE*/

            if ($validationResult) {
                $wsInitTransactionOutput = $initTransactionResponse->return;
                $this->transactionmodel->log_init_response($id, json_encode($initTransactionResponse));
                $request = array(
                    'transaction_type' => 'TR_NORMAL_WS',
                    'sessionId' => $post['sessionId'],
                    'returnUrl' => $this->config->base_url('transaction/process'),
                    'finalUrl' => $post['finalUrl'],
                    'transactionDetail' => array(
                        'commerceCode' => COMMERCE_ID,
                        'buyOrder' => $post['buyOrder'],
                        'amount' => $post['amount']
                    )
                );

                $this->transactionmodel->insert($id, $wsInitTransactionOutput->token, json_encode($request));

                $data = array(
                    'url' => $wsInitTransactionOutput->url,
                    'token' => $wsInitTransactionOutput->token
                );

                $this->load->view('init_transaction', $data);

            }
            else {
                $data = array(
                    'error' => 'Transacción no autorizada por Transbank',
                    'sessionId' => $post['sessionId'],
                    'url' => $post['finalUrl']
                );

                $this->load->view('init_transaction', $data);
            }
        }
    }

    public function process()
    {
        $data = array('token' => $this->input->post('token_ws'));
        $this->load->view('process_transaction', $data);
    }

    public function result() {
        $this->load->model('transactionmodel');
        $this->load->library('webpayservice');
        header('Content-Type: application/json');

        $token = $this->input->post('token_ws');

        try {
            $getTransactionResult = new getTransactionResult();
            $getTransactionResult->tokenInput = $token;
            $this->transactionmodel->log_result_request($token, json_encode($getTransactionResult));
            $getTransactionResultResponse = $this->webpayservice->getTransactionResult(
                $getTransactionResult);
            $xmlResponse = $this->webpayservice->soapClient->__getLastResponse();
            $params = array('xmlSoap' => $xmlResponse, 'certServerPath' => SERVER_CERT);
            $this->load->library('SoapValidation', $params);

            $validationResult = $this->soapvalidation->getValidationResult();
            if($validationResult) {
                $this->transactionmodel->log_result_response($token, json_encode($getTransactionResultResponse));
                $transactionResultOutput = $getTransactionResultResponse->return;
                $json = json_encode($transactionResultOutput);
                $this->transactionmodel->set_response($token, $json);
                if($transactionResultOutput->detailOutput->responseCode == '0') {
                    $this->transactionmodel->set_paid($token);
                }
                echo $json;
                return;
            } else {
                echo $this->errorHandler($token, 'Transacción no autorizada por Transbank');
                return;
            }
        }
        catch (Exception $e){
            echo $this->errorHandler($token, $e->getMessage());
            return;
        }
    }

    public function complete($token) {
        header('Content-Type: application/json');
        $this->load->model('transactionmodel');
        $this->load->library('webpayservice');

        $result = $this->transactionmodel->get_by_token($token);

        if($result != null) {
            if($result->acknowledge == 'f') {
                if(!$this->transactionmodel->is_paid_by_session($result->sessionId)) {
                    $acknowledgeTransaction = new acknowledgeTransaction();
                    $acknowledgeTransaction->tokenInput = $token;
                    $this->transactionmodel->log_akw_request($token, json_encode($acknowledgeTransaction));
                    $acknowledgeTransactionResponse = $this->webpayservice->acknowledgeTransaction(
                        $acknowledgeTransaction);
                    $xmlResponse = $this->webpayservice->soapClient->__getLastResponse();
                    $params = array('xmlSoap' => $xmlResponse, 'certServerPath' => SERVER_CERT);
                    $this->load->library('SoapValidation', $params);

                    $validationResult = $this->soapvalidation->getValidationResult();
                    if ($validationResult) {
                        $this->transactionmodel->log_awk_response($token, json_encode($acknowledgeTransactionResponse));
                        $this->transactionmodel->set_acknowledge($token);
                    }
                    else {
                        echo $this->errorHandler($token, 'Transacción no autorizada por Transbank');
                        return;
                    }
                }
                else {
                    echo $this->errorHandler($token, 'Esta orden de compra ya fue pagada.');
                    return;
                }
            }

            echo $result->response;
        }
        else {
            echo json_encode(null);
        }

    }

    private function errorHandler($token, $msg) {

        $this->load->model('transactionmodel');
        $row = $this->transactionmodel->get_by_token($token);
        $request = json_decode($row->request, true);
        $data = array(
            'error' => $msg,
            'sessionId' => $row->sessionId,
            'finalUrl' => $request['finalUrl']
        );
        return json_encode($data);
    }
}
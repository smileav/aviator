<?php
class ControllerCheckoutSmsValidator extends Controller {

    public function validateNumber() {
        $step = 1;

        if (isset($this->request->get['tel'])) {
            $this->session->data['customer']['telephone'] = $this->request->get['tel'];

            $this->log->write('$this->request->get[tel]: ' . $this->request->get['tel']);

            $telephone = $this->clearTelephoneMask($this->request->get['tel']);

            if (utf8_strlen($telephone) == 12 && substr($telephone, 0, 3) == '380' && substr($telephone, 3, 1) > 0) {
                $step = 2;
            } else {
                $step = 1;
            }

            if ($step == 2) {
                $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "sms_validator` WHERE `telephone` = '" . $this->db->escape($telephone) . "'");

                if (!$query->num_rows) {
                    $step = 2;
                } else {
                    $step = 3;
                }
            }
        }

        $this->response->setOutput($step);
    }

    public function sendSMS() {
        $json = [];

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $code = $this->session->data['sms_code'] = rand(1000, 9999);

            $telephone = $this->clearTelephoneMask($this->session->data['customer']['telephone']);

            $this->log->write('Send SMS to: ' . $telephone);
            $this->log->write('Code: ' . $code);

            $client = new SoapClient('http://turbosms.in.ua/api/wsdl.html');

            $auth = [
                'login' => 'BlackinWhite',
                'password' => '7460079'
            ];

            $client->Auth($auth);

            $sms = [
                'sender'        => 'AVIATOR',
                'destination'   => '+' . $telephone,
                'text'          => $code
            ];

            $this->log->write('SMS_Array:');
            $this->log->write($sms);

            $result = $client->SendSMS($sms);

            $this->log->write('SMS result:');
            $this->log->write($result);

            if (!empty($result->SendSMSResult->ResultArray[1])) {
                $this->log->write('SMS SUCCESS!');
                $json['success']    = 1;
            } else {
                $this->log->write('SMS ERROR!');
                $json['error']      = 1;
            }

            $json['sms_code'] = $code;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function validateCode() {
        $json = [];

        if ($this->request->server['REQUEST_METHOD'] == 'GET' && !empty($this->request->get['code']) && isset($this->session->data['sms_code']) && isset($this->session->data['customer']['telephone'])) {
            if ($this->request->get['code'] == $this->session->data['sms_code']) {
                $telephone = $this->clearTelephoneMask($this->session->data['customer']['telephone']);

                $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "sms_validator` WHERE `telephone` = '" . $this->db->escape($telephone) . "'");

                if (!$query->num_rows) {
                    $this->db->query("INSERT INTO `" . DB_PREFIX . "sms_validator` SET `telephone` = '" . $this->db->escape($telephone) . "', status = '1', date_added = NOW()");
                }

                $json['success'] = 1;
            } else {
                $json['success'] = 0;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function clearTelephoneMask($telephone) {
        return preg_replace(['/\+/', '/-/', '/_/', '/\ /', '/\(/', '/\)/', '/X/', '/x/'], '', trim($telephone));
    }
}

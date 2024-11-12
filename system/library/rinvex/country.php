<?php
namespace Rinvex;

class Country {
    private $iso_code_2;
    private $resources;
    private $data;
    private $log;

    public function __construct() {
        $this->log = new \Log('error.log');
        if (!defined('DIR_RESOURCES_DATA'))
        define('DIR_RESOURCES_DATA',            DIR_SYSTEM . 'library/rinvex/resources/data/');
        if (!defined('DIR_RESOURCES_FLAGS'))
        define('DIR_RESOURCES_FLAGS',           DIR_IMAGE  . 'country_flags/');

        // define('DIR_RESOURCES_DIVISIONS',       DIR_SYSTEM . 'library/rinvex/resources/divisions/');
        // define('DIR_RESOURCES_TRANSLATIONS',    DIR_SYSTEM . 'library/rinvex/resources/translations/');
    }

    public function getData($iso_code_2, $telephone = '', $validate = false) {
        $this->data['valid']    = true;
        $this->iso_code_2       = strtolower($iso_code_2);
        $this->resources        = $this->getResourcesData();

        if ($this->resources) {
            $this->getCallingCode();
            $this->getNationalNumberLengths();
            $this->getFlag();

            if ($this->data['valid'] && $validate) {
                $this->data['telephone'] = $this->validateTelephone($telephone);
            }

            $this->data['iso_code_2'] = $this->iso_code_2;
        }

        return $this->data;
    }

    public function getCallingCode() {
        if (isset($this->resources['calling_code'][0])) {
            $this->data['calling_code'] = $this->resources['calling_code'][0];
        } else {
            $this->data['valid'] = false;
        }
    }

    public function getNationalNumberLengths() {
        if ($this->data['valid'] && !empty($this->resources['national_number_lengths'])) {
            $this->data['number_lengths'] = end($this->resources['national_number_lengths']);

            $this->data['number_lengths_mask'] = '';

            for ($i = 1; $i <= $this->data['number_lengths']; $i++) {
                $this->data['number_lengths_mask'] .= '9';
            }

        } else {
            $this->data['valid'] = false;
        }
    }

    public function getFlag() {
        if ($this->data['valid']) {
            $flag_file = DIR_RESOURCES_FLAGS . $this->iso_code_2 . '.svg';

            if (file_exists($flag_file)) {
                $this->data['flag'] = 'image/country_flags/' . $this->iso_code_2 . '.svg';
            } else {
                $this->data['valid'] = false;
            }
        }
    }

    public function validateTelephone($telephone) {
        $telephone = $this->clearTelephoneMask($telephone);

        if (substr($telephone, 0, strlen($this->data['calling_code'])) != $this->data['calling_code']) {
            $this->log->write(print_r('=1',1));
            $this->log->write(print_r($telephone,1));
            $this->log->write(print_r($this->data['calling_code'],1));
            $this->log->write(print_r(substr($telephone, 0, strlen($this->data['calling_code'])),1));
            $this->data['valid'] = false;
        }

        if (substr($telephone, strlen($this->data['calling_code']), 1) == 0) {
            $this->data['valid'] = false;
        }

        if (strlen(substr($telephone, strlen($this->data['calling_code']), $this->data['number_lengths'])) != $this->data['number_lengths']) {
            $this->data['valid'] = false;
        }

        return $telephone;
    }

    public function getResourcesData($key = 'dialling') {
        $data = [];

        $json_file = DIR_RESOURCES_DATA . $this->iso_code_2 . '.json';

        if (file_exists($json_file)) {
            $json = file_get_contents($json_file);
            $data = json_decode($json,true);

            if ($key && isset($data[$key])) {
                $data = $data[$key];
            }
        }

        return $data;
    }

    public function clearTelephoneMask($telephone) {
        return preg_replace(['/\+/', '/-/', '/_/', '/\ /', '/\(/', '/\)/', '/X/', '/x/'], '', trim($telephone));
    }
}

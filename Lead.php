<?php

require_once __DIR__.'/Request.php';
require_once __DIR__.'/ZohoConfig.php';

class Lead {

  const INSERT_RECORDS_URL = 'https://crm.zoho.eu/crm/private/xml/Leads/insertRecords';
  const SEARCH_RECORDS_URL = 'https://crm.zoho.eu/crm/private/json/Leads/searchRecords';

  private $data;

  public function __construct() {
    $this->data = array();
  }

  public function addData($key, $value) {
    $this->data[$key] = $value;
    return $this;
  }

  public function getData() {
    return $this->data;
  }

  public function create() {
    $request = new Request();
    $request->setUrl(self::INSERT_RECORDS_URL)
      ->addParam('newFormat', 1)
      ->addParam('authtoken', ZohoConfig::AUTHTOKEN)
      ->addParam('scope', 'crmapi')
      ->addParam('xmlData', $this->getXmlDataLead());
    $result = $request->post();
    return $result;
  }

  public function isCreated() {
    $req = new Request();
    $res = $req->setUrl(self::SEARCH_RECORDS_URL)
      ->addParam('authtoken', ZohoConfig::AUTHTOKEN)
      ->addParam('scope', 'crmapi')
      ->addParam('criteria', '(phone:'.$this->data['Phone'].')')
      ->post();
   
    $responseArray = json_decode($res, true);
    if ($responseArray['response']['nodata']['code'] == '4422') {
      return false;
    } else {
      $leadId = $responseArray['response']['result']['Leads']['row']['FL'][0]['content'];
      $this->addData('LeadID', $leadId);
      return true;
    }
  }

  private function getXmlDataLead() {
    $result = '<Leads><row no="1">';
    foreach ($this->data as $key => $value) {
      $result .= '<FL val="'.$key.'">'.$value.'</FL>';
    }
    $result .= '</row></Leads>';
    return $result;
  }

}
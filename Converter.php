<?php

require_once __DIR__.'/Lead.php';
require_once __DIR__.'/Request.php';
require_once __DIR__.'/ZohoConfig.php';

class Converter {

  const CONVERT_LEAD_URL = 'https://crm.zoho.eu/crm/private/json/Leads/convertLead';

  public function convertLeadToDeal(Lead $lead) {
    $leadId = $lead->getData()['LeadID'];
    $xmlData = $this->getConvertXmlData($leadId);

    $request = new Request();
    $request->setUrl(self::CONVERT_LEAD_URL)
      ->addParam('authtoken', ZohoConfig::AUTHTOKEN)
      ->addParam('scope', 'crmapi')
      ->addParam('leadId', $leadId)
      ->addParam('xmlData', $xmlData);
    $result = $request->post();
    return $result;
  }

  private function getConvertXmlData($leadId) {
    $currentDate = date("m/d/Y");
    $xmlString = '<Potentials>
      <row no="1">
      <option val="createPotential">true</option>
      <option val="assignTo">sample@zoho.com</option>
      <option val="notifyLeadOwner">true</option>
      <option val="notifyNewEntityOwner">true</option>
      </row>
      <row no="2">';
    $xmlString .= '<FL val="Potential Name">deal-'.$leadId.'</FL>';
    $xmlString .= '<FL val="Closing Date">'.$currentDate.'</FL>';
    $xmlString .= '<FL val="Potential Stage">Closed Won</FL>';
    $xmlString .= '</row></Potentials>';
    
    return $xmlString;
  }
}
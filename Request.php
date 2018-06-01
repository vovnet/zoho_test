<?php

class Request {

  private $url;
  private $params;

  public function __construct() {
    $this->params = array();
  }

  public function setUrl($url) {
    $this->url = $url;
    return $this;
  }

  public function addParam($key, $value) {
    $this->params[$key] = $value;
    return $this;
  }

  public function post() {
    $stringParams = $this->getParamsAsString();

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $stringParams);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $result = curl_exec($ch);

    curl_close($ch);

    return $result;
  }

  private function getParamsAsString() {
    $result = '';
    foreach ($this->params as $key => $value) {
      $result .= $key.'='.$value.'&';
    }
    substr($result, 0, -1);
    return $result;
  }

}
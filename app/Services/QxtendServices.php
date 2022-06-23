<?php

namespace App\Services;

use App\Models\Master\Qxwsa;
use App\Models\Transaksi\SalesOrderDetail;
use App\Models\Transaksi\SuratJalanDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class QxtendServices
{
  private function httpHeader($req)
  {
    return array(
      'Content-type: text/xml;charset="utf-8"',
      'Accept: text/xml',
      'Cache-Control: no-cache',
      'Pragma: no-cache',
      'SOAPAction: ""',        // jika tidak pakai SOAPAction, isinya harus ada tanda petik 2 --> ""
      'Content-length: ' . strlen(preg_replace("/\s+/", " ", $req))
    );
  }

  public function qxSOMaintenance($data, $sonbr)
  {
    $qxwsa = Qxwsa::firstOrFail();
    if (is_null($qxwsa->qx_url)) {
      return 'nourl';
    }
    // Var Qxtend
    $qxUrl          = $qxwsa->qx_url;

    $timeout        = 0;

    // XML Qextend
    $qdocHead = '<?xml version="1.0" encoding="UTF-8"?>
                    <soapenv:Envelope xmlns="urn:schemas-qad-com:xml-services"
                      xmlns:qcom="urn:schemas-qad-com:xml-services:common"
                      xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:wsa="http://www.w3.org/2005/08/addressing">
                      <soapenv:Header>
                        <wsa:Action/>
                        <wsa:To>urn:services-qad-com:QADHSS</wsa:To>
                        <wsa:MessageID>urn:services-qad-com::QADHSS</wsa:MessageID>
                        <wsa:ReferenceParameters>
                          <qcom:suppressResponseDetail>false</qcom:suppressResponseDetail>
                        </wsa:ReferenceParameters>
                        <wsa:ReplyTo>
                          <wsa:Address>urn:services-qad-com:</wsa:Address>
                        </wsa:ReplyTo>
                      </soapenv:Header>
                      <soapenv:Body>
                        <maintainSalesOrder>
                          <qcom:dsSessionContext>
                            <qcom:ttContext>
                              <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                              <qcom:propertyName>domain</qcom:propertyName>
                              <qcom:propertyValue>'.$data['domains'].'</qcom:propertyValue>
                            </qcom:ttContext>
                            <qcom:ttContext>
                              <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                              <qcom:propertyName>scopeTransaction</qcom:propertyName>
                              <qcom:propertyValue>true</qcom:propertyValue>
                            </qcom:ttContext>
                            <qcom:ttContext>
                              <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                              <qcom:propertyName>version</qcom:propertyName>
                              <qcom:propertyValue>ERP3_2</qcom:propertyValue>
                            </qcom:ttContext>
                            <qcom:ttContext>
                              <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                              <qcom:propertyName>mnemonicsRaw</qcom:propertyName>
                              <qcom:propertyValue>false</qcom:propertyValue>
                            </qcom:ttContext>
                            <qcom:ttContext>
                              <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                              <qcom:propertyName>username</qcom:propertyName>
                              <qcom:propertyValue>mfg</qcom:propertyValue>
                            </qcom:ttContext>
                            <qcom:ttContext>
                              <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                              <qcom:propertyName>password</qcom:propertyName>
                              <qcom:propertyValue></qcom:propertyValue>
                            </qcom:ttContext>
                            <qcom:ttContext>
                              <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                              <qcom:propertyName>action</qcom:propertyName>
                              <qcom:propertyValue/>
                            </qcom:ttContext>
                            <qcom:ttContext>
                              <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                              <qcom:propertyName>entity</qcom:propertyName>
                              <qcom:propertyValue/>
                            </qcom:ttContext>
                            <qcom:ttContext>
                              <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                              <qcom:propertyName>email</qcom:propertyName>
                              <qcom:propertyValue/>
                            </qcom:ttContext>
                            <qcom:ttContext>
                              <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                              <qcom:propertyName>emailLevel</qcom:propertyName>
                              <qcom:propertyValue/>
                            </qcom:ttContext>
                          </qcom:dsSessionContext>';

    $qdocbody = '<dsSalesOrder>
                    <salesOrder>
                        <soNbr>' . $sonbr . '</soNbr>
                        <soCust>' . $data['customer'] . '</soCust>
                        <soShip>' . $data['shipto'] . '</soShip>
                        <soOrdDate>' . Carbon::now()->toDateString() . '</soOrdDate>
                        <soDueDate>' . $data['duedate'] . '</soDueDate>
                        <soDetailAll>true</soDetailAll>';

                foreach ($data['operation'] as $key => $datas) {
                  $qdocbody .=    '<salesOrderDetail>' .
                    '<line>' . $data['line'][$key] . '</line>' .
                    '<sodPart>' . $data['part'][$key] . '</sodPart>' .
                    '<sodQtyOrd>' . $data['qtyord'][$key] . '</sodQtyOrd>' .
                    '<sodQtyAll>' . $data['qtyord'][$key] . '</sodQtyAll>' .                          
                    '</salesOrderDetail>';
                }

    $qdocbody .=   '</salesOrder>
                                        </dsSalesOrder>';

    $qdocfoot = '</maintainSalesOrder>
                    </soapenv:Body>
                 </soapenv:Envelope>';

    $qdocRequest = $qdocHead . $qdocbody . $qdocfoot;

    $curlOptions = array(
      CURLOPT_URL => $qxUrl,
      CURLOPT_CONNECTTIMEOUT => $timeout,        // in seconds, 0 = unlimited / wait indefinitely.
      CURLOPT_TIMEOUT => $timeout + 120, // The maximum number of seconds to allow cURL functions to execute. must be greater than CURLOPT_CONNECTTIMEOUT
      CURLOPT_HTTPHEADER => $this->httpHeader($qdocRequest),
      CURLOPT_POSTFIELDS => preg_replace("/\s+/", " ", $qdocRequest),
      CURLOPT_POST => true,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_SSL_VERIFYHOST => false
    );

    $getInfo = '';
    $httpCode = 0;
    $curlErrno = 0;
    $curlError = '';


    $qdocResponse = '';

    $curl = curl_init();
    if ($curl) {
      curl_setopt_array($curl, $curlOptions);
      $qdocResponse = curl_exec($curl);           // sending qdocRequest here, the result is qdocResponse.
      //
      $curlErrno = curl_errno($curl);
      $curlError = curl_error($curl);
      $first = true;
      foreach (curl_getinfo($curl) as $key => $value) {
        if (gettype($value) != 'array') {
          if (!$first) $getInfo .= ", ";
          $getInfo = $getInfo . $key . '=>' . $value;
          $first = false;
          if ($key == 'http_code') $httpCode = $value;
        }
      }
      curl_close($curl);
    }

    if (is_bool($qdocResponse)) {
      return false;
    }
    // dd($qdocResponse, $qdocRequest);

    $xmlResp = simplexml_load_string($qdocResponse);

    $xmlResp->registerXPathNamespace('ns1', 'urn:schemas-qad-com:xml-services');
    $qdocResult = (string) $xmlResp->xpath('//ns1:result')[0];

    if ($qdocResult == "success" or $qdocResult == "warning") {
      return [$qdocResult, $qdocResponse];
    } else {
      return [$qdocResult, $qdocResponse];
    }
  }

  public function qxSOShip($data)
  {
    $qxwsa = Qxwsa::firstOrFail();
    if (is_null($qxwsa->qx_url)) {
      return 'nourl';
    }
    // Var Qxtend
    $qxUrl          = $qxwsa->qx_url;

    $timeout        = 0;

    // XML Qextend
    $qdocHead = '<?xml version="1.0" encoding="UTF-8"?>
                    <soapenv:Envelope xmlns="urn:schemas-qad-com:xml-services"
                  xmlns:qcom="urn:schemas-qad-com:xml-services:common"
                  xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:wsa="http://www.w3.org/2005/08/addressing">
                  <soapenv:Header>
                    <wsa:Action/>
                    <wsa:To>urn:services-qad-com:QADHSS</wsa:To>
                    <wsa:MessageID>urn:services-qad-com::QADHSS</wsa:MessageID>
                    <wsa:ReferenceParameters>
                      <qcom:suppressResponseDetail>true</qcom:suppressResponseDetail>
                    </wsa:ReferenceParameters>
                    <wsa:ReplyTo>
                      <wsa:Address>urn:services-qad-com:</wsa:Address>
                    </wsa:ReplyTo>
                  </soapenv:Header>
                  <soapenv:Body>
                    <shipSalesOrder>
                        <qcom:dsSessionContext>
                            <qcom:ttContext>
                                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                                <qcom:propertyName>domain</qcom:propertyName>
                                <qcom:propertyValue>'.Session::get('domain').'</qcom:propertyValue>
                            </qcom:ttContext>
                            <qcom:ttContext>
                                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                                <qcom:propertyName>scopeTransaction</qcom:propertyName>
                                <qcom:propertyValue>true</qcom:propertyValue>
                            </qcom:ttContext>
                            <qcom:ttContext>
                                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                                <qcom:propertyName>version</qcom:propertyName>
                                <qcom:propertyValue>ERP3_2</qcom:propertyValue>
                            </qcom:ttContext>
                            <qcom:ttContext>
                                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                                <qcom:propertyName>mnemonicsRaw</qcom:propertyName>
                                <qcom:propertyValue>false</qcom:propertyValue>
                            </qcom:ttContext>
                            <qcom:ttContext>
                                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                                <qcom:propertyName>username</qcom:propertyName>
                                <qcom:propertyValue>mfg</qcom:propertyValue>
                            </qcom:ttContext>
                            <qcom:ttContext>
                                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                                <qcom:propertyName>password</qcom:propertyName>
                                <qcom:propertyValue></qcom:propertyValue>
                            </qcom:ttContext>
                            <qcom:ttContext>
                                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                                <qcom:propertyName>action</qcom:propertyName>
                                <qcom:propertyValue/>
                            </qcom:ttContext>
                            <qcom:ttContext>
                                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                                <qcom:propertyName>entity</qcom:propertyName>
                                <qcom:propertyValue/>
                            </qcom:ttContext>
                            <qcom:ttContext>
                                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                                <qcom:propertyName>email</qcom:propertyName>
                            <qcom:propertyValue/>
                            </qcom:ttContext>
                            <qcom:ttContext>
                                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                                <qcom:propertyName>emailLevel</qcom:propertyName>
                                <qcom:propertyValue/>
                            </qcom:ttContext>
                        </qcom:dsSessionContext>';

    $qdocbody = '<dsSalesOrderShipment>
                  <SalesOrderShipment>
                      <soNbr>'.$data['sonbr'].'</soNbr>
                      <effDate>'.$data['effdate'].'</effDate>
                      <document>'.$data['remark'].'</document>'
                      ;
                      foreach($data['iddetail'] as $key => $idDetail){
                          $datadetail = SuratJalanDetail::findOrFail($idDetail); 
                          $qdocbody.= '
                          <lineDetail>
                                  <line>'.$datadetail->sjd_line.'</line>
                                  <lotserialQty>'.$data['qtyakui'][$key].'</lotserialQty>
                                  <pickLogic>false</pickLogic>    
                                  <yn>true</yn>
                                  <yn1>true</yn1>        
                          </lineDetail>';
                      }

    $qdocfoot = '</SalesOrderShipment> 
                  </dsSalesOrderShipment>
                  </shipSalesOrder>
                  </soapenv:Body>
                  </soapenv:Envelope>';

    $qdocRequest = $qdocHead . $qdocbody . $qdocfoot;

    $curlOptions = array(
      CURLOPT_URL => $qxUrl,
      CURLOPT_CONNECTTIMEOUT => $timeout,        // in seconds, 0 = unlimited / wait indefinitely.
      CURLOPT_TIMEOUT => $timeout + 120, // The maximum number of seconds to allow cURL functions to execute. must be greater than CURLOPT_CONNECTTIMEOUT
      CURLOPT_HTTPHEADER => $this->httpHeader($qdocRequest),
      CURLOPT_POSTFIELDS => preg_replace("/\s+/", " ", $qdocRequest),
      CURLOPT_POST => true,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_SSL_VERIFYHOST => false
    );

    $getInfo = '';
    $httpCode = 0;
    $curlErrno = 0;
    $curlError = '';


    $qdocResponse = '';

    $curl = curl_init();
    if ($curl) {
      curl_setopt_array($curl, $curlOptions);
      $qdocResponse = curl_exec($curl);           // sending qdocRequest here, the result is qdocResponse.
      //
      $curlErrno = curl_errno($curl);
      $curlError = curl_error($curl);
      $first = true;
      foreach (curl_getinfo($curl) as $key => $value) {
        if (gettype($value) != 'array') {
          if (!$first) $getInfo .= ", ";
          $getInfo = $getInfo . $key . '=>' . $value;
          $first = false;
          if ($key == 'http_code') $httpCode = $value;
        }
      }
      curl_close($curl);
    }

    if (is_bool($qdocResponse)) {
      return false;
    }
    
    $xmlResp = simplexml_load_string($qdocResponse);

    $xmlResp->registerXPathNamespace('ns1', 'urn:schemas-qad-com:xml-services');
    $qdocResult = (string) $xmlResp->xpath('//ns1:result')[0];

    if ($qdocResult == "success" or $qdocResult == "warning") {
      return [$qdocResult, $qdocResponse];
    } else {
      return [$qdocResult, $qdocResponse];
    }
  }
}

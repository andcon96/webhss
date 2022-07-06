<?php

namespace App\Services;

use App\Models\Master\Customer;
use App\Models\Master\CustomerShipTo;
use App\Models\Master\Item;
use App\Models\Master\ItemConversion;
use App\Models\Master\Qxwsa;
use App\Models\Master\UM;
use App\Models\RFPMaster;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class WSAServices
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

    public function wsaitem()
    {
        $wsa = Qxwsa::first();

        $qxUrl = $wsa->wsas_url;
        $qxReceiver = '';
        $qxSuppRes = 'false';
        $qxScopeTrx = '';
        $qdocName = '';
        $qdocVersion = '';
        $dsName = '';
        $timeout = 0;
        $domain = Session::get('domain');

        $qdocRequest =
            '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">' .
            '<Body>' .
            '<HRD_item_mstr xmlns="' . $wsa->wsas_path . '">' .
            '<inpdomain>' . $domain . '</inpdomain>' .
            '</HRD_item_mstr>' .
            '</Body>' .
            '</Envelope>';

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
            $curlErrno    = curl_errno($curl);
            $curlError    = curl_error($curl);
            $first        = true;

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

        $xmlResp = simplexml_load_string($qdocResponse);

        $xmlResp->registerXPathNamespace('ns1', $wsa->wsas_path);
        $dataloop    = $xmlResp->xpath('//ns1:tempRow');
        $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];
        
        if($qdocResult == 'true'){
            DB::beginTransaction();
            try{
                foreach($dataloop as $datas){
                    $item = Item::updateOrCreate([
                        'item_part' => $datas->t_part,
                        'item_domain' => $datas->t_domain
                    ]);
                    
                    $item->item_desc = $datas->t_desc;
                    $item->item_um = $datas->t_um;
                    $item->item_promo = $datas->t_promo;
                    $item->save();
                }
                
                DB::commit();
                return true;
            }catch(Exception $e){
                DB::rollBack();
                return false;
            }
        }else{
            return false;
        }
    }

    public function wsacust()
    {
        $wsa = Qxwsa::first();

        $qxUrl = $wsa->wsas_url;
        $qxReceiver = '';
        $qxSuppRes = 'false';
        $qxScopeTrx = '';
        $qdocName = '';
        $qdocVersion = '';
        $dsName = '';
        $timeout = 0;
        $domain = Session::get('domain');

        $qdocRequest =
            '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
            <Body>
            <HRD_cust_mstr xmlns="'.$wsa->wsas_path.'">
            <inpdomain>'.$domain.'</inpdomain>
            </HRD_cust_mstr>
            </Body>
            </Envelope>';

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
            $curlErrno    = curl_errno($curl);
            $curlError    = curl_error($curl);
            $first        = true;

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

        $xmlResp = simplexml_load_string($qdocResponse);

        $xmlResp->registerXPathNamespace('ns1', $wsa->wsas_path);
        
        $dataloop    = $xmlResp->xpath('//ns1:tempRow');
        
        $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];
        // dd($qdocResponse,$dataloop);
        if($qdocResult == 'true'){
            DB::beginTransaction();
            try{

                foreach($dataloop as $datas){
                    $cust = Customer::updateOrCreate([
                        'cust_code' => $datas->t_cmaddr,
                        'cust_domain' => $domain
                    ]);
                    $cust->cust_desc = $datas->t_cmname;
                    $cust->cust_alt_desc = $datas->t_cmname;
                    $cust->cust_site = $datas->t_cmsite;
                    $cust->cust_alamat = $datas->t_addr1.' '.$datas->t_addr2.' '.$datas->t_addr3;
                    $cust->save();
                }
                DB::commit();
                return true;
            }catch(Exception $e){
                DB::rollBack();
                return false;
            }
        }else{
            return false;
        }
    }

    public function wsacustship()
    {
        $wsa = Qxwsa::first();

        $qxUrl = $wsa->wsas_url;
        $qxReceiver = '';
        $qxSuppRes = 'false';
        $qxScopeTrx = '';
        $qdocName = '';
        $qdocVersion = '';
        $dsName = '';
        $timeout = 0;
        $domain = Session::get('domain');

        $qdocRequest =
            '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
            <Body>
                <HRD_cust_shipto xmlns="'.$wsa->wsas_path.'">
                    <inpdomain>'.$domain.'</inpdomain>
                </HRD_cust_shipto>
            </Body>
        </Envelope>';
        // dd($qdocRequest);
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
            $curlErrno    = curl_errno($curl);
            $curlError    = curl_error($curl);
            $first        = true;

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

        $xmlResp = simplexml_load_string($qdocResponse);

        $xmlResp->registerXPathNamespace('ns1', $wsa->wsas_path);
        
        $dataloop    = $xmlResp->xpath('//ns1:tempRow');
        
        $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];
        
        if($qdocResult == 'true'){
            DB::beginTransaction();
            try{
                
                foreach($dataloop as $datas){
                // dd($datas->domain,$datas->t_)    
                     CustomerShipTo::updateOrCreate([
                        'cs_domain' => $datas->t_domain,
                        'cs_cust_code' => $datas->t_custcode,
                        'cs_shipto' => $datas->t_shipto,

                    ],[                     
                        'cs_shipto_name' => $datas->t_custname,
                        'cs_address' => $datas->t_shiptoaddr
                    ]);
                    

                }

                DB::commit();
                return true;
            }catch(Exception $e){
                DB::rollBack();
                return false;
            }
        }else{
            return false;
        }
    }
    // public function wsatruck()
    // {
    //     $wsa = Qxwsa::first();

    //     $qxUrl = $wsa->wsas_url;
    //     $qxReceiver = '';
    //     $qxSuppRes = 'false';
    //     $qxScopeTrx = '';
    //     $qdocName = '';
    //     $qdocVersion = '';
    //     $dsName = '';
    //     $timeout = 0;
    //     $domain = Session::get('domain');

    //     $qdocRequest =
    //         '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
    //         <Body>
    //         <HRD_shipto_mstr xmlns="'.$wsa->wsas_path.'">
    //         <inpdomain>'.$domain.'</inpdomain>
    //         </HRD_shipto_mstr>
    //         </Body>
    //         </Envelope>';

    //     $curlOptions = array(
    //         CURLOPT_URL => $qxUrl,
    //         CURLOPT_CONNECTTIMEOUT => $timeout,        // in seconds, 0 = unlimited / wait indefinitely.
    //         CURLOPT_TIMEOUT => $timeout + 120, // The maximum number of seconds to allow cURL functions to execute. must be greater than CURLOPT_CONNECTTIMEOUT
    //         CURLOPT_HTTPHEADER => $this->httpHeader($qdocRequest),
    //         CURLOPT_POSTFIELDS => preg_replace("/\s+/", " ", $qdocRequest),
    //         CURLOPT_POST => true,
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_SSL_VERIFYPEER => false,
    //         CURLOPT_SSL_VERIFYHOST => false
    //     );

    //     $getInfo = '';
    //     $httpCode = 0;
    //     $curlErrno = 0;
    //     $curlError = '';
    //     $qdocResponse = '';

    //     $curl = curl_init();
    //     if ($curl) {
    //         curl_setopt_array($curl, $curlOptions);
    //         $qdocResponse = curl_exec($curl);           // sending qdocRequest here, the result is qdocResponse.
    //         $curlErrno    = curl_errno($curl);
    //         $curlError    = curl_error($curl);
    //         $first        = true;

    //         foreach (curl_getinfo($curl) as $key => $value) {
    //             if (gettype($value) != 'array') {
    //                 if (!$first) $getInfo .= ", ";
    //                 $getInfo = $getInfo . $key . '=>' . $value;
    //                 $first = false;
    //                 if ($key == 'http_code') $httpCode = $value;
    //             }
    //         }
    //         curl_close($curl);
    //     }

    //     $xmlResp = simplexml_load_string($qdocResponse);

    //     $xmlResp->registerXPathNamespace('ns1', $wsa->wsas_path);
        
    //     $dataloop    = $xmlResp->xpath('//ns1:tempRow');
        
    //     $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];
    //     // dd($qdocResponse,$dataloop);
    //     if($qdocResult == 'true'){
    //         DB::beginTransaction();
    //         try{

    //             foreach($dataloop as $datas){
    //                 $cust = CustomerShipTo::updateOrCreate([
    //                     'cust_code' => $datas->t_custcode,
    //                     'cust_domain' => $datas->t_domain,
    //                     'cust_shipto' => $datas->t_shipto
    //                 ]);

    //                 $cust->cust_shipto_name = $datas->t_shiptoname;
    //                 $cust->cust_address = $datas->t_shiptoaddr;
    //                 $cust->save();
    //             }
    //             DB::commit();
    //             return true;
    //         }catch(Exception $e){
    //             DB::rollBack();
    //             return false;
    //         }
    //     }else{
    //         return false;
    //     }
    // }
    
}

<?php

namespace App\Services;

use App\Models\Master\Customer;
use App\Models\Master\CustomerShipTo;
use App\Models\Master\Item;
use App\Models\Master\Qxwsa;
use App\Models\Transaksi\SalesOrderMstr;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
        $domain = "ASAHSST";

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
        if(is_bool($qdocResponse)){
            return false;
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
        $domain = 'ASAHSST';

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
        if(is_bool($qdocResponse)){
            return false;
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
        $domain = 'ASAHSST';

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
        if(is_bool($qdocResponse)){
            return false;
        }
        $xmlResp = simplexml_load_string($qdocResponse);

        $xmlResp->registerXPathNamespace('ns1', $wsa->wsas_path);
        
        $dataloop    = $xmlResp->xpath('//ns1:tempRow');
        
        $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];
        
        if($qdocResult == 'true'){
            DB::beginTransaction();
            try{
                
                foreach($dataloop as $datas){

                     CustomerShipTo::updateOrCreate([
                        
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

    public function wsawocheckloc($nopol,$part)
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
        $domain = "ASAHSST";

        $qdocRequest =
            '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
            <Body>
                <HRD_wo_checkloc xmlns="'.$wsa->wsas_path.'">
                    <inpdomain>'.$domain.'</inpdomain>
                    <lotserial>'.$nopol.'</lotserial>
                    <inldpart>'.$part.'</inldpart>
                </HRD_wo_checkloc>
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
        if(is_bool($qdocResponse)){
            return false;
        }

        $xmlResp = simplexml_load_string($qdocResponse);
        
        $xmlResp->registerXPathNamespace('ns1', $wsa->wsas_path);
        
        $dataloop    = $xmlResp->xpath('//ns1:tempRow');
        if(empty($dataloop)){
            return false;
        }
        $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];
        
        if($qdocResult == 'true'){
            $status = '';
                foreach($dataloop as $datas){
                    if($datas->t_location == 'POOL2'){
                        
                        return 'nodata';
                    }

                }
               
                return true;
            
        }else{
            return false;
        }
    }

    public function wsacheckinvoice($domain, $invoicenbr)
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

        // $invoicenbr = Voucher + '/' + Daybook + Year
        // $voucher = substr($invoicenbr,0,9);
        // $daybook = substr($invoicenbr,10,strlen($invoicenbr) - 9 - 4 - 1); // 9 Voucher 4 Year 1 offset
        // $year = substr($invoicenbr, -4);

        // $invoicenbr = Year + '/' + Daybook + Voucher
        $year = substr($invoicenbr,0,4);
        $voucher = intval(substr($invoicenbr,-9));
        $daybook = substr($invoicenbr,5,strlen($invoicenbr) - 9 - 4 - 1);


        $qdocRequest =
            '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
                <Body>
                    <HRD_check_invoice xmlns="'.$wsa->wsas_path.'">
                        <inpdomain>'.$domain.'</inpdomain>
                        <inpvoucher>'.$voucher.'</inpvoucher>
                        <inpdaybook>'.$daybook.'</inpdaybook>
                        <inppostyear>'.$year.'</inppostyear>
                    </HRD_check_invoice>
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
        if(is_bool($qdocResponse)){
            return false;
        }
        $xmlResp = simplexml_load_string($qdocResponse);
        $xmlResp->registerXPathNamespace('ns1', $wsa->wsas_path);
        $dataloop    = $xmlResp->xpath('//ns1:tempRow');
        if(empty($dataloop)){
            return false;
        }
        

        $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];
        // dd($qdocResponse);
        if($qdocResult == 'true'){
            $harga = '';
            $duedate = '';
            $sonbr = '';
            $cust = '';
            $custcode = '';
            foreach($dataloop as $datas){
                
                $harga = $datas->t_harga;
                $duedate = (string)$datas->t_duedate;
                $sonbr = (string)$datas->t_sonbr;
                $cust = (string)$datas->t_custdesc;
                $custcode = (string)$datas->t_custcode;
            }
               
            return [$harga, $duedate, $sonbr, $cust, $custcode];
        }else{
            return false;
        }
    }

    // WSA Invoice Web
    public function wsainvoice($data)
    {
        $wsa = Qxwsa::first();

        $qxUrl = $wsa->wsas_url;
        $timeout = 0;

        // $sonbr = $data->getSalesOrder['so_nbr'];
        $output = [];

        foreach($data->getDetail as $datas){
            // dd($datas);
            $qdocRequest =
            '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
                <Body>
                    <HRD_detail_invoice xmlns="'.$wsa->wsas_path.'">
                        <inpdomain>'.$datas->id_domain.'</inpdomain>
                        <inpsonbr></inpsonbr>
                        <inpinvoicenbr>'.$datas->id_nbr.'</inpinvoicenbr>
                    </HRD_detail_invoice>
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

            if(is_bool($qdocResponse)){
                return false;
            }

            $xmlResp = simplexml_load_string($qdocResponse);
            $xmlResp->registerXPathNamespace('ns1', $wsa->wsas_path);
            $dataloop    = $xmlResp->xpath('//ns1:tempRow');
            if(empty($dataloop)){
                return false;
            }
            
            $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];
            
            if($qdocResult == 'true'){
                
                foreach($dataloop as $dataloops){   
                    $so = SalesOrderMstr::with('getShipTo','getShipFrom')->where('so_nbr',(string)$dataloops->t_sonbr)->first();
                    
                    $output[] = [
                        't_part'   => (string)$dataloops->t_part,
                        't_invnbr' => (string)$dataloops->t_invnbr,
                        't_qtyinv' => (string)$dataloops->t_qtyinv,
                        't_harga'  => (string)$dataloops->t_harga,
                        't_sonbr'  => (string)$dataloops->t_sonbr,
                        't_shipto' => $so->getShipTo->cs_shipto ?? '',
                        't_shiptodesc' => $so->getShipTo->cs_shipto_name ?? '',
                        't_shipfrom' => $so->getShipFrom->sf_code ?? '',
                        't_shipfromdesc' => $so->getShipFrom->sf_desc ?? '',
                    ];
                }
                
            }else{
                return false;
            }
        }
        rsort($output);
        return $output;
        
    }

    public function wsadetailinvoice($data)
    {
        $wsa = Qxwsa::first();

        $qxUrl = $wsa->wsas_url;
        $timeout = 0;

        // $sonbr = $data->getSalesOrder['so_nbr'];

        
        Schema::create('temp_table', function ($table) {
            $table->string('t_part');
            $table->string('t_invnbr');
            $table->string('t_qtyinv');
            $table->string('t_harga');
            $table->string('t_sj');
            $table->string('t_orddate');
            $table->temporary();
        });

        foreach($data->getDetail as $datas){

            $qdocRequest =
                '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
                    <Body>
                        <HRD_detail_invoice xmlns="'.$wsa->wsas_path.'">
                            <inpdomain>'.$datas['id_domain'].'</inpdomain>
                            <inpsonbr></inpsonbr>
                            <inpinvoicenbr>'.$datas['id_nbr'].'</inpinvoicenbr>
                        </HRD_detail_invoice>
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

            if(is_bool($qdocResponse)){
                Schema::dropIfExists('temp_group');
                return false;
            }

            $xmlResp = simplexml_load_string($qdocResponse);
            $xmlResp->registerXPathNamespace('ns1', $wsa->wsas_path);
            $dataloop    = $xmlResp->xpath('//ns1:tempRow');
            if(empty($dataloop)){
                Schema::dropIfExists('temp_group');
                return false;
            }
            
            $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];

            if($qdocResult == 'true'){

                foreach($dataloop as $dataloops){    
                    DB::table('temp_table')->insert([
                        't_part'   => (string)$dataloops->t_part,
                        't_invnbr' => (string)$dataloops->t_invnbr,
                        't_qtyinv' => (string)$dataloops->t_qtyinv,
                        't_harga'  => (string)$dataloops->t_harga,
                        't_sj' => (string)$dataloops->t_sj,
                        't_orddate' => (string)$dataloops->t_orddate,
                    ]);
                }
                
            }else{
                Schema::dropIfExists('temp_group');
                return false;
            }
        }

        
        $output = DB::table('temp_table')
                    ->leftJoin('sj_mstr','sj_mstr.sj_nbr','temp_table.t_sj')
                    ->leftJoin('sj_trip_hist','sj_mstr.id','sj_trip_hist.sjh_sj_mstr_id')
                    ->leftJoin('so_mstr','so_mstr.id','sj_mstr.sj_so_mstr_id')
                    ->leftJoin('co_mstr','co_mstr.id','so_mstr.so_co_mstr_id')
                    ->leftJoin('customership','so_mstr.so_ship_to','customership.cs_shipto')
                    ->leftJoin('shipfrom','so_mstr.so_ship_from','shipfrom.sf_code')
                    ->leftJoin('customer','co_mstr.co_cust_code','customer.cust_code')
                    ->leftJoin('barang','co_mstr.co_barang_id','barang.id')
                    ->leftJoin('truck','sj_mstr.sj_truck_id','truck.id')
                    ->select('t_part','t_invnbr','t_harga','t_qtyinv','t_sj', 't_orddate',
                            'customer.cust_desc','barang.barang_deskripsi',
                            'co_mstr.co_kapal','sj_mstr.sj_eff_date','truck.truck_no_polis',
                            'shipfrom.sf_desc','customership.cs_shipto_name',
                            'sj_mstr.sj_surat_jalan_customer','truck.truck_tipe_id','co_mstr.co_kapal',
                            'so_mstr.so_po_aju','so_mstr.so_ship_to')
                    ->orderBy('so_ship_to')
                    ->orderBy('sj_eff_date')
                    ->get();

        Schema::dropIfExists('temp_group');
        // dd($output);
        return $output;
    }

    // WSA Invoice QAD
    public function wsainvoiceqad($data)
    {
        $wsa = Qxwsa::first();

        $qxUrl = $wsa->wsas_url;
        $timeout = 0;

        // $sonbr = $data->getMaster->getSalesOrder['so_nbr'];
        $qdocRequest =
            '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
                <Body>
                    <HRD_detail_invoice xmlns="'.$wsa->wsas_path.'">
                        <inpdomain>'.$data['id_domain'].'</inpdomain>
                        <inpsonbr>[string]</inpsonbr>
                        <inpinvoicenbr>'.$data['id_nbr'].'</inpinvoicenbr>
                    </HRD_detail_invoice>
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

        // dd($qdocResponse,$qdocRequest);
        if(is_bool($qdocResponse)){
            return false;
        }

        $xmlResp = simplexml_load_string($qdocResponse);
        $xmlResp->registerXPathNamespace('ns1', $wsa->wsas_path);
        $dataloop    = $xmlResp->xpath('//ns1:tempRow');
        if(empty($dataloop)){
            return false;
        }
        
        $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];
        
        if($qdocResult == 'true'){
            $output = [];
            
            foreach($dataloop as $dataloops){    
                $so = SalesOrderMstr::with('getShipTo')->where('so_nbr',(string)$dataloops->t_sonbr)->first();
                $output[] = [
                    't_part'   => (string)$dataloops->t_part,
                    't_sonbr'  => (string)$dataloops->t_sonbr,
                    't_invnbr' => (string)$dataloops->t_invnbr,
                    't_qtyinv' => (string)$dataloops->t_qtyinv,
                    't_harga'  => (string)$dataloops->t_harga,
                    't_sj'     => (string)$dataloops->t_sj,
                    't_shipto' => $so->getShipTo->cs_shipto ?? '',
                    't_shiptodesc' => $so->getShipTo->cs_shipto_name ?? '',
                    't_shipfrom' => $so->getShipFrom->sf_code ?? '',
                    't_shipfromdesc' => $so->getShipFrom->sf_desc ?? '',
                ];
            }

            return $output;
        }else{
            return false;
        }
    }

    public function wsadetailinvoiceqad($data)
    {
        $wsa = Qxwsa::first();

        $qxUrl = $wsa->wsas_url;
        $timeout = 0;

        // $sonbr = $data->getMaster->getSalesOrder['so_nbr'];
        $qdocRequest =
            '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
                <Body>
                    <HRD_detail_invoice xmlns="'.$wsa->wsas_path.'">
                        <inpdomain>'.$data['id_domain'].'</inpdomain>
                        <inpsonbr></inpsonbr>
                        <inpinvoicenbr>'.$data['id_nbr'].'</inpinvoicenbr>
                    </HRD_detail_invoice>
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

        if(is_bool($qdocResponse)){
            return false;
        }

        $xmlResp = simplexml_load_string($qdocResponse);
        $xmlResp->registerXPathNamespace('ns1', $wsa->wsas_path);
        $dataloop    = $xmlResp->xpath('//ns1:tempRow');
        if(empty($dataloop)){
            return false;
        }
        
        $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];
        
        if($qdocResult == 'true'){

            Schema::create('temp_table', function ($table) {
                $table->string('t_part');
                $table->string('t_invnbr');
                $table->string('t_qtyinv');
                $table->string('t_harga');
                $table->string('t_sj');
                $table->string('t_orddate');
                $table->temporary();
            });

            foreach($dataloop as $dataloops){    
                DB::table('temp_table')->insert([
                    't_part'   => (string)$dataloops->t_part,
                    't_invnbr' => (string)$dataloops->t_invnbr,
                    't_qtyinv' => (string)$dataloops->t_qtyinv,
                    't_harga'  => (string)$dataloops->t_harga,
                    't_sj' => (string)$dataloops->t_sj,
                    't_orddate' => (string)$dataloops->t_orddate,
                ]);
            }
            
            $output = DB::table('temp_table')
                        ->leftJoin('sj_mstr','sj_mstr.sj_nbr','temp_table.t_sj')
                        ->leftJoin('so_mstr','so_mstr.id','sj_mstr.sj_so_mstr_id')
                        ->leftJoin('co_mstr','co_mstr.id','so_mstr.so_co_mstr_id')
                        ->leftJoin('customership','so_mstr.so_ship_to','customership.cs_shipto')
                        ->leftJoin('shipfrom','so_mstr.so_ship_from','shipfrom.sf_code')
                        ->leftJoin('customer','co_mstr.co_cust_code','customer.cust_code')
                        ->leftJoin('barang','co_mstr.co_barang_id','barang.id')
                        ->leftJoin('truck','sj_mstr.sj_truck_id','truck.id')
                        // ->select('t_part','t_invnbr','t_harga','t_qtyinv','t_sj',
                        //          'customer.cust_desc','barang.barang_deskripsi',
                        //          'co_mstr.co_kapal','sj_mstr.sj_eff_date','truck.truck_no_polis',
                        //          'shipfrom.sf_desc','customership.cs_shipto_name')
                        ->select('t_part','t_invnbr','t_harga','t_qtyinv','t_sj', 't_orddate',
                                'customer.cust_desc','barang.barang_deskripsi',
                                'co_mstr.co_kapal','sj_mstr.sj_eff_date','truck.truck_no_polis',
                                'shipfrom.sf_desc','customership.cs_shipto_name',
                                'sj_mstr.sj_surat_jalan_customer','truck.truck_tipe_id','co_mstr.co_kapal',
                                'so_mstr.so_po_aju')
                        ->get();


            Schema::dropIfExists('temp_group');

            return $output;
        }else{
            return false;
        }
    }

    public function wsaloadtruck($nopol)
    {
        $newnopol = $nopol."*";
        $wsa = Qxwsa::first();

        $qxUrl = $wsa->wsas_url;
        $qxReceiver = '';
        $qxSuppRes = 'false';
        $qxScopeTrx = '';
        $qdocName = '';
        $qdocVersion = '';
        $dsName = '';
        $timeout = 0;
        $domain = "ASAHSST";

        $qdocRequest =
            '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
            <Body>
                <HRD_Load_nopol xmlns="'.$wsa->wsas_path.'">
                    <intruck>'.$newnopol.'</intruck>
                </HRD_Load_nopol>
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
        if(is_bool($qdocResponse)){
            return false;
        }
        
        $xmlResp = simplexml_load_string($qdocResponse);
        
        $xmlResp->registerXPathNamespace('ns1', $wsa->wsas_path);
        
        $dataloop    = $xmlResp->xpath('//ns1:tempRow');
        if(empty($dataloop)){
            return false;
        }
        $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];
        
        if($qdocResult == 'true'){
            $status = '';
            
                foreach($dataloop as $datas){
                    return $datas->t_nopol;
                }
               
                return true;
            
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



    public function wsacekwostatus()
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
        $domain = "ASAHSST";

        $qdocRequest =
            '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
            <Body>
                <HRD_cek_wostatus xmlns="'.$wsa->wsas_path.'">
                    <inpdomain>'.$domain.'</inpdomain>
                </HRD_cek_wostatus>
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
        if(is_bool($qdocResponse)){
            return false;
        }
        
        $xmlResp = simplexml_load_string($qdocResponse);
        
        $xmlResp->registerXPathNamespace('ns1', $wsa->wsas_path);
        
        $dataloop    = $xmlResp->xpath('//ns1:tempRow');
        if(empty($dataloop)){
            return false;
        }
        $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];
        
        if($qdocResult == 'true'){
            $status = '';
            
                foreach($dataloop as $datas){
                    return $datas->t_nopol;
                }
               
                return true;
            
        }else{
            return false;
        }
    }
    
}

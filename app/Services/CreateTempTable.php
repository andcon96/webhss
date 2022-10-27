<?php

namespace App\Services;

use App\Models\Master\Domain;
use App\Models\Master\Driver;
use App\Models\Master\Prefix;
use App\Models\Master\Truck;
use App\Models\Transaksi\Cicilan;
use App\Models\Transaksi\CicilanHistory;
use App\Models\Transaksi\ReportBiaya;
use App\Models\Transaksi\SuratJalan;
use DateInterval;
use DatePeriod;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class CreateTempTable
{
    public function createPOTemp($data)
    {
        // WSA -> LMA_getPO
        Schema::create('temp_group', function ($table) {
            $table->string('po_nbr');
            $table->string('po_cust');
            $table->string('pod_line');
            $table->string('pod_part');
            $table->string('pod_qty_ord');
            $table->string('pod_qty_rcvd');
            $table->temporary();
        });

        foreach ($data as $datas) {
            DB::table('temp_group')->insert([
                'po_nbr' => $datas->t_ponbr,
                'po_cust' => $datas->t_cust,
                'pod_line' => $datas->t_line,
                'pod_part' => $datas->t_part,
                'pod_qty_ord' => $datas->t_ord,
                'pod_qty_rcvd' => $datas->t_rcvd,
            ]);
        }

        $table_po = DB::table('temp_group')->get();

        Schema::dropIfExists('temp_group');

        return $table_po;
    }

    public function getrnso()
    {
        try {
            $prefix = Prefix::firstOrFail();

            $cektahun = substr($prefix->prefix_so_rn, 0, 1);
            $yearnow = date('y');
            $lastdigityear = $yearnow % 10;

            if ($cektahun != $lastdigityear) {
                $rn_new = $lastdigityear . '00001';
            } else {
                $rn_new = $prefix->prefix_so_rn + 1;
            }
            $newprefix = $prefix->prefix_so . $rn_new;

            return $newprefix;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getrnkerusakan()
    {
        try {
            // $prefix = Prefix::firstOrFail();

            $prefix = Prefix::firstOrFail();

            $cektahun = substr($prefix->prefix_kr_rn, 0, 2);
            $yearnow = date('y');
            
            if ($cektahun != $yearnow) {
                $rn_new = $yearnow . '0001';
            } else {
                $rn_new = $prefix->prefix_kr_rn + 1;
            }
            $newprefix = $prefix->prefix_kr . $rn_new;

            return $newprefix;
        } catch (Exception $e) {

            return false;
        }
    }

    public function getrnco()
    {
        try {
            $prefix = Prefix::firstOrFail();

            $cektahun = substr($prefix->prefix_co_rn, 0, 1);
            $yearnow = date('y');
            $lastdigityear = $yearnow % 10;

            if ($cektahun != $lastdigityear) {
                $rn_new = $lastdigityear . '00001';
            } else {
                $rn_new = $prefix->prefix_co_rn + 1;
            }
            $newprefix = $prefix->prefix_co . $rn_new;

            return $newprefix;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getrnsj()
    {
        try {
            $prefix = Prefix::firstOrFail();

            $cektahun = substr($prefix->prefix_sj_rn, 0, 1);
            $yearnow = date('y');
            $lastdigityear = $yearnow % 10;

            if ($cektahun != $lastdigityear) {
                $rn_new = $lastdigityear . '00001';
            } else {
                $rn_new = $prefix->prefix_sj_rn + 1;
            }
            $newprefix = $prefix->prefix_sj . $rn_new;

            return $newprefix;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getrniv()
    {
        try {
            $prefix = Prefix::firstOrFail();
            $cektahun = substr($prefix->prefix_iv_rn, 0, 4); // Save ke DB 2022000000001, 4 digit tahun 9 digit rn
            $yearnow = date('Y');
            if ($cektahun != $yearnow) {
                $rn_new = '000000001';
            } else {
                $rn_new = substr($prefix->prefix_iv_rn, 4, 10) + 1;
                $rn_new = str_pad($rn_new, 9, '0', STR_PAD_LEFT);
            }
            $newprefix =  $yearnow."/".$prefix->prefix_iv.$rn_new;
            $updateprefix = $yearnow.$rn_new;

            return [$newprefix,$updateprefix];
        } catch (Exception $e) {
            return false;
        }
    }

    public function getDataReportPerNopol($truck, $datefrom, $dateto, $driver)
    {
        $truckcol = Truck::findOrFail($truck);
        $drivercol = Driver::findOrFail($driver);

        $nopol = $truckcol->truck_no_polis;

        $data = SuratJalan::query();
        $rbhist = ReportBiaya::query();
        $histcicilan = CicilanHistory::query()->with('getCicilan.getDriverNopol');
        
        // $cicilan = Cicilan::query()->with(['getDriverNopol','getTotalPaidActive']);

        if ($datefrom) {
            $data->where('sj_eff_date', '>=', $datefrom);
            $rbhist->where('rb_eff_date', '>=', $datefrom);
            // $cicilan->where('cicilan_eff_date','>=',$datefrom);
            $histcicilan->where('hc_eff_date','>=',$datefrom);
        }
        if ($dateto) {
            $data->where('sj_eff_date', '<=', $dateto);
            $rbhist->where('rb_eff_date', '<=', $dateto);
            // $cicilan->where('cicilan_eff_date', '<=', $dateto);
            $histcicilan->where('hc_eff_date','<=',$dateto);
        }
        if ($truck) {
            $data->where('sj_truck_id', $truck);
            $rbhist->where('rb_truck_id', $truck);
            // $cicilan->whereRelation('getDriverNopol','dn_truck_id',$truck);
            $histcicilan->whereRelation('getCicilan.getDriverNopol','dn_truck_id',$truck);
        }
        if ($driver) {
            // $cicilan->whereRelation('getDriverNopol','dn_driver_id',$driver);
            $histcicilan->whereRelation('getCicilan.getDriverNopol','dn_driver_id',$driver);
        }

        $rbhist = $rbhist->with('getTruck')->get();

        $data = $data->with(
            'getDetail.getItem',
            'getSOMaster.getCOMaster.getCustomer',
            'getSOMaster.getShipFrom',
            'getSOMaster.getShipTo',
            'getRuteHistory.getRute',
            'getBonusBarang',
        )
            ->where('sj_status', 'Closed')
            ->get();
            
        $totalrb = ReportBiaya::where('rb_truck_id', $truck)->sum('rb_nominal');

        // $cicilan = $cicilan->get();
        $histcicilan = $histcicilan->where('hc_is_active',1)->get();
            
        return [
            'data' => $data,
            'rbhist' => $rbhist,
            'totalrb' => $totalrb,
            'nopol' => $nopol,
            'histcicilan' => $histcicilan,
            'driver' => $drivercol
        ];
    }

    public function getDataReportLoosingHSST($domain, $datefrom, $dateto)
    {
        $data = SuratJalan::query();

        if ($datefrom) {
            $data->where('sj_eff_date', '>=', $datefrom);
        }

        if ($dateto) {
            $data->where('sj_eff_date', '<=', $dateto);
        }

        $data = $data->with(['getTruck.getUserDriver', 'getTruck.getTipe'])
            ->where('sj_status', 'Closed')
            ->whereRelation('getTruck', 'truck_domain', $domain)
            ->groupBy('sj_truck_id', 'sj_eff_date')
            ->selectRaw('sj_truck_id,sj_eff_date,sum(sj_default_sangu) as sangu')
            ->get();

        $listtruck = Truck::with(['getTipe', 'getUserDriver'])
            ->where('truck_domain', $domain)
            ->get();

        $interval = DateInterval::createFromDateString('1 day');
        $end = new DateTime($dateto);
        $end->modify('+1 day');
        $period = new DatePeriod(new DateTime($datefrom), $interval, $end);

        $periodcount = 0;
        foreach ($period as $dt) {
            $periodcount++;
        }

        return [
            'data' => $data,
            'listtruck' => $listtruck,
            'period' => $period,
            'periodcount' => $periodcount,
        ];
    }

    public function getDataTotalanSupirLoosing($domain, $datefrom, $dateto, $tipe)
    {
        $data = SuratJalan::query()->with('getTruck');
        // $rbhist = ReportBiaya::query()->with('getTruck');
        $cicilan = Cicilan::query()->with('getDriverNopol.getTruck');
        $listtruck = Truck::query()->with(['getTipe', 'getUserDriver'])
                        ->where('truck_domain', $domain);

        if ($datefrom) {
            $data->where('sj_eff_date', '>=', $datefrom);
            // $rbhist->where('rb_eff_date', '>=', $datefrom);
            $cicilan->where('cicilan_eff_date', '>=', $datefrom);
        }

        if ($dateto) {
            $data->where('sj_eff_date', '<=', $dateto);
            // $rbhist->where('rb_eff_date', '<=', $dateto);
            $cicilan->where('cicilan_eff_date', '<=', $dateto);
        }

        if ($tipe) {
            if($tipe == 1){
                $data->whereHas('getTruck', function($q){
                    $q->whereIn('truck_tipe_id',['1','2','3','4']);
                });
                // $rbhist->whereHas('getTruck', function($q){
                //     $q->whereIn('truck_tipe_id',['1','2','3','4']);
                // });
                $cicilan->whereHas('getDriverNopol.getTruck', function($q){
                    $q->whereIn('truck_tipe_id',['1','2','3','4']);
                });
                $listtruck->whereIn('truck_tipe_id',['1','2','3','4']);
            }elseif($tipe == 2){
                $data->whereHas('getTruck', function($q){
                    $q->whereIn('truck_tipe_id',['5','6']);
                });
                // $rbhist->whereHas('getTruck', function($q){
                //     $q->whereIn('truck_tipe_id',['5','6']);
                // });
                $cicilan->whereHas('getDriverNopol.getTruck', function($q){
                    $q->whereIn('truck_tipe_id',['5','6']);
                });
                $listtruck->whereIn('truck_tipe_id',['5','6']);
            }
        }

        $data = $data->with(['getTruck.getUserDriver', 'getTruck.getTipe'])
            ->where('sj_status', 'Closed')
            ->whereRelation('getTruck', 'truck_domain', $domain)
            ->groupBy('sj_truck_id')
            ->selectRaw('sj_truck_id,sum(sj_default_sangu) as defaultSangu, sum(sj_tot_sangu) as totalSangu')
            ->get();

        // $rbhist =  $rbhist->where('rb_is_active', 1)
        //     ->with(['getTruck.getUserDriver', 'getTruck.getTipe'])
        //     ->whereRelation('getTruck', 'truck_domain', $domain)
        //     ->groupBy('rb_truck_id')
        //     ->selectRaw('rb_truck_id,sum(CASE WHEN rb_is_pemasukan = 1 then - rb_nominal else rb_nominal end) as total')
        //     ->get();

        $cicilan = $cicilan
            ->join('driver_nopol','cicilan.cicilan_dn_id','=','driver_nopol.id')
            ->where('cicilan_is_active', 1)
            ->whereRelation('getDriverNopol.getTruck', 'truck_domain', $domain)
            ->groupBy('dn_truck_id')
            ->selectRaw('cicilan.*,driver_nopol.dn_truck_id,SUM(cicilan_nominal) as total')
            ->get();

        $listtruck = $listtruck->orderby('truck_no_polis','asc')->get();

        return [
            'data' => $data,
            'listtruck' => $listtruck,
            'cicilan' => $cicilan
            // 'rbhist' => $rbhist,
        ];
    }
    
    public function terbilang ($angka) {
        // deklarasikan tipe data parameter menjadi float / double
        $angka = (float)$angka;
        $bilangan = ['','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan','Sepuluh','Sebelas'];

        if ($angka < 12) {
            return $bilangan[$angka];
        } else if ($angka < 20) {
            return $bilangan[$angka - 10] . ' Belas';
        } else if ($angka < 100) {
            $hasil_bagi = intval($angka / 10);
            $hasil_mod = $angka % 10;
            return trim(sprintf('%s Puluh %s', $bilangan[$hasil_bagi], $bilangan[$hasil_mod]));
        } else if ($angka < 200) {
            return sprintf('Seratus %s', $this->terbilang($angka - 100));
        } else if ($angka < 1000) {
            $hasil_bagi = intval($angka / 100);
            $hasil_mod = $angka % 100;
            return trim(sprintf('%s Ratus %s', $bilangan[$hasil_bagi], $this->terbilang($hasil_mod)));
        } else if ($angka < 2000) {
            return trim(sprintf('Seribu %s', $this->terbilang($angka - 1000)));
        } else if ($angka < 1000000) {
            $hasil_bagi = intval($angka / 1000); 
            $hasil_mod = $angka % 1000;
            return sprintf('%s Ribu %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod));
        } else if ($angka < 1000000000) {
            $hasil_bagi = intval($angka / 1000000);
            $hasil_mod = $angka % 1000000;
            return trim(sprintf('%s Juta %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
        } else if ($angka < 1000000000000) {
            $hasil_bagi = intval($angka / 1000000000);
            $hasil_mod = fmod($angka, 1000000000);
            return trim(sprintf('%s Milyar %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
        } else if ($angka < 1000000000000000) {
            $hasil_bagi = $angka / 1000000000000;
            $hasil_mod = fmod($angka, 1000000000000);
            return trim(sprintf('%s Triliun %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
        } else {
            return 'Data Salah';
        }
    }
}

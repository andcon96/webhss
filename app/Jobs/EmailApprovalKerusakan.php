<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class EmailApprovalKerusakan
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $pesan;
    protected $wonbr;
    protected $nopol;
    protected $gandengan;
    protected $kerusakan;
    protected $emailto;
    protected $gandengcode;

    public function __construct($pesan,$wonbr,$nopol,$gandengan,$kerusakan,$emailto,$gandengcode)
    {
        $this->pesan = $pesan;
        $this->wonbr = $wonbr;
        $this->nopol = $nopol;
        $this->gandengan = $gandengan;
        $this->kerusakan = $kerusakan;
        $this->emailto = $emailto;
        $this->gandengcode = $gandengcode;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $emailto = $this->emailto;
        $email = [];
        foreach($emailto as $et){
            array_push($email,$et->approval_email);
        }
        $wonbr = $this->wonbr;
        $nopol = $this->nopol;
        $gandengan = $this->gandengan;
        $gandengcode = $this->gandengcode;
        $pesan = $this->pesan;
        $kerusakan = $this->kerusakan;
        
        $rusaknbr = Crypt::encrypt($wonbr);
        $nopolnbr = Crypt::encrypt($nopol);
        $gandengnbr = Crypt::encrypt($gandengan);
        // if(!empty($gandengcode)){
            $crypgandengcode = Crypt::encrypt($gandengcode);
        // }
        // else{
        //     $crypgandengcode = '';
        // }
        $yes = Crypt::encrypt('yes');
        $no = Crypt::encrypt('no');
        // dd($wonbr,$nopolnbr,$gandengnbr,$rusaknbr,$crypgandengcode,$pesan,$kerusakan,$yes,$no);
        Mail::send('emails.kerusakanemail',[
            'pesan' => $pesan,
            'nopol' => $nopol,
            'gandengan' => $gandengan,
            'kerusakan' => $kerusakan,
            'wonbr'=>$wonbr,
            'param1' => $rusaknbr,
            'param2' => $nopolnbr,
            'param3' => $yes,
            'param4' => $no,
            'param5' => $gandengnbr,
            'param6' => $crypgandengcode
        ],
        function ($message) use ($email,$nopol,$gandengan) {
            if(empty($nopol)){
                $message->subject('Gandengan Breackdown Approval - ' . $gandengan);
            }
            else if(!empty($nopol)){
                $message->subject('Truck Breackdown Approval - ' . $nopol);
            }
            $message->from('anugerahworkshop@gmail.com');
            $message->to($email);
        });
        
    }
}

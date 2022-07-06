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
    protected $kerusakan;
    protected $emailto;
    
    public function __construct($pesan,$wonbr,$nopol,$kerusakan,$emailto)
    {
        $this->pesan = $pesan;
        $this->wonbr = $wonbr;
        $this->nopol = $nopol;
        $this->kerusakan = $kerusakan;
        $this->emailto = $emailto;
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
        $pesan = $this->pesan;
        $kerusakan = $this->kerusakan;
        $rusaknbr = Crypt::encrypt($wonbr);
        $nopolnbr = Crypt::encrypt($nopol);
        $yes = Crypt::encrypt('yes');
        $no = Crypt::encrypt('no');
        Mail::send('emails.kerusakanemail',[
            'pesan' => $pesan,
            'nopol' => $nopol,
            'kerusakan' => $kerusakan,
            'wonbr'=>$wonbr,
            'param1' => $rusaknbr,
            'param2' => $nopolnbr,
            'param3' => $yes,
            'param4' => $no
        ],
        function ($message) use ($email,$nopol) {
            $message->subject('Truck Breackdown Approval - ' . $nopol);
            $message->to($email);
        });
        
    }
}

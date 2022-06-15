<?php

namespace App\Http\Controllers\Test;

use App\Helpers\Formatter;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AppProfile;
use App\Models\Bank;
use App\Models\CustomerData;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RouterOS\{Client, Query, Config};
use RouterOS\Exceptions\ConnectException;
use Dapphp\Radius\Radius;

class SampleController extends Controller
{
    //
    public function index()
    {
        $appProfile = AppProfile::first();
        $today = Carbon::today()->format('d M Y');

        $customerData = CustomerData::with(['customer_profiles', 'product_services', 'area_products',])->first();
        $customerProfile = $customerData->customer_profiles;
        $productService = $customerData->product_services;
        $areaProduct = $customerData->area_products;
        $bank = Bank::whereActive(1)->first();

        $data = [
            'invoice_code' => uniqid(),
            'invoice_date' => $today,
            'invoice_due' => $today,
                            
            'company_logo' => $appProfile->logo ? asset('storage/app_profile/' . $appProfile->logo) : asset('logo.jpg'),
            'company_name' => $appProfile->name,
            'company_contact' => implode(', ', [$appProfile->email, $appProfile->telp]),
            'company_address' => $appProfile->address,
                            
            'cus_code' => $customerData->code,
            'cus_name' => $customerProfile->name,
            'cus_contact' => implode(', ', [$customerProfile->email, $customerProfile->handphone]),
                            
            'product_service' => 'Internet 1 Mbps',

            'price_sub' => Formatter::rupiah(100),
            'price_ppn' => Formatter::rupiah(10),
            'price_discount' => Formatter::rupiah(50),
            'price_total' => Formatter::rupiah(60),

            'price_active_after_cutoff' => 0,
            'price_after_cutoff_format' => Formatter::rupiah(0),

            'payment_type' => 'payment_link',
            'trx_payment' => 'google.com',

            'bank_code' => $bank->code,
            'bank_name' => $bank->name,
            'responsible_name' => $bank->responsible_name,
                            
            'sayit' => Formatter::rupiahSpeakOnBahasa(60),
        ];

        $pdf = PDF::loadView('billing.invoice.makefile.pdf', $data);
        
        return $pdf->stream();
    }

    public function mikrotik()
    {
        try {
            $config = (new Config())
                ->set('host', '192.168.1.19')
                ->set('port', 8728)
                ->set('user', 'dafi')
                ->set('pass', '123123');

            # setup
            // $client = new Client($config);
            # membuat user password hotpost
            // for ($i=1; $i <= 99; $i++) { 
            //     $query = (new Query('/ip/hotspot/user/add'))
            //         ->equal('server', 'hotspot1')
            //         ->equal('name', 'guest_' . $i)
            //         ->equal('password', uniqid())
            //         ->equal('profile', 'default');
            //     $client->query($query);

            //     # jika membuat banyak harap membuat jeda dulu sebentar
            //     sleep(.1);
            // }

            

            # membuat limitasi bandwidth
            // $client = new Client($config);
            // $query = (new Query('/queue/simple/add'))
            //     ->equal('name', 'buat api')
            //     ->equal('target', '10.1.1.14/32')
            //     ->equal('max-limit', '1000000/1000000'); # Tx Rx (kilobit to megabit)
            // $client->query($query);

            # print out
            // sleep(1);
            // unset($client);

            // $client = new Client($config);
            // $query = new Query('/ip/hotspot/user/print');
            // $res = $client->query($query)->read();

            // sleep(.1);
            // unset($client);

            // $client = new Client($config);
            // for ($i=3; $i <= 20; $i++) { 
            //     $query = (new Query('/ip/firewall/address-list/add'))
            //         ->equal('list', 'test')
            //         ->equal('address', '10.10.1.'.$i)
            //         ->equal('disabled', 'no');
            //     $client->query($query);

            //     sleep(.1);
            // }

            // sleep(1);
            // unset($client);

            // $client = new Client($config);
            // $query = new Query('/ip/firewall/address-list/print');
            // $res = $client->query($query)->read();

            $client = new Client($config);
            $query = (new Query('/ip/firewall/address-list/print'))
                ->where('address', '10.10.1.3');
            $result = $client->query($query)->read();

            $res = [];
            if (!empty($result) && isset($result[0]['.id'])) {
                $id = $result[0]['.id'];

                $query = (new Query('/ip/firewall/address-list/set'))
                    ->equal('.id', $id)
                    ->equal('comment', 'suspend at ' . Carbon::now());
                $client->query($query);

                sleep(.1);

                $query = (new Query('/ip/firewall/address-list/disable'))
                    ->equal('.id', $id);

                $res = $client->query($query)->read();
            }

            return $res;
        } catch (\Exception $e) {
            $message = $e->getMessage() . PHP_EOL;

            return $message;
        }
    }

    public function mikrotikSample()
    {
        $config = (new Config())
            ->set('host', '192.168.1.10')
            ->set('port', 8728)
            ->set('user', 'dafi')
            ->set('pass', '123123');

        # print 
        // $query = '/ip/address/print';

        # add
        // $query = (new Query('/user/add'))
        //     ->equal('group', 'full')
        //     ->equal('name', 'user')
        //     ->equal('password' , '123123');

        # add loop
        // for ($i=1; $i <= 5; $i++) { 
        //     $query = (new Query('/user/add'))
        //         ->equal('group', 'full')
        //         ->equal('name', 'user'.$i)
        //         ->equal('password' , '123123');
        //     $client->query($query);
        // }

        # remove
        // $client = new Client($config);
        // $query = (new Query('/user/remove'))
        //     ->equal('.id', 3);
        // $client->query($query);
        

        # remove loop
        // for ($i=3; $i <= 7; $i++) { 
        //     $query = (new Query('/user/remove'))
        //         ->equal('.id', $i);
            
        //     $client->query($query);
        // }

        # disabled/enabled
        // $query = (new Query('/user/disable'))
        //     ->equal('.id', '2');

        # memberikan jeda untuk request selanjutnya
        sleep(1);
        unset($client);
        $client = new Client($config);
        $query = '/user/print';
        $res = $client->query($query)->read();

        return $res;
    }

    public function radius()
    {
        $client = new Radius();

        $client->setServer('192.168.1.2') // RADIUS server address
            ->setSecret('kura');
    //    ->setNasIpAddress('10.0.1.2') // NAS server address
    //    ->setAttribute(32, 'login');

        $username = "bob";
        $password = "hello";

        // PAP authentication; returns true if successful, false otherwise
        // $authenticated = $client->accessRequest($username, $password);

        // CHAP-MD5 authentication
        $client->setChapPassword($password); // set chap password
        $authenticated = $client->accessRequest($username); // authenticate, don't specify pw here

        // MSCHAP v1 authentication
        // $client->setMSChapPassword($password); // set ms chap password (uses openssl or mcrypt)
        // $authenticated = $client->accessRequest($username);

        // EAP-MSCHAP v2 authentication
        // $authenticated = $client->accessRequestEapMsChapV2($username, $password); // failed

        if ($authenticated === false) {
            // false returned on failure
            echo sprintf(
                "Access-Request failed with error %d (%s).\n",
                $client->getErrorCode(),
                $client->getErrorMessage()
            );
        } else {
            // access request was accepted - client authenticated successfully
            echo "Success!  Received Access-Accept response from RADIUS server.\n";
        }
    }
}

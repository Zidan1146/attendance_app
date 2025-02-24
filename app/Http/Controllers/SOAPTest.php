<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SoapClient;
use SoapFault;

class SOAPTest extends Controller
{
    public function index() {

    }
    public function callSoap() {
        $machineIP = '192.168.1.201:4370';

        $client = new SoapClient(null, [
            'location' => route('soap.test'),
            'uri' => "http://$machineIP/iWsService",
            'trace' => 1
        ]);

        $params = [
            'ArgComKey' => 0
        ];

        try {
            $response = $client->__soapCall("Restart", [$params]);

            if($response) {
                return "Response: $response";
            } else {
                return "No response";
            }
        } catch(SoapFault $fault) {
            return "SOAP Malfunction: (Fault Code: {$fault->faultcode}, Fault String: {$fault->faultstring})";
        }catch (\Throwable $th) {
            return "Error detected: $th";
        }
    }
}

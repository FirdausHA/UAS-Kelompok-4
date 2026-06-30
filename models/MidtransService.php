<?php

require_once __DIR__ . '/../config/midtrans.php';

class MidtransService {
    private $server_key;
    private $api_url;

    public function __construct() {
        $this->server_key = MIDTRANS_SERVER_KEY;
        $this->api_url = MIDTRANS_API_URL;
    }

    /**
     * Create Snap transaction
     * @param array $params Transaction details
     * @return array Response from Midtrans
     */
    public function createSnapTransaction($params) {
        $url = $this->api_url . '/snap/v1/transactions';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($this->server_key . ':')
        ]);
        
        $result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return [
            'http_code' => $http_code,
            'response' => json_decode($result, true)
        ];
    }

    /**
     * Check transaction status from Midtrans
     * @param string $order_id Order ID
     * @return array Response from Midtrans
     */
    public function checkTransactionStatus($order_id) {
        $url = $this->api_url . '/v2/' . $order_id . '/status';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . base64_encode($this->server_key . ':')
        ]);
        
        $result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return [
            'http_code' => $http_code,
            'response' => json_decode($result, true)
        ];
    }
}

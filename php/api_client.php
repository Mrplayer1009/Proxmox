<?php
class ApiClient {
    private $base_url;
    
    public function __construct($base_url) {
        $this->base_url = rtrim($base_url, '/');
    }
    
    public function get($endpoint, $params = []) {
        $url = $this->buildUrl($endpoint, $params);
        return $this->request('GET', $url);
    }
    
    public function post($endpoint, $data = [], $params = []) {
        $url = $this->buildUrl($endpoint, $params);
        return $this->request('POST', $url, $data);
    }
    
    public function put($endpoint, $data = [], $params = []) {
        $url = $this->buildUrl($endpoint, $params);
        return $this->request('PUT', $url, $data);
    }
    
    public function delete($endpoint, $params = []) {
        $url = $this->buildUrl($endpoint, $params);
        return $this->request('DELETE', $url);
    }
    
    private function buildUrl($endpoint, $params = []) {
        $url = $this->base_url . '/' . $endpoint;
        
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        return $url;
    }
    
    private function request($method, $url, $data = null) {
        $curl = curl_init();
        
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
        ];
        
        if ($data !== null) {
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
            $options[CURLOPT_HTTPHEADER] = [
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($data))
            ];
        }
        
        curl_setopt_array($curl, $options);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            error_log("cURL Error: " . $err);
            throw new Exception("cURL Error: " . $err);
        }
        
        $decoded_response = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON Decode Error: " . json_last_error_msg());
            error_log("Raw Response: " . $response);
            throw new Exception("JSON Decode Error: " . json_last_error_msg());
        }
        
        return $decoded_response;
    }
}

// Initialize API client
$api_base_url = 'http://localhost/PA23/api/api.php';
$api_client = new ApiClient($api_base_url);
?>

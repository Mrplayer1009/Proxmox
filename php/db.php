<?php
require_once 'api_client.php';
$api_client = new ApiClient();
function executeQuery($sql, $params = []) {
    global $api_client;
    
    $sql = trim($sql);
    $action = strtoupper(substr($sql, 0, strpos($sql, ' ')));
    
    preg_match('/FROM\s+(\w+)/i', $sql, $matches);
    $table = isset($matches[1]) ? $matches[1] : '';
    
    $route_map = [
        'users' => 'users',
        'interventions' => 'interventions',
        'services' => 'services',
        'evaluations' => 'evaluations',
        'annonces' => 'annonces',
        'documents' => 'documents',
        'pdf_achat' => 'achats'
    ];
    
    $route = isset($route_map[$table]) ? $route_map[$table] : '';
    
    if (empty($route)) {
        return false;
    }
    
    switch ($action) {
        case 'SELECT':
            $where_params = [];
            if (preg_match('/WHERE\s+(.*?)(?:ORDER|GROUP|LIMIT|$)/is', $sql, $where_matches)) {
                $where_clause = $where_matches[1];
                
                if (preg_match('/(\w+)\s*=\s*\?/i', $where_clause, $cond_matches)) {
                    $field = $cond_matches[1];
                    $where_params[$field] = $params[0];
                }
                
                if (preg_match('/(\w+)\s+LIKE\s+\?/i', $where_clause, $like_matches)) {
                    $field = $like_matches[1];
                    $where_params['search'] = str_replace('%', '', $params[0]);
                }
            }
            
            $response = $api_client->get($route, $where_params);
            
            return new FakePDOStatement($response['data'] ?? []);
            
        case 'INSERT':
            preg_match('/INSERT\s+INTO\s+\w+\s*$$(.*?)$$\s*VALUES\s*$$(.*?)$$/is', $sql, $insert_matches);
            $columns = explode(',', $insert_matches[1]);
            $values = $params;
            
            $data = [];
            foreach ($columns as $i => $column) {
                $column = trim($column);
                $data[$column] = $values[$i];
            }
            
            $response = $api_client->post($route, $data);
            
            return true;
            
        case 'UPDATE':
            preg_match('/WHERE\s+id\s*=\s*(\d+|\?)/i', $sql, $id_matches);
            $id = isset($id_matches[1]) && $id_matches[1] === '?' ? $params[count($params) - 1] : $id_matches[1];
            
            preg_match('/SET\s+(.*?)\s+WHERE/is', $sql, $set_matches);
            $set_parts = explode(',', $set_matches[1]);
            
            $data = [];
            $param_index = 0;
            foreach ($set_parts as $part) {
                if (preg_match('/(\w+)\s*=\s*\?/i', $part, $col_matches)) {
                    $column = $col_matches[1];
                    $data[$column] = $params[$param_index++];
                }
            }
            
            $response = $api_client->put($route, $data, ['id' => $id]);
            
            return true;
            
        case 'DELETE':
            preg_match('/WHERE\s+id\s*=\s*(\d+|\?)/i', $sql, $id_matches);
            $id = isset($id_matches[1]) && $id_matches[1] === '?' ? $params[0] : $id_matches[1];
            
            $response = $api_client->delete($route, ['id' => $id]);
            
            return true;
            
        default:
            return false;
    }
}

class FakePDOStatement {
    private $data;
    private $position = 0;
    
    public function __construct($data) {
        $this->data = is_array($data) ? $data : [];
    }
    
    public function fetch($fetch_style = PDO::FETCH_ASSOC) {
        if ($this->position >= count($this->data)) {
            return false;
        }
        
        $row = $this->data[$this->position++];
        return $row;
    }
    
    public function fetchAll($fetch_style = PDO::FETCH_ASSOC) {
        return $this->data;
    }
    
    public function execute($params = []) {
        return true;
    }
    
    public function rowCount() {
        return count($this->data);
    }
}

class FakePDO {
    public function prepare($sql) {
        return new FakePDOStatement([]);
    }
    
    public function query($sql) {
        return executeQuery($sql);
    }
    
    public function lastInsertId() {
        return 0;
    }
}


$pdo = new FakePDO();

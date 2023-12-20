<?php

class Capsule
{
    private $conn;

    public function __construct($host, $dbname, $username, $password)
    {
        $this->conn = new mysqli($host, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function insert($tableName, $data)
    {
        $columns = implode(', ', array_keys($data));
        $values = "'" . implode("', '", $data) . "'";
        $query = "INSERT INTO $tableName ($columns) VALUES ($values)";
        if ($this->conn->query($query)) {
            echo "Data inserted successfully.\n";
        } else {
            echo "Error inserting data: " . $this->conn->error . "\n";
        }
    }

    public function get($tableName, $id)
    {
        $query = "SELECT * FROM $tableName WHERE id = $id";
        $result = $this->conn->query($query);
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            echo "No data found for ID: $id\n";
            return null;
        }
    }

    public function getAll($tableName, $orderByColumn = null)
    {
        $orderByClause = $orderByColumn ? "ORDER BY $orderByColumn" : "";

        $query = "SELECT * FROM $tableName $orderByClause";
        $result = $this->conn->query($query);

        $data = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function delete($tableName, $id)
    {
        $query = "DELETE FROM $tableName WHERE id = $id";
        $result = $this->conn->query($query);
        
        if ($result) {
            echo "Data deleted successfully.\n";
        } else {
            echo "Error deleting data: " . $this->conn->error . "\n";
        }
    }

}

$capsule = new Capsule('db8', 'laravel', 'root', 'gaditek123');
$allProducts = $capsule->getAll('products', 'id');

echo "<pre>";
print_r($allProducts);
echo "</pre>";

// $capsule->delete('products', 10);

// $product = $capsule->get('products', 9);

// if ($product) {
//     echo "Product ID: {$product['id']}, Name: {$product['name']}, Description: {$product['description']}, Image: {$product['image']}\n";
// }
?>

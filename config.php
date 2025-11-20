<?php

class Database
{
    private $conn;

    public function __construct()
    {

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        
        /* Спрятать данные */
        // Локальные данные
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "Unveil";
        
        // PS данные
        /*$servername = "localhost:3306";
        $username = "unveilkz_admin";
        $password = "!nV?b0xhVzOgln66";
        $dbname = "unveilkz_db";
        */

        $this->conn = new mysqli($servername, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function __destruct()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    function validate($value)
    {
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
        $value = mysqli_real_escape_string($this->conn, $value);
        return $value;
    }

    public function executeQuery($sql)
    {
        $result = $this->conn->query($sql);
        if ($result === false) {
            die("ERROR: " . $this->conn->error);
        }
        return $result;
    }

    public function getBaseUrl()
    {
        //$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        //$base_url = $protocol . $_SERVER['HTTP_HOST'] . '/';
        return "http://localhost/unveil/";
    }

    public function select($table, $columns = "*", $condition = "")
    {
        $sql = "SELECT $columns FROM $table $condition";
        return $this->executeQuery($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function insert($table, $data)
    {
        $keys = implode(', ', array_keys($data));
        $values = "'" . implode("', '", array_values($data)) . "'";
        $sql = "INSERT INTO $table ($keys) VALUES ($values)";
        return $this->executeQuery($sql);
    }

    public function update($table, $data, $condition = "")
    {
        $set = '';
        foreach ($data as $key => $value) {
            $set .= "$key = '$value', ";
        }
        $set = rtrim($set, ', ');
        $sql = "UPDATE $table SET $set $condition";
        return $this->executeQuery($sql);
    }

    public function delete($table, $condition = "")
    {
        $sql = "DELETE FROM $table $condition";
        return $this->executeQuery($sql);
    }

    public function hashPassword($password)
    {
        return hash_hmac('sha256', $password, 'unveilSecretKey'); // Спрятать ключ в безопасное место
    }

    public function authenticate($username, $password, $table)
    {
        $username = $this->validate($username);
        $condition = "WHERE username = '" . $username . "' AND password = '" . $this->hashPassword($password) . "'";
        return $this->select($table, "*", $condition);
    }

    public function registerUser($name, $number, $email, $username, $password, $role)
    {
        $name = $this->validate($name);
        $number = $this->validate($number);
        $email = $this->validate($email);
        $username = $this->validate($username);

        $password_hash = $this->hashPassword($password);

        $data = array(
            'name' => $name,
            'number' => $number,
            'email' => $email,
            'username' => $username,
            'password' => $password_hash,
            'role' => $role
        );

        $user_id = $this->insert('accounts', $data);

        if ($user_id) {
            return $user_id;
        }
        return false;
    }

    function saveImagesToDatabase($files, $path, $productId)
    {
        if (is_array($files['tmp_name'])) {
            $uploaded_files = array();
            foreach ($files['tmp_name'] as $index => $tmp_name) {
                $file_name = $files['name'][$index];
                $file_info = pathinfo($file_name);
                $file_extension = $file_info['extension'];
                $new_file_name = md5($tmp_name . date("Y-m-d_H-i-s") . rand(1, 9999999) . $productId) . "." . $file_extension;
                if (move_uploaded_file($tmp_name, $path . $new_file_name)) {
                    $uploaded_files[] = $new_file_name;
                    $this->insert('product_images', array('product_id' => $productId, 'image_url' => $new_file_name));
                }
            }
            return $uploaded_files;
        } else {
            $file_name = $files['name'];
            $file_tmp = $files['tmp_name'];

            $file_info = pathinfo($file_name);
            $file_extension = $file_info['extension'];

            $new_file_name = md5($file_tmp . date("Y-m-d_H-i-s") . rand(1, 9999999) . $productId) . "." . $file_extension;

            if (move_uploaded_file($file_tmp, $path . $new_file_name)) {
                $this->insert('product_images', array('product_id' => $productId, 'image_url' => $new_file_name));
                return $new_file_name;
            }
            return false;
        }
    }

    function saveFilesToDatabase($files, $path, $productId)
    {
        if (is_array($files['tmp_name'])) {
            $uploaded_files = array();
            foreach ($files['tmp_name'] as $index => $tmp_name) {
                $file_name = $files['name'][$index];
                $file_info = pathinfo($file_name);
                $file_extension = $file_info['extension'];
                $new_file_name = md5($tmp_name . date("Y-m-d_H-i-s") . rand(1, 9999999) . $productId) . "." . $file_extension;
                if (move_uploaded_file($tmp_name, $path . $new_file_name)) {
                    $uploaded_files[] = $new_file_name;
                    $this->insert('product_docs', array('product_id' => $productId, 'doc_url' => $new_file_name, 'doc_origin_name' => $file_name.$file_extension));
                }
            }
            return $uploaded_files;
        } else {
            $file_name = $files['name'];
            $file_tmp = $files['tmp_name'];

            $file_info = pathinfo($file_name);
            $file_extension = $file_info['extension'];

            $new_file_name = md5($file_tmp . date("Y-m-d_H-i-s") . rand(1, 9999999) . $productId) . "." . $file_extension;

            if (move_uploaded_file($file_tmp, $path . $new_file_name)) {
                $this->insert('product_docs', array('product_id' => $productId, 'doc_url' => $new_file_name, 'doc_origin_name' => $file_name.$file_extension));
                return $new_file_name;
            }
            return false;
        }
    }

    function saveImage($file, $path)
    {
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];

        $file_info = pathinfo($file_name);
        $file_format = $file_info['extension'];

        $new_file_name = md5($file_tmp . date("Y-m-d_H-i-s")) . rand(1, 9999999) . "." . $file_format;

        if (move_uploaded_file($file_tmp, $path . $new_file_name)) {
            return $new_file_name;
        }
        return false;
    }

    public function getCategories()
    {
        $categories = array();
        $result = $this->select('categories', 'id, category_name');
        foreach ($result as $row) {
            $categories[$row['id']] = $row['category_name'];
        }
        return $categories;
    }

    public function getProduct($product_id)
    {
        $result = $this->select('products', '*', 'WHERE id = ' . $product_id);
        return $result[0];
    }

    public function getProductImageID($product_id)
    {
        $images = $this->select('product_images', 'id', 'WHERE product_id = ' . $product_id);
        $id = array();
        foreach ($images as $image) {
            $id[] = $image['id'];
        }
        return $id;
    }

    public function getProductDocs($product_id)
    {
        $docs = $this->select('product_docs', 'doc_url, doc_origin_name', 'WHERE product_id = ' . $product_id);
        return $docs;
    }


    function getProductImage($id)
    {
        global $query;
        $result = $this->select('product_images', 'image_url', 'WHERE id = ' . $id);
        return $result[0]['image_url'];
    }

    public function getCartItems($user_id)
    {
        $sql = "SELECT 
        p.id,
        p.name,
        p.price_current,
        p.price_old,
        c.number_of_products,
        (p.price_current * c.number_of_products) AS total_price
    FROM 
        cart c
    JOIN
        products p ON c.product_id = p.id
    WHERE 
        c.user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $cartItems = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $cartItems;
    }

    public function getProductImages($product_id)
    {
        $sql = "SELECT image_url FROM product_images WHERE product_id = $product_id";
        $result = $this->executeQuery($sql)->fetch_all(MYSQLI_ASSOC);
        $imageUrls = array();
        foreach ($result as $row) {
            $imageUrls[] = $row['image_url'];
        }
        return $imageUrls;
    }

    public function getWishes($user_id)
    {
        $sql = "SELECT 
        p.name,
        p.price_current,
        p.price_old,
        w.product_id
    FROM 
        wishes w
    JOIN
        products p ON w.product_id = p.id
    WHERE 
        w.user_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $result = $stmt->get_result();

        $wishes = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $wishes;
    }

    public function getProjectsList($user_id)
    {
        $sql = "SELECT 
            p.id,
            p.name,
            p.location,
            p.stage,
            tmpPs.totalUsers,
            tmpPi.totalItems
        FROM 
            projects as ps, 
            project as p
            left join (SELECT project_id, count(project_id) AS totalUsers FROM projects GROUP BY project_id) AS tmpPs on tmpPs.project_id = p.id
            left join (SELECT project_id, count(project_id) AS totalItems FROM project_items GROUP BY project_id) as tmpPi on tmpPi.project_id = p.id
        WHERE 
            ps.user_id = ? AND 
            ps.project_id = p.id;";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $projectsList = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $projectsList;
    }

    public function getProjectItems($project_id)
    {
        $sql = "SELECT 
            pi.id,
            pi.product_id,
            a2.name as whoAdded,
            pi.quantity,
            pi.schematic,
            pi.comments,
            pi.status,
            pr.name as productName,
            pr.price_current,
            pr.size,
            a.name as sellerName
        FROM 
            `project_items` pi
            join `products` pr on pr.id = pi.product_id
            join `accounts` a on a.id = pr.seller_id
            join `accounts` a2 on a2.id = pi.user_id
        WHERE
            pi.project_id = ?;";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $project_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $projectDetails = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $projectDetails;
    }

    public function lastInsertId($table, $data)
    {
        $keys = implode(', ', array_keys($data));
        $values = "'" . implode("', '", array_values($data)) . "'";
        $sql = "INSERT INTO $table ($keys) VALUES ($values)";
        $insert_result = $this->executeQuery($sql);
        if ($insert_result) {
            return $this->conn->insert_id;
        } else {
            return false;
        }
    }
}

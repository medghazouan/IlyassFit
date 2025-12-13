<?php
// Generic CRUD functions using PDO

// Create (Insert) - returns last insert ID or false
function create($pdo, $table, $data) {
    try {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $pdo->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        $stmt->execute();
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Create error: " . $e->getMessage());
        return false;
    }
}

// Read All - returns array of records
function readAll($pdo, $table, $orderBy = 'id', $order = 'DESC') {
    try {
        $sql = "SELECT * FROM $table ORDER BY $orderBy $order";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Read all error: " . $e->getMessage());
        return [];
    }
}

// Read One - returns single record
function readOne($pdo, $table, $id) {
    try {
        $sql = "SELECT * FROM $table WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Read one error: " . $e->getMessage());
        return false;
    }
}

// Read with condition
function readWhere($pdo, $table, $column, $value, $orderBy = 'id', $order = 'DESC') {
    try {
        $sql = "SELECT * FROM $table WHERE $column = ? ORDER BY $orderBy $order";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$value]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Read where error: " . $e->getMessage());
        return [];
    }
}

// Update - returns true on success
function update($pdo, $table, $data, $id) {
    try {
        $setParts = [];
        foreach (array_keys($data) as $key) {
            $setParts[] = "$key = :$key";
        }
        $setClause = implode(', ', $setParts);
        
        $sql = "UPDATE $table SET $setClause WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(':id', $id);
        
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Update error: " . $e->getMessage());
        return false;
    }
}

// Delete - returns true on success
function delete($pdo, $table, $id) {
    try {
        $sql = "DELETE FROM $table WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$id]);
    } catch (PDOException $e) {
        error_log("Delete error: " . $e->getMessage());
        return false;
    }
}

// Count records
function countRecords($pdo, $table, $column = null, $value = null) {
    try {
        if ($column && $value) {
            $sql = "SELECT COUNT(*) FROM $table WHERE $column = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$value]);
        } else {
            $sql = "SELECT COUNT(*) FROM $table";
            $stmt = $pdo->query($sql);
        }
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Count error: " . $e->getMessage());
        return 0;
    }
}
?>

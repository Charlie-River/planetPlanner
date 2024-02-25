<?php
session_start(); // Start the session (if not already started)
require_once("connectdb.php");
// fetch_tasks.php

if (isset($_POST['folderId'])) {
    $folderId = $_POST['folderId'];

    $query = $db->prepare("SELECT * FROM tasks WHERE folder_id = ?");
    $query->execute([$folderId]);
    $tasks = $query->fetchAll(PDO::FETCH_ASSOC);

    // Return tasks as JSON
    echo json_encode($tasks);
} else {
    echo 'Invalid request';
}
?>

<?php
if (!isset($_GET['id'])) {
    header('Location: index_blog.php');
    exit();
} else {
    $id = $_GET['id'];
}
//include database connection
require_once('./includes/connection.php');
if (!is_numeric($id)) {
    header('Location: index_blog.php');
}
    $query = $db->prepare('DELETE FROM posts WHERE post_id=:post_id');
    $query->bindParam(':post_id', $id);

    $query->execute();

    header('Location: index_blog.php');

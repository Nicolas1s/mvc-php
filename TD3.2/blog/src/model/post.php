<?php

class Post
{
    public $author;
    public $frenchCreationDate;
    public $Comment;
}

function getPosts(string $post)
{
    $database = postDbConnect();
    $statement = $database->prepare(
        "SELECT id, author, comment, DATE_FORMAT(post_date, '%d/%m/%Y à %Hh%imin%ss') AS french_creation_date FROM posts WHERE post_id = ? ORDER BY post_date DESC"
    );
    $statement->execute([$post]);

    $posts = [];
    while (($row = $statement->fetch())) {

        $post = new Post();

        $post->author = $row['author'];
        $post->frenchCreationDate = $row['french_creation_date'];
        $post->comment = $row['comment'];

        $posts[] = $post;
    }

    return $posts;
}

function createPost(string $post, string $author, string $comment)
{
    $database = postDbConnect();
    $statement = $database->prepare(
        'INSERT INTO posts(post_id, author, comment, post_date) VALUES(?, ?, ?, NOW())'
    );
    $affectedLines = $statement->execute([$post, $author, $comment]);

    return ($affectedLines > 0);
}

function postDbConnect()
{
    $database = new PDO('mysql:host=localhost;dbname=db;charset=utf8', 'root', 'root');

    return $database;
}
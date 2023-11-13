<?php

class Post
{
    public $title;
    public $frenchCreationDate;
    public $content;
    public $identifier;
}

class PostRepository
{
    public $database = null;

    public function getPost(string $identifier): Post
        {
            dbConnect($this);
            $statement = $this->database->prepare(
                "SELECT id, title, content,
                DATE_FORMAT(creation_date, '%d/%m/%Y à %Hh%imin%ss')
                AS french_creation_date FROM posts WHERE id = ?"
            );
            $statement->execute([$identifier]);
            
            $row = $statement->fetch();
            $post = new Post();
            $post->title = $row['title'];
            $post->frenchCreationDate = $row['french_creation_date'];
            $post->content = $row['content'];
            $post->identifier = $row['id'];

            return $post;
        }
}
function createPost(string $post, string $author, string $comment, PostRepository $repository)
{
    dbConnect($repository);
    $statement = $repository->database->prepare(
        'INSERT INTO posts(post_id, title, content, post_date) VALUES(?, ?, ?, NOW())'
    );
    $affectedLines = $statement->execute([$post, $author, $comment]);

    return ($affectedLines > 0);
}

function dbConnect(PostRepository $repository)
{
    if ($repository->database === null) {
        $repository->database = new PDO('mysql:host=localhost;
        dbname=db;charset=utf8', 'root', 'root');
    }
}
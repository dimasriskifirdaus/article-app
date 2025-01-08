<?php
require 'database.php';

class Article
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM articles ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function get($id)
    {
    $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC); // Fetch as associative array
    }


    public function create($title, $content)
    {
        $stmt = $this->pdo->prepare("INSERT INTO articles (title, content) VALUES (:title, :content)");
        $stmt->execute(['title' => $title, 'content' => $content]);
    }

    public function getComments($articleId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM comments WHERE article_id = :article_id ORDER BY created_at ASC");
        $stmt->execute(['article_id' => $articleId]);
        return $stmt->fetchAll();
    }

    // public function addComment($articleId, $content)
    // {
    //     $stmt = $this->pdo->prepare("INSERT INTO comments (article_id, content) VALUES (:article_id, :content)");
    //     $stmt->execute(['article_id' => $articleId, 'content' => $content]);
    // }

    public function addComment($articleId, $content, $parentId = null) {
        $stmt = $this->pdo->prepare("
            INSERT INTO comments (article_id, content, parent_id, created_at) 
            VALUES (:article_id, :content, :parent_id, NOW())
        ");
        $stmt->execute([
            'article_id' => $articleId,
            'content' => $content,
            'parent_id' => $parentId,
        ]);
    }
    

    public function getThreadedComments($articleId) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM comments 
            WHERE article_id = :article_id
            ORDER BY parent_id, created_at
        ");
        $stmt->execute(['article_id' => $articleId]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $this->buildCommentTree($comments);
    }
    
    private function buildCommentTree(array $comments, $parentId = null) {
        $branch = [];
        foreach ($comments as $comment) {
            if ($comment['parent_id'] == $parentId) {
                $children = $this->buildCommentTree($comments, $comment['id']);
                if ($children) {
                    $comment['replies'] = $children;
                }
                $branch[] = $comment;
            }
        }
        return $branch;
    }
    
}


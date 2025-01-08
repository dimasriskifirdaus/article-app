<?php
require 'Article.php';
$article = new Article($pdo);
$articles = $article->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Articles</h1>
        <a href="create.php" class="add-article">Add New Article</a>
        <ul class="article-list">
            <?php foreach ($articles as $article): ?>
            <li class="article-item">
                <a href="details.php?id=<?= $article['id'] ?>" class="article-title">
                    <?= htmlspecialchars($article['title']); ?>
                </a>
                <p class="article-content">
                    <?= nl2br(htmlspecialchars(substr($article['content'], 0, 200) . (strlen($article['content']) > 200 ? '...' : ''))); ?>
                </p>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>


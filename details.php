<?php
require 'Article.php';

$articleId = $_GET['id'] ?? null;
if (!$articleId) {
    die('Invalid Article ID.');
}

$article = new Article($pdo);
$articleDetails = $article->get($articleId);
$comments = $article->getComments($articleId);

if (!$articleDetails) {
    die('Invalid Article ID.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentContent = trim($_POST['content'] ?? '');
    $parentId = $_POST['parent_id'] ?? null;

    if (!empty($commentContent)) {
        $article->addComment($articleId, htmlspecialchars($commentContent), $parentId);
        header("Location: details.php?id=$articleId");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($articleDetails['title']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="home-link">← Back to Home</a>
        
        <article class="article-content">
            <h1><?= htmlspecialchars($articleDetails['title'] ?? 'No Title') ?></h1>
            <p><?= nl2br(htmlspecialchars($articleDetails['content'] ?? 'No Content')) ?></p>
        </article>

        <section class="comments-section">
            <h2>Comments</h2>
            <form method="POST" class="comment-form">
                <textarea name="content" required placeholder="Add a comment..."></textarea>
                <button type="submit">Submit Comment</button>
            </form>

            <?php
            function displayComments(array $comments) {
                echo '<ul class="comments-list">';
                foreach ($comments as $comment) {
                    echo '<li class="comment">';
                    echo '<div class="comment-content">' . nl2br(htmlspecialchars($comment['content'])) . '</div>';
                    echo '<small class="comment-timestamp">' . htmlspecialchars($comment['created_at']) . '</small>';
                    echo '<button class="reply-toggle" onclick="toggleReply(' . htmlspecialchars($comment['id']) . ')">Reply</button>';
                    
                    echo '<div id="reply-section-' . htmlspecialchars($comment['id']) . '" class="reply-section" style="display: none;">';
                    echo '<form method="POST" class="comment-form">';
                    echo '<textarea name="content" required placeholder="Write a reply..."></textarea>';
                    echo '<input type="hidden" name="parent_id" value="' . htmlspecialchars($comment['id']) . '">';
                    echo '<button type="submit">Submit Reply</button>';
                    echo '</form>';
                    echo '</div>';

                    if (!empty($comment['replies'])) {
                        echo '<div class="nested-comments">';
                        displayComments($comment['replies']);
                        echo '</div>';
                    }
                    echo '</li>';
                }
                echo '</ul>';
            }
            
            $threadedComments = $article->getThreadedComments($articleId);
            displayComments($threadedComments);
            ?>
        </section>
    </div>

    <script>
    function toggleReply(commentId) {
        const replySection = document.getElementById(`reply-section-${commentId}`);
        replySection.style.display = replySection.style.display === 'none' ? 'block' : 'none';
    }
    </script>
</body>
</html>


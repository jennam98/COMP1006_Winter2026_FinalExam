

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Gallery</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="p-5">
    <h1>Image Gallery</h1>
    <a class="btn btn-primary" href="create.php">Upload New Image</a>
   
    <div class="list-group">
        <?php foreach ($images as $img): ?>
            <div class="list-group-item">
                <h5><?= $row['title'] ?></h5>
                <?php if ($row['filename']): ?>
                    <img src="uploads/<?= $row['filename'] ?>" class="img-fluid mb-2">
                <?php endif; ?>
                <a class="btn btn-primary btn-sm" href="edit.php?id=<?= $row['id'] ?>">Edit</a>
                <a class="btn btn-danger btn-sm" href="delete.php?id=<?= $row['id'] ?>">Delete</a>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <p>No images uploaded yet.</p>
<?php endif; ?>
</body>
</html>
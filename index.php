<?php
session_start();
require 'koneksi.php';

// Cek login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$result = mysqli_query($conn, "
    SELECT movies.*, genres.name AS genre_name 
    FROM movies 
    JOIN genres ON movies.genre_id = genres.id
");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Movie Collection</title>
    <style>
        body {
            margin: 0;
            background-color: #111;
            font-family: Arial, sans-serif;
            color: #fff;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
        }

        h1 {
            color: red;
            margin: 0;
        }

        .top-button a {
            background-color: #e50914;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .search-box {
            padding: 0 20px 10px 20px;
            max-width: 320px;
        }

        #searchInput {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: none;
            font-size: 16px;
        }

        .movie-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            padding: 30px;
        }

        .movie-card {
            background-color: #1c1c1c;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease-in-out;
        }

        .movie-card:hover {
            transform: scale(1.05);
        }

        .movie-card img {
            width: 100%;
            height: 400px;
            object-fit: contain;
            background-color: #000;
        }

        .movie-info {
            padding: 15px;
        }

        .movie-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .movie-genre {
            font-size: 14px;
            color: #ccc;
            margin-bottom: 8px;
        }

        .movie-description {
            font-size: 14px;
            color: #aaa;
            margin-bottom: 8px;
            white-space: pre-wrap;
        }

        .movie-meta {
            font-size: 13px;
            color: #999;
        }

        @media (max-width: 900px) {
            .movie-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .movie-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="top-bar">
        <h1>Movie Collection</h1>
        <div class="top-button">
            <a href="logout.php">Login</a>
        </div>
    </div>

    <div class="search-box">
        <input type="search" id="searchInput" placeholder="Search movies...">
    </div>

    <div class="movie-grid" id="movieGrid">
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <div class="movie-card"
                data-title="<?= strtolower($row['title']); ?>"
                data-description="<?= strtolower($row['description']); ?>"
                data-genre="<?= strtolower($row['genre_name']); ?>">
                <img src="img/<?= htmlspecialchars($row['photo']); ?>" alt="<?= htmlspecialchars($row['title']); ?>">
                <div class="movie-info">
                    <div class="movie-title"><?= htmlspecialchars($row['title']); ?></div>
                    <div class="movie-genre"><?= htmlspecialchars($row['genre_name']); ?></div>
                    <div class="movie-description"><?= nl2br(htmlspecialchars($row['description'])); ?></div>
                    <div class="movie-meta"><?= htmlspecialchars($row['release_year']); ?> | <?= htmlspecialchars($row['duration']); ?> min</div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const movieCards = document.querySelectorAll('.movie-card');

        searchInput.addEventListener('input', function() {
            const keyword = this.value.toLowerCase();
            movieCards.forEach(card => {
                const title = card.dataset.title;
                const desc = card.dataset.description;
                const genre = card.dataset.genre;
                const match = title.includes(keyword) || desc.includes(keyword) || genre.includes(keyword);
                card.style.display = match ? 'block' : 'none';
            });
        });
    </script>
</body>

</html>
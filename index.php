<?php
session_start(); // Memulai session
require 'koneksi.php'; // Menghubungkan ke database

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: index.php"); // Jika belum login, redirect ke index
    exit;
}

// Ambil data film dan genre dari database
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
        /* Gaya dasar halaman */
        body {
            margin: 0;
            background-color: #111;
            font-family: Arial, sans-serif;
            color: #fff;
        }

        /* Bar atas berisi judul dan tombol */
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

        .top-button {
            display: flex;
            gap: 10px;
        }

        .top-button a,
        .top-button button {
            background-color: #e50914;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        /* Kotak input pencarian */
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

        /* Grid film */
        .movie-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            padding: 30px;
        }

        /* Kartu film */
        .movie-card {
            background-color: #1c1c1c;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease-in-out;
        }

        .movie-card:hover {
            transform: scale(1.05);
            /* Efek membesar saat hover */
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
        }

        .movie-meta {
            font-size: 13px;
            color: #999;
        }

        .selengkapnya {
            color: red;
            font-size: 14px;
            cursor: pointer;
            text-decoration: underline;
        }

        .komentar {
            display: none;
            margin-top: 5px;
        }

        /* Responsif untuk ukuran layar kecil */
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

        /* Untuk keperluan cetak PDF */
        @media print {
            .movie-card img {
                height: 150px !important;
                object-fit: cover !important;
            }

            body {
                zoom: 80%;
            }
        }
    </style>
</head>

<body>
    <!-- Bar atas -->
    <div class="top-bar">
        <h1>Movie Collection</h1>
        <div class="top-button">
            <button onclick="downloadPDF()">Download PDF</button> <!-- Tombol unduh PDF -->
            <a href="logout.php">Login</a> <!-- Link logout -->
        </div>
    </div>

    <!-- Input pencarian -->
    <div class="search-box">
        <input type="search" id="searchInput" placeholder="Search movies...">
    </div>

    <!-- Daftar film -->
    <div class="movie-grid" id="movieGrid">
        <?php while ($row = mysqli_fetch_assoc($result)) :
            $deskripsi = $row['description']; // Ambil deskripsi full
            $deskripsi_pendek = substr($deskripsi, 0, 80); // Potong jadi 80 karakter
        ?>
            <!-- Kartu film -->
            <div class="movie-card"
                data-title="<?= strtolower($row['title']); ?>"
                data-description="<?= strtolower($row['description']); ?>"
                data-genre="<?= strtolower($row['genre_name']); ?>">

                <img src="img/<?= htmlspecialchars($row['photo']); ?>" alt="<?= htmlspecialchars($row['title']); ?>">

                <div class="movie-info">
                    <div class="movie-title"><?= htmlspecialchars($row['title']); ?></div>
                    <div class="movie-genre"><?= htmlspecialchars($row['genre_name']); ?></div>

                    <!-- Deskripsi singkat + lengkap -->
                    <div class="movie-description">
                        <span class="short" style="display:none;"><?= htmlspecialchars($deskripsi_pendek) ?>...</span>
                        <span class="full" style="display:none;"><?= nl2br(htmlspecialchars($deskripsi)) ?></span>

                        <div class="selengkapnya">Baca Selengkapnya dan Komentar</div> <!-- Tombol toggle -->

                        <!-- Komentar -->
                        <div class="komentar">
                            <a href="comment.php?id=<?= $row['id']; ?>" style="color:#0af">Tambah Komentar</a>
                        </div>
                    </div>

                    <!-- Tahun rilis & durasi -->
                    <div class="movie-meta"><?= htmlspecialchars($row['release_year']); ?> | <?= htmlspecialchars($row['duration']); ?> min</div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Script untuk pencarian dan toggle deskripsi -->
    <script>
        const searchInput = document.getElementById('searchInput'); // Input pencarian
        const movieCards = document.querySelectorAll('.movie-card'); // Semua kartu film

        // Fitur pencarian live search
        searchInput.addEventListener('input', function() {
            const keyword = this.value.toLowerCase();
            movieCards.forEach(card => {
                const title = card.dataset.title;
                const desc = card.dataset.description;
                const genre = card.dataset.genre;
                const match = title.includes(keyword) || desc.includes(keyword) || genre.includes(keyword);
                card.style.display = match ? 'block' : 'none'; // Tampilkan yang cocok
            });
        });

        // Fitur baca selengkapnya dan komentar
        const tombolSelengkapnya = document.querySelectorAll('.selengkapnya');
        tombolSelengkapnya.forEach(tombol => {
            tombol.addEventListener('click', function() {
                const deskripsiBox = this.parentElement;
                deskripsiBox.querySelector('.short').style.display = 'inline';
                deskripsiBox.querySelector('.full').style.display = 'inline';
                this.style.display = 'none';
                deskripsiBox.querySelector('.komentar').style.display = 'block';
            });
        });
    </script>

    <!-- Script untuk download PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
        function downloadPDF() {
            const element = document.body; // Ambil seluruh body
            const opt = {
                margin: 0.5,
                filename: 'movie-collection.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'in',
                    format: 'a4',
                    orientation: 'landscape'
                }
            };
            html2pdf().set(opt).from(element).save(); // Proses download PDF
        }
    </script>
</body>

</html>
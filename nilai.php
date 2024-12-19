<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Input Nilai Alternatif</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="navbar-container">
        <div class="logo">
          <img src="image/logo.png" alt="SPK"/>
        </div>
          <nav class="nav-list">
            <ul>  
              <li><a href="kriteria.php">Input Kriteria</a></li>
              <li><a href="alternatif.php">Input Alternatif</a></li>
              <li><a href="nilai.php">Input Nilai Alternatif</a></li>
              <li><a href="proses.php">Hitung dan Lihat Hasil</a></li>
            </ul>
          </nav>
          </header>

<section>
    <h2>Input Nilai Alternatif</h2>
    <form method="POST" action="">
        <label>Alternatif:</label>
        <select name="id_alternatif" required>
            <option value="">Pilih Alternatif</option>
            <?php
            $alternatif = $conn->query("SELECT * FROM alternatif");
            while ($alt = $alternatif->fetch_assoc()) {
                echo "<option value='{$alt['id_alternatif']}'>{$alt['nama_alternatif']}</option>";
            }
            ?>
        </select>

        <label>Kriteria:</label>
        <select name="id_kriteria" required>
            <option value="">Pilih Kriteria</option>
            <?php
            $kriteria = $conn->query("SELECT * FROM kriteria");
            while ($krit = $kriteria->fetch_assoc()) {
                echo "<option value='{$krit['id_kriteria']}'>{$krit['nama_kriteria']}</option>";
            }
            ?>
        </select>

        <label>Nilai:</label>
        <input type="number" step="0.01" name="nilai" placeholder="Nilai" required>

        <button type="submit" name="tambah">Tambah</button>
    </form>

    <?php
    // Proses tambah data ke database
    if (isset($_POST['tambah'])) {
        $id_alternatif = $_POST['id_alternatif'] ?? null;
        $id_kriteria = $_POST['id_kriteria'] ?? null;
        $nilai = $_POST['nilai'] ?? null;

        // Pastikan semua nilai telah terisi
        if ($id_alternatif && $id_kriteria && $nilai) {
            // Pastikan alternatif dan kriteria yang dipilih valid
            $cek_alternatif = $conn->query("SELECT id_alternatif FROM alternatif WHERE id_alternatif = $id_alternatif");
            $cek_kriteria = $conn->query("SELECT id_kriteria FROM kriteria WHERE id_kriteria = $id_kriteria");

            if ($cek_alternatif->num_rows > 0 && $cek_kriteria->num_rows > 0) {
                // Insert ke tabel nilai_alternatif jika valid
                $insert = $conn->query("INSERT INTO nilai_alternatif (id_alternatif, id_kriteria, nilai) VALUES ('$id_alternatif', '$id_kriteria', '$nilai')");

                if ($insert) {
                    echo "<p>Data nilai alternatif berhasil ditambahkan.</p>";
                } else {
                    echo "<p>Gagal menambahkan data nilai alternatif.</p>";
                }
            } else {
                echo "<p>Alternatif atau Kriteria tidak valid.</p>";
            }
        } else {
            echo "<p>Semua field harus diisi.</p>";
        }
    }
    ?>
    </section>
</body>
</html>

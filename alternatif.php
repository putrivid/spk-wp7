<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Data Alternatif</title>
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
    <h2>Data Alternatif</h2>
    <form method="POST" action="">
        <input type="text" name="nama_alternatif" placeholder="Nama Alternatif">
        <button type="submit" name="tambah">Tambah</button>
    </form>

    <?php
    if (isset($_POST['tambah'])) {
        $nama = $_POST['nama_alternatif'];
        $conn->query("INSERT INTO alternatif (nama_alternatif) VALUES ('$nama')");
        header("Location: alternatif.php");
    }

    $result = $conn->query("SELECT * FROM alternatif");
    ?>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nama Alternatif</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id_alternatif'] ?></td>
                <td><?= $row['nama_alternatif'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <section>
        
</body>
</html>

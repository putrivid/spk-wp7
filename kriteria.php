<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Data Kriteria</title>
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


    <h2>Data Kriteria</h2>
    <form method="POST" action="">
        <input type="text" name="nama_kriteria" placeholder="Nama Kriteria">
        <input type="number" step="0.01" name="bobot" placeholder="Bobot">
        <select name="tipe">
            <option value="benefit">Benefit</option>
            <option value="cost">Cost</option>
        </select>
        <button type="submit" name="tambah">Tambah</button>
    </form>

    <?php
    if (isset($_POST['tambah'])) {
        $nama = $_POST['nama_kriteria'];
        $bobot = $_POST['bobot'];
        $tipe = $_POST['tipe'];
        $conn->query("INSERT INTO kriteria (nama_kriteria, bobot, tipe) VALUES ('$nama', '$bobot', '$tipe')");
        header("Location: kriteria.php");
    }

    $result = $conn->query("SELECT * FROM kriteria");
    ?>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nama Kriteria</th>
            <th>Bobot</th>
            <th>Tipe</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id_kriteria'] ?></td>
                <td><?= $row['nama_kriteria'] ?></td>
                <td><?= $row['bobot'] ?></td>
                <td><?= $row['tipe'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
        </section>
</body>
</html>

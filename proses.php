<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hasil Perhitungan Weighted Product</title>
    <link rel="stylesheet" href="styles.css">
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
    <br><br>
    <h2>Hasil Perhitungan Weighted Product - Langkah Per Langkah</h2>

    <?php
    // 1. Ambil Data Kriteria dan Hitung Total Bobot
    echo "<h3>Langkah 1: Ambil Data Kriteria dan Hitung Total Bobot</h3>";
    $kriteria = $conn->query("SELECT * FROM kriteria");
    $total_bobot = 0;
    while ($row = $kriteria->fetch_assoc()) {
        $total_bobot += $row['bobot'];
        echo "Kriteria: {$row['nama_kriteria']}, Bobot: {$row['bobot']}<br>";
    }
    echo "Total Bobot: $total_bobot<br><br>";

    // 2. Normalisasi Bobot
    echo "<h3>Langkah 2: Normalisasi Bobot</h3>";
    $bobot_normal = [];
    $kriteria = $conn->query("SELECT * FROM kriteria");
    while ($row = $kriteria->fetch_assoc()) {
        $bobot_normal[$row['id_kriteria']] = $row['bobot'] / $total_bobot;
        echo "Kriteria: {$row['nama_kriteria']}, Bobot Normalisasi: {$bobot_normal[$row['id_kriteria']]}<br>";
    }
    echo "<br>";

    // 3. Hitung Nilai S (Vektor S) untuk setiap Alternatif
    echo "<h3>Langkah 3: Hitung Nilai S (Vektor S) untuk setiap Alternatif</h3>";
    $alternatif = $conn->query("SELECT * FROM alternatif");
    $s_values = [];

    while ($alt = $alternatif->fetch_assoc()) {
        $s_value = 1; // Inisialisasi S = 1
        $id_alternatif = $alt['id_alternatif'];
        echo "<strong>Alternatif: {$alt['nama_alternatif']}</strong><br>";

        $nilai_alternatif = $conn->query("SELECT * FROM nilai_alternatif WHERE id_alternatif = $id_alternatif");
        while ($nilai = $nilai_alternatif->fetch_assoc()) {
            $id_kriteria = $nilai['id_kriteria'];
            $tipe = $conn->query("SELECT tipe FROM kriteria WHERE id_kriteria = $id_kriteria")->fetch_assoc()['tipe'];
            $bobot = $bobot_normal[$id_kriteria];

            if ($tipe == 'benefit') {
                $s_value *= pow($nilai['nilai'], $bobot);
                echo "Nilai: {$nilai['nilai']} (Benefit) ^ $bobot = " . pow($nilai['nilai'], $bobot) . "<br>";
            } else { // cost
                $s_value *= pow($nilai['nilai'], -$bobot);
                echo "Nilai: {$nilai['nilai']} (Cost) ^ -$bobot = " . pow($nilai['nilai'], -$bobot) . "<br>";
            }
        }

        $s_values[$id_alternatif] = $s_value;
        echo "Hasil S untuk {$alt['nama_alternatif']}: $s_value<br><br>";
    }

    // 4. Hitung Nilai V (Preferensi) untuk setiap Alternatif
    echo "<h3>Langkah 4: Hitung Nilai V (Preferensi) untuk setiap Alternatif</h3>";
    $total_s = array_sum($s_values);
    $v_values = [];

    foreach ($s_values as $id_alternatif => $s_value) {
        $v_values[$id_alternatif] = $s_value / $total_s;
        $nama_alternatif = $conn->query("SELECT nama_alternatif FROM alternatif WHERE id_alternatif = $id_alternatif")->fetch_assoc()['nama_alternatif'];
        echo "Preferensi V untuk $nama_alternatif: $s_value / $total_s = " . round($v_values[$id_alternatif], 4) . "<br>";
    }
    echo "<br>";

    // 5. Urutkan Nilai V secara Descending
    echo "<h3>Langkah 5: Urutkan Nilai V (Preferensi) secara Descending</h3>";
    arsort($v_values);

    // 6. Tampilkan Hasil Akhir
    echo "<h3>Hasil Akhir</h3>";
    echo "<table border='1'>";
    echo "<tr><th>Alternatif</th><th>Nilai Preferensi (V)</th></tr>";

    foreach ($v_values as $id_alternatif => $v) {
        $nama_alternatif = $conn->query("SELECT nama_alternatif FROM alternatif WHERE id_alternatif = $id_alternatif")->fetch_assoc()['nama_alternatif'];
        echo "<tr><td>$nama_alternatif</td><td>" . round($v, 4) . "</td></tr>";
    }

    echo "</table>";
    ?>
    </section>
</body>
</html>

<?php

    // Oturumu başlat
    session_start();
    
    // Veritabanı ayarları
    include 'ayar.php';
    include 'ukas.php';

    // Fonksiyonlar
    include 'func.php';

    if (@$_SESSION["uye_onay"] != 1) {
        // Admin değilse

        echo '<center><h1>Sadece yöneticiler görebilir</h1><center>';

        // Alttaki kodlar gizlensin/çalışmasın
        exit;
    }

?>

<center>
    <?php include 'header.php'; // Header / Üst bilgi ?>
    <br><br>
    <h2>Admin Paneli</h2>
    <hr>
    <h3>Kategori Ekle</h3>
    <?php
    
        if ($_POST) {
            $kategori = $_POST["kategori"];
            $kategoriLink = permalink($kategori);

            // Veritabanına kategori ekle
            $dataAdd = $db -> prepare("INSERT INTO kategoriler SET
                k_kategori=?,
                k_kategori_link=?
            ");
            $dataAdd -> execute([
                $kategori,
                $kategoriLink
            ]);

            if ( $dataAdd ) {
                echo '<p class="alert alert-success">Kategori başarıyla eklendi. :)</p>';
                
                header("REFRESH:1;URL=admin.php");
            } else {
                echo '<p class="alert alert-danger">Hay aksi bir sorunla karşılaştık, lütfen tekrar deneyiniz. :/</p>';
                
                header("REFRESH:1;URL=admin.php");
            }
        }
    
    ?>
    <form action="" method="post">
        <strong>Kategori:</strong>
        <input type="text" name="kategori">
        <br>
        <input type="submit" value="Kategori Oluştur">
    </form>

    <hr>

    <ol>
        <?php
        
            $dataList = $db -> prepare("SELECT * FROM kategoriler");
            $dataList -> execute();
            $dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);
            
            foreach($dataList as $row){
                echo '<li><a href="kategori.php?q='.$row["k_kategori_link"].'">'.$row["k_kategori"].'</a></li>';
            }
        
        ?>
    </ol>
</center>
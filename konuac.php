<?php

    session_start();
    
    include 'ayar.php';

    include 'ukas.php';

    include 'func.php';

    if (!@$_SESSION["uye_id"]) {

        echo '<center><h1>// Konu paylaşabilmek için <a href="uyelik.php">giriş yap</a>ın yada <a href="uyelik.php?q=kayit">kayıt ol</a>un.</h1></center>';
        exit;
    }

    // Kategori linki
    $kategori = @$_GET["kategori"];

?>

<center>
<?php include 'header.php'; // Header / Üst bilgi ?>
    <br><br>
    <h2>Konu Paylaşma</h2>
    <?php
    
        if ($_POST) {
            $ad     = $_POST["ad"];
            $mesaj  = $_POST["mesaj"];

            $link   = permalink($ad) . "-" . rand(000,999);

            $dataAdd = $db -> prepare("INSERT INTO konular SET
                konu_ad=?,
                konu_link=?,
                konu_mesaj=?,
                konu_uye_id=?,
                konu_kategori_link=?
            ");
            $dataAdd -> execute([
                $ad,
                $link,
                $mesaj,
                @$_SESSION["uye_id"],
                $kategori
            ]);

            if ( $dataAdd ) {
                echo '<p class="alert alert-success">Başarıyla konunuz paylaşıldı. :)</p>';
                
                header("REFRESH:1;URL=konu.php?link=" . $link);
            } else {
                echo '<p class="alert alert-danger">Hay aksi bir hata ile karşılaştık, lütfen tekrar deneyiniz. :/</p>';
                
                header("REFRESH:1;URL=konuac.php");
            }
        }
    
    ?>
    <strong><?=kategori_linkten_kategori_adi($kategori)?> Kategorisinde Konu Açmaktasınız:</strong>
    <form action="" method="post">
        <strong>Konu Adı:</strong>
        <input type="text" name="ad"><br>
        <strong>Konu Mesajı:</strong><br>
        <textarea name="mesaj" cols="30" rows="10"></textarea>
        <br>
        <input type="submit" value="Konuyu Aç">
    </form>
    
</center>
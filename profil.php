<?php

    // Oturumu başlat
    session_start();
    
    // Veritabanı ayarları
    include 'ayar.php';

    include 'ukas.php';

    // Fonksiyonlar
    include 'func.php';

    // Kullanıcı adı
    $kadi = @$_GET["kadi"];

    // Üye bilgilerini çek
    $data = $db -> prepare("SELECT * FROM uyeler WHERE
        uye_kadi=?
    ");
    $data -> execute([
        $kadi
    ]);
    $_data = $data -> fetch(PDO::FETCH_ASSOC);

?>

<center>
<?php include 'header.php'; // Header / Üst bilgi ?>
    <br><br>
    <h2><?=$_data["uye_adsoyad"]?></h2>
    <strong>Eposta:</strong> <?=$_data["uye_eposta"]?>
    <hr>
    <table border="1" width="100%">
        <tr>
            <td>
                <strong>Açtığı Konular:</strong>
                <ul>
                    <?php
                    
                        $dataList = $db -> prepare("SELECT * FROM konular WHERE konu_uye_id=?");
                        $dataList -> execute([
                            $_data["uye_id"]
                        ]);
                        $dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);
                        
                        foreach($dataList as $row){
                            echo '<li><a href="konu.php?link='.$row["konu_link"].'">'.$row["konu_ad"].'</a></li>';
                        }
                    
                    ?>
                </ul>
            </td>
            <td>
                <strong>Yorumlar:</strong>
                <ul>
                    <?php
                        
                        $dataList = $db -> prepare("SELECT * FROM yorumlar WHERE y_uye_id=? LIMIT 50");
                        $dataList -> execute([
                            $_data["uye_id"]
                        ]);
                        $dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);
                        
                        // Konu ID'ler diye dizi oluşturdum
                        $konu_idler = [];

                        foreach($dataList as $row){
                            // Konu ID'lere eleman / ID ekliyorum
                            array_push( $konu_idler, $row["y_konu_id"] );

                        }

                        //Konuları çekiyor
                        $konu_idler = array_unique( $konu_idler );

                        foreach ($konu_idler as $konuid) {
                            $konu_cek = $db -> prepare("SELECT * FROM konular WHERE
                                konu_id=?
                            ");
                            $konu_cek -> execute([
                                $konuid
                            ]);
                            $_konu_cek = $konu_cek -> fetch(PDO::FETCH_ASSOC);

                            echo '<li><a href="konu.php?link='.$_konu_cek["konu_link"].'">'.$_konu_cek["konu_ad"].'</a></li>';

                            // 10'dan fazla olunca döngüyü durdur.
                            @$i++;
                            if ($i == 10) {
                                break;
                            }
                        }
                    ?>
                </ul>
            </td>
        </tr>
    </table>
</center>


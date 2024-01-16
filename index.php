<?php

    // Oturumu başlat
    session_start();
    
    include 'ayar.php';

    include 'ukas.php';

    // Fonksiyonlar
    include 'func.php';

?>

<center>
<?php include 'header.php'; // Header / Üst bilgi ?>
    <br><br>
    <table border="1">
        <tr>
            <td>
                <strong>Yeni Açılan Konular:</strong><hr>
                <ul>
                    <?php
                        $dataList = $db -> prepare("SELECT * FROM konular ORDER BY konu_id DESC LIMIT 10");
                        $dataList -> execute();
                        $dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);
                        
                        foreach($dataList as $row){
                            echo '<li><a href="konu.php?link='.$row["konu_link"].'">'.$row["konu_ad"].'</a></li>';
                        }
                    ?>
                    
                </ul>
            </td>
            <td>
                <strong>Son Cevaplar:</strong><hr>
                <ul>
                    <?php
                        $dataList = $db -> prepare("SELECT * FROM yorumlar ORDER BY y_id DESC LIMIT 50");
                        $dataList -> execute();
                        $dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);
                        
                        // Konu ID'ler diye dizi oluşturdum
                        $konu_idler = [];

                        foreach($dataList as $row){
                            // Konu ID'lere eleman / ID ekliyorum
                            array_push( $konu_idler, $row["y_konu_id"] );

                        }

                        // Aynı ID'leri sil / bir defa göster / benzersiz liste oluştur
                        $konu_idler = array_unique( $konu_idler );

                        foreach ($konu_idler as $konuid) {
                            $konu_cek = $db -> prepare("SELECT * FROM konular WHERE
                                konu_id=?
                            ");
                            $konu_cek -> execute([
                                $konuid
                            ]);
                            $_konu_cek = $konu_cek -> fetch(PDO::FETCH_ASSOC);

                            $konu_idler = array_unique($konu_idler);

                            $i = 0; // $i değişkenini başlat
                            foreach ($konu_idler as $konuid) {
                                $konu_cek = $db->prepare("SELECT * FROM konular WHERE konu_id=?");
                                $konu_cek->execute([$konuid]);
                                
                                // fetch ile sonuçları al
                                $_konu_cek = $konu_cek->fetch(PDO::FETCH_ASSOC);
                            
                                // $_konu_cek dizisi boş değilse işlemleri gerçekleştir
                                if ($_konu_cek !== false) {
                                    echo '<li><a href="konu.php?link=' . $_konu_cek["konu_link"] . '">' . $_konu_cek["konu_ad"] . '</a></li>';
                                    
                                    $i++;
                                    if ($i == 10) {
                                        break;
                                    }
                                } else {
                                    // $_konu_cek boşsa, bir hata durumu olabilir, gerekirse işlemleri buraya ekleyebilirsiniz.
                                    echo '<li>Hata: Konu bulunamadı.</li>';
                                }
                            }
                            
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

        <tr>
            <td colspan="2">
                <h2>Kategoriler:</h2>

                <ul>
                    <?php
                        $dataList = $db -> prepare("SELECT * FROM kategoriler LIMIT 10");
                        $dataList -> execute();
                        $dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);
                        
                        foreach($dataList as $row){
                            echo '<li><a href="kategori.php?q='.$row["k_kategori_link"].'">'.$row["k_kategori"].'</a></li>';
                        }
                    ?>
                </ul>
            </td>
        </tr>
    </table>
</center>
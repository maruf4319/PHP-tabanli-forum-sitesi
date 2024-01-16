<a href="index.php"><h1>BirdMark Forum</h1></a>

<?php

    if (@$_SESSION["uye_id"]) {
        # Sadece üyeler görsün

        echo '<a href="profil.php?kadi='.@$_SESSION["uye_kadi"].'">Profilime Git</a>
        <a href="uyelik.php?p=cikis">Çıkış Yap</a>';
    } else {
        # Sadece üye olmayanlar görsün

        echo '<a href="uyelik.php?p=kayit">Üye Ol</a> yada <a href="uyelik.php">Giriş Yap</a>';
    }

?>
<?php

    session_start();

    require_once "./data.php";

    //zpracujeme přihlašovací formulář
    if (array_key_exists("login-submit", $_POST)){
        $zadanyUsername = $_POST["username"];
        $zadaneHeslo = $_POST["password"];

        //pustíme příkaz, který vyhledá řádek podle username
        $prikaz = $instanceDb->prepare("SELECT * FROM spravce WHERE username = ?");
        $prikaz->execute([$zadanyUsername]);
        $spravce = $prikaz->fetch();

        if ($spravce) {

            //kontrolujeme přihlašovací údaje
            if ($zadanyUsername == $spravce["username"] && $zadaneHeslo == $spravce["heslo"]) {
                //pokud OK, do session klíč "prihlasen"
                $_SESSION["prihlasen"] = true;
            }

        }

    }

    //zoracujem logout
    if (array_key_exists("logout-submit", $_GET)) {
        unset($_SESSION["prihlasen"]);
        //vycistit url
        header("Location: ?");
    }

    if (array_key_exists("prihlasen", $_SESSION)) {
        //toto je blok kodu ktery se provede jen kdyz je uzivatel prihlasen

        //uzivatel chce editovat stranku
        if (array_key_exists("edit", $_GET)) {
            //vytahneme si z URL id stranky
            $idStranky = $_GET["edit"];
            //podle id najdeme v posli $seznamStranek nasi isnatnci
            $aktualniInstance = $seznamStranek[$idStranky];
        }
        
        //uzivatel chce zacit editovbat novou stranku
        if (array_key_exists("pridat", $_GET)) {
            $aktualniInstance = new Stranka("", "", "", "");
        }

        //uzivatel chce aktualizovat
        if (array_key_exists("aktualizovat-submit", $_POST)) {
            //vathneme si data z formulare
            $idStranky = trim($_POST["id-stranky"]); //odstranime z id prebytecne mezery
            $titulekStranky = $_POST["titulek-stranky"];
            $menuStranky = $_POST["menu-stranky"];
            $obrazekStranky = $_POST["obrazek-stranky"];

            //musime zkontorlovat jestli id neni prazdne
            if ($idStranky != "") {
                //nastavime instanci nova data
                $aktualniInstance->setId($idStranky);
                $aktualniInstance->setTitulek($titulekStranky);
                $aktualniInstance->setMenu($menuStranky);
                $aktualniInstance->setObrazek($obrazekStranky);
                //propisme instanci do DB
                $aktualniInstance->ulozDoDb();

                //k obsahu stránky se chováme jinak z výkonnostních důvodů
                //(nechceme načítat všechny obsahy stránek ale jen ten jeden editovaný)
                $novyObsahStranky = $_POST["obsah-stranky"];
                $aktualniInstance->setObsah($novyObsahStranky);

                //refreshneme stranku aby v url bylo nove id
                header("Location: ?edit=$idStranky");
            }
        }

        //uživatel chce smazat stránku
        if (array_key_exists("smazat", $_GET)) {
            $idStrankyKeSmazani = $_GET["smazat"];
            $seznamStranek["$idStrankyKeSmazani"]->smazSe();

            header("Location: ?");
        }

        //uživatel chce seřadit stránky, POZOR posíláme AJAXem jako POST!!!
        if (array_key_exists("poradiSubmit", $_POST)) {
            $poleSerazenychId = $_POST["poleId"];
            //zavoláme statickou funkci classy stránka
            Stranka::aktualizujPoradiDb($poleSerazenychId);
            //ukoncime script, jinak by generoval celou stránku znovu
            exit;
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrace</title>
</head>
<body>
    <h1>Administrace</h1>

    <?php
        if (array_key_exists("prihlasen", $_SESSION)) {
            echo "Jste přihlášen<br>";
            echo "<a href='?logout-submit=yes'>Odhlasit se</a>";

            echo "<ul id = 'sova'>";
                foreach($seznamStranek as $instance) {
                    echo "<li id = '{$instance->getId()}'>
                    <a href = '?edit={$instance->getId()}'>{$instance->getId()}</a>
                    <a class = 'tukan' href = '?smazat={$instance->getId()}'>[SMAZAT]</a>
                    </li>";
                }
            echo "</ul>";

            echo "<a href = '?pridat=yes'>Přidat novou stránku</a>";

            echo "<br><br>";

            if (isset($aktualniInstance)) {
                ?>
    
                <form action="" method="post">
                    <label for="a">ID:</label>
                    <input type="text" name="id-stranky" id="a" value = "<?php echo $aktualniInstance->getId();?>">
                    <label for="b">Titulek:</label>
                    <input type="text" name="titulek-stranky" id="b" value = "<?php echo $aktualniInstance->getTitulek();?>">
                    <label for="c">Menu:</label>
                    <input type="text" name="menu-stranky" id="c" value = "<?php echo $aktualniInstance->getMenu();?>">
                    <label for="d">Obrázek:</label>
                    <input type="text" name="obrazek-stranky" id="d" value = "<?php echo $aktualniInstance->getObrazek();?>">
                    <br><br>
                    <label for="hroch">WYSIWYG editor</label>
                    <br>
                    <textarea name="obsah-stranky" id="hroch" cols="100" rows="20"><?php echo htmlspecialchars($aktualniInstance->getObsah()); ?></textarea>
                    <br>
                    <input type="submit" name="aktualizovat-submit" value="Aktualizovat">
                </form>
                <br>
    
                <?php
            }
        } else {
            ?>
                <form action="" method="post">
                    <label for="a">Jmeno</label>
                    <input type="text" name="username" id="a">
                    <label for="b">Heslo</label>
                    <input type="password" name="password" id="b">

                    <input type="submit" name="login-submit" value="Prihlasit se">
                </form>
            <?php
        }           
    ?>

    <!-- <script>debugger;</script> -->

    <!-- composer require primakurzy/shortcode -->

    <!-- composer require components/jquery -->
    <!-- composer require components/jqueryui -->
    <!-- nutné dodržet toto pořadí -->
    <script src="./vendor/components/jquery/jquery.min.js"></script>
    <script src="./vendor/components/jqueryui/jquery-ui.min.js"></script>

    <!-- composer require tinymce/tinymce 5.10.6 -->
    <!-- composer require primakurzy/responsivefilemanager -->
    <!-- zkopírovat složku upload do prima-penzion -->

    <!-- zde jsme připojili knihovnu tinymce -->
    <script src="./vendor/tinymce/tinymce/tinymce.js"></script>
    <!-- nyní musíme knihovnu tinymce spustit -->
    <script>
        tinymce.init({
            selector: "#hroch",
            language: "cs",
            language_url: "<?php echo dirname($_SERVER["PHP_SELF"]); ?>/vendor",
            entity_encoding: "raw",
            verify_html: false,
            content_css: ["./css/style.css", "./css/all.min.css"],
            body_id: "obsah",
            plugins:["code", "responsivefilemanager", "image", "anchor", "autolink", "autoresize", "link", "media", "lists"],
            toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
            toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
            external_plugins: {
                'responsivefilemanager': '<?php echo dirname($_SERVER['PHP_SELF']); ?>/vendor/primakurzy/responsivefilemanager/tinymce/plugins/responsivefilemanager/plugin.min.js',
            },
            external_filemanager_path: "<?php echo dirname($_SERVER['PHP_SELF']); ?>/vendor/primakurzy/responsivefilemanager/filemanager/",
            filemanager_title: "File manager",
        });
    </script>

    <script src="./js/main-admin.js"></script>

</body>
</html>
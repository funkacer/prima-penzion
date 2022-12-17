<?php

    require_once "./data.php";
    require_once "./vendor/autoload.php";

    //http://localhost/jirka/prima-penzion/index.php?stranka=galerie

    //zjistíme zda v url je parametr stránka, jinak dáme "domu"
    if (array_key_exists("stranka", $_GET)) {
        $idStranky = $_GET["stranka"];
        if (!array_key_exists($idStranky, $seznamStranek)) {
            $idStranky = "404";
        }
    } else {
        $idStranky = array_keys($seznamStranek)[0];
        //zahardcodované nechceme
        //$idStranky = "domu";
    }

?>


<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $seznamStranek[$idStranky]->getTitulek();?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
</head>

<body>
    
    <header>

        <div class="container">
            <div class="headerTop">
                <a href="tel:+420606123456">+420 / 606 123 456</a>
                <div class="socIkony">
                    <a href="#" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" target="_blank"><i class="fa-brands fa-twitter"></i></a>
                </div>
            </div>

            <a href="index.php" class="logo"><p>Prima</p> <p>Penzion</p></a>
        
            <div class="menu">
                <ul>
                    <?php
                        foreach ($seznamStranek as $klicStranky => $infoStranky) {
                            //echo "<li> <a href='?stranka=$klicStranky'>{$infoStranky['menu']}</a> </li>";
                            //prettyURL
                            if ($infoStranky->getMenu() != "") {
                                //echo "<li> <a href='$klicStranky'>{$infoStranky['menu']}</a> </li>";
                                echo "<li> <a href='$klicStranky'>{$infoStranky->getMenu()}</a> </li>";
                            }
                        }
                    ?>
                </ul>
            </div>

        </div>

        <img src="<?php echo "./img/{$seznamStranek[$idStranky]->getObrazek()}";?>" alt="PrimaPenzion">

    </header>

    <!-- zde se bude dynamicky generovat obsah stránky -->

    <?php
        echo primakurzy\Shortcode\Processor::process("./moje-shortcody" ,$seznamStranek[$idStranky]->getObsah());
        //toto bylo pred shortcody
        //echo $seznamStranek[$idStranky]->getObsah();
        //toto bylo ve verzi HTML
        //echo file_get_contents("./$idStranky.html");
    ?>

    <footer>

        <div class="pata">

            <div class="menu">
                <ul>
                    <?php
                        foreach ($seznamStranek as $klicStranky => $infoStranky) {
                            //echo "<li> <a href='?stranka=$klicStranky'>{$infoStranky['menu']}</a> </li>";
                            //prettyURL
                            if ($infoStranky->getMenu() != "") {
                                //echo "<li> <a href='$klicStranky'>{$infoStranky['menu']}</a> </li>";
                                echo "<li> <a href='$klicStranky'>{$infoStranky->getMenu()}</a> </li>";
                            }
                        }
                    ?>
                </ul>
            </div>


            <a href="index.php" class="logo"><p>Prima</p> <p>Penzion</p></a>
  
            <div class="pataRef">
                <p>
                    <a href="https://goo.gl/maps/v7jJRdJNbVdFPikH8" target="_blank"><i class="fa-solid fa-globe"></i> <strong>PrimaPenzion</strong>, Jablonského 2, Praha 7</a>
                </p>
                <p>
                    <a href="tel:+420606123456"><i class="fa-solid fa-phone"></i> +420 / 606 123 456</a>
                </p>
                <p class="strongRef">
                    <a href="mailto:info@primapenzion.cz"><i class="fa-solid fa-envelope"></i> <b>info@primapenzion.cz</b></a>
                </p>
                
            </div>

            <div class="socIkony">
                <a href="#" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                <a href="#" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" target="_blank"><i class="fa-brands fa-twitter"></i></a>
            </div>

        </div>

        <a href="#" class="btn"><i class="fa-solid fa-angles-up"></i></a>

        <div class="copy">

            <div class="container">

                <p>&copy;Copyright 2022 <b>PrimaPenzion</b> / <a href="#"> Zásady ochrany osobních údajů</a></p>

                <p><a href="#" target="_blank">Jirka</a></p>

            </div>

        </div>

    </footer>
    
</body>
</html>
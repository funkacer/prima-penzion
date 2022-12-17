<?php

    $instanceDb = new PDO(
        "mysql:host=uvdb65.active24.cz;dbname=funkacerpenzion;charset=utf8",
        "funkacerpenzion",
        "1bdeZDB8JN",
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );

    class Stranka {
        protected $id;
        protected $titulek;
        protected $menu;
        protected $obrazek;
        protected $oldId = ""; //nastaví se zde nebo v konstruktoru na ""

        public function __construct($argId, $argTitulek, $argMenu, $argObrazek)
        {
            $this->id = $argId;
            $this->titulek = $argTitulek;
            $this->menu = $argMenu;
            $this->obrazek = $argObrazek;
        }

        //toto je statická funkce
        static public function aktualizujPoradiDb ($argPoleId) {
            //pro každé id zaktualizujeme záznam v db
            foreach($argPoleId AS $klic => $id) {
                $prikaz = $GLOBALS["instanceDb"]->prepare("UPDATE stranka SET poradi=? WHERE id=?");
                $prikaz->execute([$klic, $id]);
            }
        }

        public function getId () {
            return $this->id;
        }

        public function getTitulek () {
            return $this->titulek;
        }

        public function getMenu () {
            return $this->menu;
        }

        public function getObrazek () {
            return $this->obrazek;
        }

        public function getObsah () {

            //nelze, jsem uvnitř class
            //$instanceDb->prepare("SELECT * FROM stranka WHERE id = ?");
            //použijeme $GLOBALS["instanceDb"]
            $prikaz = $GLOBALS["instanceDb"]->prepare("SELECT * FROM stranka WHERE id = ?");
            $prikaz->execute([$this->id]);
            $stranka = $prikaz->fetch();
            if ($stranka) {
                return $stranka["obsah"];
            } else {
                //stránka ještě neexistuje, třeba když přidávám novou stránku
                return "";
            }
            

            /* bylo pres HTML
            return file_get_contents("./".$this->id.".html");
            */
        }

        public function setId ($argNoveId) {
            $this->oldId = $this->id;
            $this->id = $argNoveId;
        }

        public function setTitulek ($argNovyTitulek) {
            $this->titulek = $argNovyTitulek;
        }

        public function setMenu ($argNoveMenu) {
            $this->menu = $argNoveMenu;
        }

        public function setObrazek ($argNovyObrazek) {
            $this->obrazek = $argNovyObrazek;
        }

        public function ulozDoDb () {
            if ($this->oldId != "") {
                $prikaz = $GLOBALS["instanceDb"]->prepare("UPDATE stranka SET id = ?, titulek = ?, menu = ?, obrazek = ? WHERE id = ?");
                $prikaz->execute([$this->id, $this->titulek, $this->menu, $this->obrazek, $this->oldId]);
            } else {
                //nejprve zjistíme nejvyšší hodnotu v dtb
                $prikaz = $GLOBALS["instanceDb"]->prepare("SELECT MAX(poradi) AS max_poradi FROM stranka");
                $prikaz->execute();
                $vysledek = $prikaz->fetch();
                $maxPoradi = $vysledek["max_poradi"];
                $maxPoradi++;

                $prikaz = $GLOBALS["instanceDb"]->prepare("INSERT INTO stranka SET id = ?, titulek = ?, menu = ?, obrazek = ?, poradi = ?");
                $prikaz->execute([$this->id, $this->titulek, $this->menu, $this->obrazek, $maxPoradi]);
            }
            
            
        }

        public function setObsah($argNovyObsah) {
            $prikaz = $GLOBALS["instanceDb"]->prepare("UPDATE stranka SET obsah = ? WHERE id = ?");
            $prikaz->execute([$argNovyObsah, $this->id]);

            /* bylo pres HTML
            file_put_contents("./".$this->id.".html", $argNovyObsah);
            */
        }

        public function smazSe () {
            $prikaz =  $GLOBALS["instanceDb"]->prepare("DELETE FROM stranka WHERE id = ?");
            $prikaz->execute([$this->id]);
        }

    }

    $seznamStranek = [];
    //připojíme se do databáze a vytáhneme všechny stránky
    $prikaz = $instanceDb->prepare("SELECT * from stranka ORDER BY poradi ASC, id ASC");
    $prikaz->execute();
    //fetchall
    $poleStranek = $prikaz->fetchAll();

    //pro každou stránku vytvoříme instanci
    foreach ($poleStranek as $stranka) {
        $seznamStranek[$stranka["id"]] = new Stranka($stranka["id"], $stranka["titulek"], $stranka["menu"], $stranka["obrazek"]);
    }

    /*
    $seznamStranek = [
        "domu" => new Stranka("domu","Prima Penzion","Domů","primapenzion-main.jpg"),
        "kontakt" => new Stranka("kontakt","Kontakt","Napište nám","primapenzion-pool-min.jpg"),
        "rezervace" => new Stranka("rezervace","Rezervace","Chci pokoj","primapenzion-room.jpg"),
        "galerie" => new Stranka("galerie","Galerie","Fotky","primapenzion-room2.jpg"),
        "404" => new Stranka("404","Chyba 404","","primapenzion-main.jpg")];
    /*

    /*
    $seznamStranek = [
        "domu" => [
            "id" => "domu",
            "titulek" => "Prima Penzion",
            "menu" => "Domů",
            "obrazek" => "primapenzion-main.jpg"
        ],
        "kontakt" => [
            "id" => "kontakt",
            "titulek" => "Kontakt",
            "menu" => "Napište nám",
            "obrazek" => "primapenzion-pool-min.jpg"
        ],
        "rezervace" => [
            "id" => "rezervace",
            "titulek" => "Rezervace",
            "menu" => "Chci pokoj",
            "obrazek" => "primapenzion-room.jpg"
        ],
        "galerie" => [
            "id" => "galerie",
            "titulek" => "Galerie",
            "menu" => "Fotky",
            "obrazek" => "primapenzion-room2.jpg"
        ],
        "404" => [
            "id" => "404",
            "titulek" => "Chyba 404",
            "menu" => "",
            "obrazek" => "primapenzion-main.jpg"
        ]
    ];

    */


?>
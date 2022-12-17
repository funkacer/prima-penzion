//při kliknutí na odkaz chceme nejprve získat svolení

//nejprve zaměřit všechny odkazy smazat
let poleOdkazuSmazat = document.querySelectorAll(".tukan");

//musíme každý odkaz deaktivovat
for (let odkaz of poleOdkazuSmazat) {
    odkaz.addEventListener("click", (e) => {
        //vypnuli jsme přesměrování na ?smazat=$instance v php odkazech simulujících getovský formulář
        e.preventDefault();
        //confirm je dialogové okno pro potvrzení nebo zrušení
        //vrací boolean (true = OK, false = Cancel)
        let souhlas = confirm("Opravdu chcete smazat stránku?");
        if (souhlas == true) {
            //musíme zjistit, kam původní odkaz směřoval
            let cilOdkazu = odkaz.getAttribute("href");
            //přesměrujeme uživatele
            window.location.href = cilOdkazu;
        }
    })
}


//budeme potřebovat knihovny jquery a jqueryui
//composer require components/jquery
//composer require components/jqueryui

//přetransformovat náš ul seznam stránek na softable
//zaměříme sovu
$("#sova").sortable({
    update: () => {
        //toto vrátí pole id <li> v aktuálním seřazení
        $poleSerazenychStranek =  $("#sova").sortable("toArray");
        console.log($poleSerazenychStranek);

        //nyní provedem AJAX
        $.ajax({
            type: "POST",
            url: "admin.php",
            data: {
                poradiSubmit: true,
                poleId: $poleSerazenychStranek},
            dataType: "json", //můžeme smazat
            success: () => {
                //zde nemusíme dělat nic, můžeme smazat
            }
        });

    }
});

$(document).ready(function () {

    let colorGray = "#6c757d"

    let btnFormations = document.getElementById('btn-formations');
    let btnFormationsEnCours = document.getElementById('btn-formations-en-cours');
    let btnFormationsTerminée = document.getElementById('btn-formations-terminées');

    let formations = document.getElementById('formations');
    let formationsEnCours = document.getElementById('formationsEnCours')
 //   let formationsTerminée = document.getElementById('formationsTerminée')

    $("#search").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $(".card").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    btnFormations.addEventListener('click' , () => {
        formations.style.display = "block";
        btnFormations.style.backgroundColor = colorGray;
        btnFormations.style.color = "white";

        formationsEnCours.style.display = "none";
        btnFormationsEnCours.style.backgroundColor = "white"
        btnFormationsEnCours.style.color = colorGray

        //  formationsTerminée.style.display = "none";
        //  btnFormationsTerminée.style.backgroundColor = "white"
        //  btnFormationsTerminée.style.color = colorGray
    })



    btnFormationsEnCours.addEventListener('click' , () => {
        formationsEnCours.style.display = "block";
        btnFormationsEnCours.style.backgroundColor = colorGray
        btnFormationsEnCours.style.color = "white"

        formations.style.display = "none";
        btnFormations.style.backgroundColor = "white"
        btnFormations.style.color = colorGray


        //   formationsTerminée.style.display = "none";
        //  btnFormationsTerminée.style.backgroundColor = "white"
        //  btnFormationsTerminée.style.color = colorGray
   })





});
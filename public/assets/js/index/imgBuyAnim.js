// Récupérez l'élément bouton i
$(".btn_main_shop").on("click", function (e) {
    e.preventDefault();

    // Récupérez l'URL de l'image à partir de la carte de produit
    const imageUrl = $(this).closest(".card_crochet_ctn").find("img").attr("src");

    // Clonez l'image et ajoutez-la à l'élément de clonage
    const $imageClone = $("#image-clone");
    $imageClone.css("background-image", `url('${imageUrl}')`);
    $imageClone.show();

    // Récupérez la position de la souris au moment du clic
    const mouseX = e.clientX;
    const mouseY = e.clientY;

    // Animez l'image vers la position du panier
    $imageClone.addClass("animating");
    $imageClone.css({
        top: mouseY - $imageClone.height() / 2, // Position verticale de la souris
        left: mouseX - $imageClone.width() / 2, // Position horizontale de la souris
    });

    // Animez l'opacité pour la faire disparaître progressivement
    setTimeout(() => {
        $imageClone.css("opacity", 0);
    }, 400);

    // Supprimez l'image clonée après l'animation
    setTimeout(() => {
        $imageClone.hide();
        $imageClone.removeClass("animating");
        $imageClone.css("opacity", 1); // Réinitialisez l'opacité
    }, 900);
});
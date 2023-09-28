const imgdiv = document.querySelector('.prd_img_slide');
const img = document.querySelectorAll('.prd_img_slide > img');
const mainDiv = document.querySelector('.prd_card_img_arrow > img');

img.forEach(element => {
    element.addEventListener('click', () => {
        let imgSrc = element.src;
        mainDiv.src = imgSrc;
    })
});

const leftArrow = document.querySelector('.fa-arrow-left');
const rightArrow = document.querySelector('.fa-arrow-right');

leftArrow.addEventListener('click', () => {
    // Récupérez l'URL de l'image actuellement affichée dans mainDiv
    let actualImg = mainDiv.src;
    // Trouvez l'index de cette image dans le tableau des images
    let index = Array.from(img).findIndex(element => element.src === actualImg);
    // Si l'index est supérieur à 0 (il y a une image précédente), mettez à jour mainDiv avec l'URL de l'image précédente
    if (index > 0) {
        let previous = img[index - 1].src;
        mainDiv.src = previous;
    }
});

rightArrow.addEventListener('click', () => {
    let actualImg = mainDiv.src;
    let index = Array.from(img).findIndex(element => element.src === actualImg);
    if (index < img.length - 1) {
        let next = img[index + 1].src;
        mainDiv.src = next;
    }
});
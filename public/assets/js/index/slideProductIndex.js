const productContainers = document.querySelector('.slide_js_main');
const arrowIcons = document.querySelectorAll('.prd_card_arrow_index i');
const imageContainer = document.querySelector('.idx_img_container');
const firstImgWidth = document.querySelector('.idx_shop_card').offsetWidth + 14;
const scrollWidth = imageContainer.scrollWidth - imageContainer.clientWidth;

const showHideIcons = () => {
    arrowIcons[0].style.opacity = productContainers.scrollLeft == 0 ? "0" : "1";
    arrowIcons[1].style.opacity = productContainers.scrollLeft >= scrollWidth ? "0" : "1";
}
arrowIcons.forEach(icon => {
    icon.addEventListener('click', () => {
        if (icon.id == "left") {
            productContainers.scrollLeft -= firstImgWidth;
        } else if (icon.id == "right") {
            productContainers.scrollLeft += firstImgWidth;
        }
        setTimeout(() => showHideIcons(), 60);
    })
})

showHideIcons();
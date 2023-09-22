const shopCards = document.querySelectorAll('.idx_shop_card');

shopCards.forEach(card => {
    const button = card.querySelector('.btn_main_shop');
    button.style.display = "none";
    card.addEventListener('mouseover', () => {
        button.style.display = "flex";
    });
    card.addEventListener('mouseout', () => {
        button.style.display = "none";
    });
});
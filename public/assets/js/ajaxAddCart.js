const addToCartButtons = document.querySelectorAll('.btn_main_shop');

addToCartButtons.forEach(button => {
    button.addEventListener('click', () => {
        const productId = button.getAttribute('data-product-id');
        addToCart(productId);
    })
})

// Ajac Fonction
function addToCart(productId){
    // Créez une requête AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('POST', `/add-to-cart/${productId}`, true);

    // Définir le type de contentenue de la requête
    xhr.setRequestHeader('Content-Type', 'application/json');

    // Gérer la réponse de la requête
    xhr.onload = function () {
        if(xhr.status === 200){
            const response = JSON.parse(xhr.responseText);
            alert(response.message);
        }
    };

    // Envoye de la requête
    xhr.send();
}
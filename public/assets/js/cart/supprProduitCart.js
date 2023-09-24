const btnDeletePrd = document.querySelectorAll('.cart_delete_product_btn');

btnDeletePrd.forEach(button => {
    button.addEventListener('click', () => {
        const infoData = button.getAttribute('data-product-id');
        DeleteProductCart(infoData);

        cardPrdCheck = document.querySelectorAll('.cart_prd_check');
        cardPrdCheck.forEach(element => {
            if(element.getAttribute('data-product-id') == infoData){
                setInterval(element.remove(), 500);
            }
        })

        cardPrdCheck = document.querySelectorAll('.dropdown_cart_card');
        cardPrdCheck.forEach(element => {
            if(element.getAttribute('data-product-id') == infoData){
                setInterval(element.remove(), 500);
            }
        })
        
    })
});

function DeleteProductCart(productId){
     // Créez une requête AJAX
     const xhr = new XMLHttpRequest();
     xhr.open('POST', `/get_item_delete/${productId}`, true);
 
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

     // Set attente
    setInterval(6000);

     fetch('/get_pricecart_count')
        .then(response => response.json())
        .then(data => {
            const cartItemCountElement = document.querySelectorAll('.cart_total_price');
            cartItemCountElement.forEach(div => {
                div.textContent = data.totalPrice;
            })
        })
        .catch(error => {
            console.log('Erreur lors de la récupération du nombre d\'articles dans le panier : ', error);
        });
};


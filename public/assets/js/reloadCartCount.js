function fetchCartItemCount(){
    fetch('/get_item_count')
        .then(response => response.json())
        .then(data => {
            const cartItemCountElement = document.querySelector('.cart_count_a');
            cartItemCountElement.textContent = data.count;
        })
        .catch(error => {
            console.log('Erreur lors de la récupération du nombre d\'articles dans le panier : ', error);
        });
}

setInterval(fetchCartItemCount, 5000);
function fetchCartItemCount(){
    fetch('/get_pricecart_count')
        .then(response => response.json())
        .then(data => {
            const cartTotalPriceElement = document.querySelector('.cart_total_price');
            if(cartTotalPriceElement){
                cartTotalPriceElement.textContent = data.totalPrice;
            }
        })
        .catch(error => {
            console.log('Erreur lors de la récupération du nombre d\'articles dans le panier : ', error);
        });
        
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
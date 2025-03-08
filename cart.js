document.addEventListener('input', function(event) {
    if (event.target.classList.contains('quantity-input')) {
        const quantityInput = event.target;
        const newQuantity = parseInt(quantityInput.value);
        const price = parseFloat(quantityInput.dataset.price);
        const row = quantityInput.closest('tr');
        const itemTotalElement = row.querySelector('td:nth-child(4)');
        const newItemTotal = price * newQuantity;
        itemTotalElement.innerText = 'LKR' + newItemTotal.toFixed(2);

        updateTotalCartPrice();
    }
});

function updateTotalCartPrice() {
    const totalPriceElement = document.getElementById('total-price');
    let newTotalPrice = 0;

    document.querySelectorAll('.quantity-input').forEach(function(input) {
        const price = parseFloat(input.dataset.price);
        const quantity = parseInt(input.value);
        newTotalPrice += price * quantity;
    });

    totalPriceElement.innerText = newTotalPrice.toFixed(2);
}

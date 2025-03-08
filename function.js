document.addEventListener("DOMContentLoaded", function() {
  const decreaseBtn = document.getElementById('decrease-btn');
  const increaseBtn = document.getElementById('increase-btn');
  const quantityInput = document.getElementById('quantity');
  const smallImages = document.querySelectorAll('.small-image');
  const mainImage = document.getElementById('main-image');
  const addToCartBtn = document.getElementById('add-to-cart-btn');

  decreaseBtn.addEventListener('click', function() {
    let currentQuantity = parseInt(quantityInput.value);
    if (currentQuantity > 1) {
      quantityInput.value = currentQuantity - 1;
    }
  });

  increaseBtn.addEventListener('click', function() {
    let currentQuantity = parseInt(quantityInput.value);
    quantityInput.value = currentQuantity + 1;
  });
  
  function updateCartCount() {
    const cartCountElement = document.getElementById('cart-count');
    let currentCount = parseInt(cartCountElement.innerText);
    cartCountElement.innerText = currentCount + 1;
  }

  addToCartBtn.addEventListener('click', function() {
    updateCartCount();
  });

  smallImages.forEach(image => {
    image.addEventListener("click", function() {
      console.log("Small image clicked:"); 
      mainImage.src = this.src;
    });
  });
});




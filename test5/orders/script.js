let menu = [];
let cart = [];

const cartList = document.getElementById("cartList");
const totalPrice = document.getElementById("totalPrice");
const cartCount = document.getElementById("cartCount");

const openCartBtn = document.getElementById("openCart");
const cartSection = document.querySelector(".cart");
const closeCartBtn = document.getElementById("closeCart");

fetch("/test5/menu/menu_list.php")
  .then(res => res.json())
  .then(data => {
    menu = data;
    renderMenu();
  });

function renderMenu() {
  const menuContainer = document.getElementById("menuItems");
  menuContainer.innerHTML = "";

  const categories = {};
  menu.forEach(item => {
    if (!categories[item.category]) categories[item.category] = [];
    categories[item.category].push(item);
  });

  for (const [category, items] of Object.entries(categories)) {
    const categoryBlock = document.createElement("div");
    categoryBlock.classList.add("menu-category");

    const categoryHeader = document.createElement("h2");
    categoryHeader.textContent = category;
    categoryHeader.style.marginBottom = "1rem";
    categoryBlock.appendChild(categoryHeader);

    const itemList = document.createElement("div");
    itemList.classList.add("menu-items");

    items.forEach(item => {
      const div = document.createElement("div");
      div.classList.add("menu-item");
      div.innerHTML = `
      <img src="${item.image || 'main.png'}" class="menu-thumb" />
      <div>
        <h3>${item.name}</h3>
        <p>₱${item.price}</p>
      </div>
      <button class="add-btn" onclick="addToCart(${menu.indexOf(item)})">Add</button>
    `;
      itemList.appendChild(div);
    });

    categoryBlock.appendChild(itemList);
    menuContainer.appendChild(categoryBlock);
  }
}

//cart functions

function renderCart() {
  cartList.innerHTML = "";
  let total = 0;

  cart.forEach((item, index) => {
    total += item.price * item.qty;

    const li = document.createElement("li");
    li.innerHTML = `
      <div>
        <strong>${item.name}</strong><br>
        ₱${item.price} x ${item.qty}
      </div>
      <div>
        <button onclick="changeQty(${index}, ${item.qty - 1})">-</button>
        <button onclick="changeQty(${index}, ${item.qty + 1})">+</button>
      </div>
    `;
    cartList.appendChild(li);
  });

  totalPrice.textContent = `₱${total}`;
  cartCount.textContent = cart.reduce((sum, i) => sum + i.qty, 0);
}

function changeQty(index, qty) {
  if (qty <= 0) cart.splice(index, 1);
  else cart[index].qty = qty;
  renderCart();
}


// Add to Cart
function addToCart(index) {
  const selected = menu[index];
  const found = cart.find(i => i.name === selected.name);
  if (found) {
    found.qty++;
  } else {
    cart.push({ 
      product_id: selected.product_id,
      name: selected.name, 
      price: selected.price,
      qty: 1 });
  }
  renderCart();
}

// Sidebar toggle
openCartBtn.addEventListener("click", () => {
  cartSection.classList.toggle("open");
});

closeCartBtn.addEventListener("click", () => {
  cartSection.classList.remove("open");
});

const clearCartBtn = document.getElementById("clearCart");

clearCartBtn.addEventListener("click", () => {
  cart = [];          // empty the cart array
  renderCart();       // re-render to update UI
});


const checkoutBtn = document.getElementById("checkoutBtn");

checkoutBtn.addEventListener("click", () => {
  if (cart.length === 0) {
    alert("Your cart is empty!");
    return;
  }

  const total = cart.reduce((sum, item) => sum + item.price * item.qty, 0);

  fetch("checkout.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      total_amount: total,
      items: cart
    })
  })
    .then(res => {
      if (!res.ok) {
        throw new Error("Network response was not ok");
      }
      return res.json();
    })
    .then(data => {
      if (data && data.success) {
        // clear cart locally
        cart = [];
        renderCart();

        // redirect to payment page with order_id
      window.location.href = "../payment/payment.php?order_id=" + encodeURIComponent(data.order_id);
      } else {
        alert("Error saving order. Please try again.");
      }
    })
    .catch(err => {
      console.error("Checkout error:", err);
      alert("Something went wrong while saving the order.");
    });
});



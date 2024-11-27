
let checkoutOrderItems = [];

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

function handleCheckoutButton() {

    if (!(loggedIn)) {
        showModal(userModal);
        return;
    }

    let hasOrder = getCookie("order");
    if (!hasOrder) {
        showModal(orderModal);
        return;
    }

    fetch(`../server/cart.php?checkout=${hasOrder}`)
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            } else {
                response.json();
            }
        })
        .then((data) => {
            console.log(data);
        })

    fetch(`../server/order.php?completeOrder=${hasOrder}`)
        .then((response) => response.ok ? response.json() : console.log(response))
        .then((data) => console.log(data));

    window.location.href = "../pages/checkout.php";
}

class Cart extends HTMLElement {

    static get observedAttributes() {
        return ["total", "visible", "items"];
    }

    constructor() {
        super();
    }

    connectedCallback() {
        this.classList.add("cart");
        const cartItemsElement = document.createElement("div");
        cartItemsElement.classList.add("cart-items");
        let title = document.createElement("h2");
        title.textContent = "YOUR CART";
        let cartTotal = document.createElement("div");
        cartTotal.classList.add("cart-total");
        let total = document.createElement("h2");
        total.textContent = "TOTAL";
        let totalAmount = document.createElement("h2");
        totalAmount.id = "cart-total-amount";
        totalAmount.textContent = "$0.00";
        this.fetchCart();
        if (this.getAttribute("total")) {
            totalAmount.textContent = "$" + this.getAttribute("total");
        }
        this.appendChild(title);
        this.appendChild(cartItemsElement);
        cartTotal.appendChild(total);
        cartTotal.append(totalAmount);
        this.appendChild(cartTotal);
        let checkout = document.createElement("button");
        checkout.textContent = "CHECKOUT";
        checkout.classList.add("cart-checkout");
        checkout.addEventListener("click", () => { handleCheckoutButton() });
        this.appendChild(checkout);
    }

    attributeChangedCallback(name, oldValue, newValue) {
        if (oldValue === newValue) {
            return;
        }

        if (name == "total") {
            // Update the total price of the cart.
            let safeTotal = parseFloat(newValue).toFixed(2);
            document.querySelector("#cart-total-amount").textContent = "$" + safeTotal;
        }

        if (name == "visible") {
            if (newValue && newValue != "false") {
                let menu = document.querySelector(".menu");
                menu.classList.add("with-cart");
            } else {
                let menu = document.querySelector(".menu");
                menu.classList.remove("with-cart");
            }
        }

        if (name == "items") {
            let newItems = JSON.parse(newValue);
            let novelItems;
            if (oldValue) {
                let oldItems = JSON.parse(oldValue);
                const isSameItem = (item1, item2) => { return item1.itemId == item2.itemId };
                novelItems = newItems.filter(item => !oldItems.some(oldItem => isSameItem(item, oldItem)));
                let removedItems = oldItems.filter(item => !newItems.some(newItem => isSameItem(item, newItem)));
                let identicalItems = newItems.filter(item => oldItems.some(oldItem => isSameItem(item, oldItem)));
                removedItems.forEach(item => {
                    let cartItem = document.getElementById(`cart-${item.itemId}`);
                    if (cartItem) {
                        cartItem.remove();
                    }
                });
                identicalItems.forEach(item => {
                    let cartItem = document.getElementById(`cart-${item.itemId}`);
                    cartItem.setAttribute("qty", item.qty);
                });
            } else {
                novelItems = newItems;
            }
            novelItems.forEach(item => {
                let cartItem = document.createElement("cart-item");
                cartItem.setAttribute("id", `cart-${item.itemId}`);
                cartItem.setAttribute("name", item.name);
                cartItem.setAttribute("price", item.price);
                cartItem.setAttribute("qty", item.qty);
                cartItem.setAttribute("img", item.img);
                document.querySelector(".cart-items").appendChild(cartItem);
            });
            if (oldValue) {
                console.log("Pushing cart...")
                this.pushCart();
            }
            this.calcTotal();
        }
    }

    fetchCart() {
        fetch(`../server/cart.php`)
            .then((response) => response.json())
            .then((resp) => {
                // Parse the existing cart items JSON from the database.
                // Push the item into the cart.
                let items = resp['cart_items'];
                if (items && items.length > 0) {
                    this.setAttribute("visible", "true");
                    this.setAttribute("items", JSON.stringify(items));
                } else {
                    this.setAttribute("items", JSON.stringify([]));
                }
            });
    }

    pushCart() {
        console.log("pushing cart...");
        let items = JSON.parse(this.getAttribute('items'));
        fetch('../server/cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ cart_items: items }),
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then((json) => console.log(json))
            .catch((error) => console.error('Error:', error));
    }

    calcTotal() {
        // Calculate the total price of all items in the cart.
        let total = 0;
        let items = JSON.parse(this.getAttribute('items'));
        items.forEach(item => {
            total += parseFloat(item.price).toFixed(2) * parseFloat(item.qty).toFixed(2);
        });
        this.setAttribute("total", total);
    }
}

class CartItem extends HTMLElement {
    static get observedAttributes() {
        return ["qty"];
    }

    constructor() {
        super();
        this.cartType = this.getAttribute("checkout") ? "order" : "cart";

    }

    connectedCallback() {
        this.cartType = this.getAttribute("checkout") ? "order" : "cart";
        let cartType = this.cartType;
        this.setAttribute("class", `${cartType}-item`);
        this.classList.add(`${cartType}-item`);
        const detail = document.createElement("div");
        detail.classList.add(`${cartType}-detail`);
        let name = document.createElement("h3");
        name.textContent = this.getAttribute("name");
        let price = document.createElement("p");
        price.textContent = "$" + this.getAttribute("price");
        let qty = document.createElement("p");
        qty.id = `${cartType}-item-qty-${this.getAttribute("id")}`;
        qty.textContent = "QTY: " + this.getAttribute("qty");
        detail.appendChild(name);
        detail.appendChild(price);
        detail.appendChild(qty);
        if (cartType == "cart") {
            let remove = document.createElement("button");
            remove.textContent = "Remove";
            remove.classList.add("cart-item-remove");
            remove.addEventListener("click", (e) => { this.handleRemove() });
            detail.appendChild(remove);
        }
        let img = document.createElement("img");
        img.src = "../" + this.getAttribute("img");
        img.alt = this.getAttribute("name");
        this.appendChild(img);
        this.appendChild(detail);
    }

    attributeChangedCallback(name, oldValue, newValue) {
        if (this.cartType != "cart") {
            return;
        }

        if (oldValue === newValue || !oldValue) {
            return;
        }

        if (name == "qty") {
            let id = this.getAttribute("id");
            this.querySelector(`#cart-item-qty-${id}`).textContent = "QTY: " + newValue;
            let cart = document.querySelector("user-cart");
            let items = JSON.parse(cart.getAttribute("items"));
            let me = items.filter(item => item.itemId == id.split("-")[1])[0];
            me.qty = parseInt(newValue);
            items = items.filter(item => item.itemId != id.split("-")[1]);
            items.push(me);
            cart.setAttribute("items", JSON.stringify(items));
        }
    }

    handleRemove() {
        if (this.cartType != "cart") {
            return;
        }
        if (this.getAttribute("qty") <= 1) {
            this.remove();
        } else {
            let qty = parseInt(this.getAttribute("qty")) - 1;
            this.setAttribute("qty", qty);
        }
    }
    disconnectedCallback() {
        if (this.cartType != "cart") {
            return;
        }
        let itemId = this.getAttribute("id").split("-")[1];
        let cart = document.querySelector("user-cart");
        let items = JSON.parse(cart.getAttribute("items"));
        let newItems = items.filter(item => item.itemId != itemId);
        cart.setAttribute("items", JSON.stringify(newItems));
    }
}

function addToCart(itemId) {
    let cart = document.querySelector("user-cart");
    let items = JSON.parse(cart.getAttribute("items"));
    let item = items.filter(item => item.itemId == itemId);
    // Item is already in cart, just increase quantity by 1.
    if (item.length == 1) {
        item[0].qty += 1;

        // In case of duplicates, just validate them.
    } else if (item.length > 1) {
        item[0].qty += item.length;
        item[0].qty += 1;
        items = items.filter(item => item.itemId != itemId);

        // Item is not in cart, add it.
    } else {
        let menuItem = document.getElementById(`menu-${itemId}`);
        item = [{
            itemId: itemId,
            name: menuItem.dataset.name,
            price: menuItem.dataset.price,
            qty: 1,
            img: menuItem.dataset.img
        }];
    }
    items = items.filter(item => item.itemId != itemId); // Avoid duplicates.
    items.push(item[0]);
    if (!(cart.getAttribute("visible")) || cart.getAttribute("visible") != "true") {
        cart.setAttribute("visible", "true");
        let menu = document.querySelector(".menu");
        menu.classList.add("with-cart");
    }
    cart.setAttribute("items", JSON.stringify(items));
}


customElements.define("user-cart", Cart);
customElements.define("cart-item", CartItem);

// Written by Libert

// A function to get what cookies are set.
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

// This runs when the user clicks CHECKOUT on the cart.
function handleCheckoutButton() {

    // This variable is controlled by the script in header.php (since code inside of header.php runs on every page)
    // and we can fetch it here as a result of that.
    // You don't want the user to be able to checkout without having first made an account.
    if (!(loggedIn)) {
        showModal(userModal);
        return;
    }

    // We check if the user has already started the order process (i.e we know what order type and address)
    // This is set in the order.php script.
    // If the user hasn't, prompt them to start an order.
    let hasOrder = getCookie("order");
    if (!hasOrder) {
        showModal(orderModal);
        return;
    }

    // Cart items are saved in a different table than items that are officially part of an order (this way you can add and remove easily)
    // Once you click checkout, we add your items to your actual order. cart.php handles this.
    fetch(`../server/cart.php?checkout=${hasOrder}`)
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            } else {
                response.json();
            }
        })
        .then((data) => {
            console.log(data); // For debugging purposes
        })

    // We then mark the order as complete, so that the user can't add more items to it.
    fetch(`../server/order.php?completeOrder=${hasOrder}`)
        .then((response) => response.ok ? response.json() : console.log(response))
        .then((data) => console.log(data));

    // Finally, we redirect the user to the checkout page.
    window.location.href = "../pages/checkout.php";
}


// This creates a "Cart" element that will display the user's cart.
// It dynamically updates and manages information assigned to it using attributes.
class Cart extends HTMLElement {

    static get observedAttributes() {
        return ["total", "visible", "items"];
    }

    constructor() {
        super();
    }

    // This function runs when the element is added to the DOM.
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
        this.fetchCart(); // Method to keep cart items in sync with the database.
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

    // This allows us to observe the changes of the attributes of the element, so if 
    // an item is added or removed from the cart, we can keep that up to date with the database.
    attributeChangedCallback(name, oldValue, newValue) {
        if (oldValue === newValue) {
            return;
        }

        // If the total is updated, we change the text to reflect that.
        if (name == "total") {
            // Update the total price of the cart.
            let safeTotal = parseFloat(newValue).toFixed(2);
            document.querySelector("#cart-total-amount").textContent = "$" + safeTotal;
        }

        // This is run when the cart is made visible, it's invisible to start.
        if (name == "visible") {
            if (newValue && newValue != "false") {
                let menu = document.querySelector(".menu");
                menu.classList.add("with-cart"); // This controls the visibility of the cart.
            } else {
                let menu = document.querySelector(".menu");
                menu.classList.remove("with-cart");
            }
        }

        // This is where the magic happens. If an item is added or removed from the cart, we update the cart.
        if (name == "items") {
            let newItems = JSON.parse(newValue); // We store the information about the cart as a JSON string.
            let novelItems; // This variable declaration is to get it out of the if/else scope since it's the only one we need regardless.

            if (oldValue) { // If there were already items in the cart

                let oldItems = JSON.parse(oldValue);
                // We make a function to check if items are the same.
                const isSameItem = (item1, item2) => { return item1.itemId == item2.itemId };
                // This creates a list of all the items that are in the cart now that weren't there before.
                novelItems = newItems.filter(item => !oldItems.some(oldItem => isSameItem(item, oldItem)));
                // This creates a list of all the items that were in the cart before that aren't there now.
                let removedItems = oldItems.filter(item => !newItems.some(newItem => isSameItem(item, newItem)));
                // This creates a list of all the items that are in the cart now that were there before, presumably because their quantity updated.
                let identicalItems = newItems.filter(item => oldItems.some(oldItem => isSameItem(item, oldItem)));

                // For all the removed items, find them and get rid of them.
                removedItems.forEach(item => {
                    let cartItem = document.getElementById(`cart-${item.itemId}`);
                    if (cartItem) {
                        cartItem.remove();
                    }
                });
                // For all the identical items, update their quantity.
                identicalItems.forEach(item => {
                    let cartItem = document.getElementById(`cart-${item.itemId}`);
                    cartItem.setAttribute("qty", item.qty);
                });
            } else {
                // If there were no items in the cart before, just add all the items.
                novelItems = newItems;
            }
            // Regardless of whether or not there were old items, this allows us to add all the 
            // new ones to the cart.
            novelItems.forEach(item => {
                let cartItem = document.createElement("cart-item");
                cartItem.setAttribute("id", `cart-${item.itemId}`);
                cartItem.setAttribute("name", item.name);
                cartItem.setAttribute("price", item.price);
                cartItem.setAttribute("qty", item.qty);
                cartItem.setAttribute("img", item.img);
                document.querySelector(".cart-items").appendChild(cartItem);
            });

            // If there were old items in the cart, this means the user had added them and we need to update the database.
            // If there wasn't, it would mean we ran a fetch to the database and it pre-populated them, so no need to update.
            if (oldValue) {
                this.pushCart();
            }
            // Update the total shown on the cart
            this.calcTotal();
        }
    }

    fetchCart() {
        // This makes a call to the server to get all of the items that the user has in their cart.
        // If they don't have any, it will return an empty array. This is how we stay in sync with the server.
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
        // This is how we update the database with the items in the cart. 
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
            // Ensure they're rounded correctly.
            total += parseFloat(item.price).toFixed(2) * parseFloat(item.qty).toFixed(2);
        });
        this.setAttribute("total", total);
    }
}


// This represents an individual item inside of the cart.
class CartItem extends HTMLElement {
    static get observedAttributes() {
        return ["qty"];
    }


    constructor() {
        super();
        // This particular element is reusable both for checkout and for the cart.
        // But there are some differences. This is how we differentiate between the two.
        this.cartType = this.getAttribute("checkout") ? "order" : "cart";

    }

    connectedCallback() {
        this.cartType = this.getAttribute("checkout") ? "order" : "cart";
        let cartType = this.cartType; // So we don't have to rewrite all the calls to cartType once it became a class variable.
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
        img.src = "../" + this.getAttribute("img"); // The database doesn't store the trailing slash, so we add it here.
        img.alt = this.getAttribute("name");
        this.appendChild(img);
        this.appendChild(detail);
    }

    attributeChangedCallback(name, oldValue, newValue) {
        if (this.cartType != "cart") {
            // On the checkout page, the items are static, so no updates should happen.
            return;
        }

        if (oldValue === newValue || !oldValue) {
            // Nothing to update
            return;
        }

        if (name == "qty") {
            // Update the quantity text on this item so we know how many are in the cart.
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

    // This allows us to make sure the total displayed on the parent Cart element (defined above) updates it's totals correctly.
    // It also makes sure that if we go below 1 quantity, the item is removed from the cart.
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

    // This is run when the cart no longer exists, and also calls upward to the parent Cart element to update everything.
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

// This is called by the buttons on the menu to add elements to the cart.
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

// Define the custom elements.
customElements.define("user-cart", Cart);
customElements.define("cart-item", CartItem);

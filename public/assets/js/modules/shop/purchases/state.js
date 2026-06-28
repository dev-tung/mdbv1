export const PurchaseState = {

    products: {},

    add(product) {

        if (this.products[product.id]) {
            return;
        }

        this.products[product.id] = {
            id: product.id,
            name: product.name,
            quantity: 1,
            price: 0
        };
    },

    remove(id) {
        delete this.products[id];
    },

    updateQuantity(id, quantity) {

        if (!this.products[id]) {
            return;
        }

        this.products[id].quantity = quantity;
    },

    updatePrice(id, price) {

        if (!this.products[id]) {
            return;
        }

        this.products[id].price = price;
    },

    get() {
        return Object.values(this.products);
    }
};
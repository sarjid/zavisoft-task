import { defineStore } from "pinia";

const normalizeOptions = (options = {}) => {
    return Object.keys(options)
        .sort()
        .map((key) => `${key}:${options[key]}`)
        .join("|");
};

const resolvePrice = (product, variant) => {
    return (
        variant?.current_price ??
        variant?.price ??
        product?.current_price ??
        product?.unit_price ??
        0
    );
};

const resolveImage = (product, variant) => {
    return variant?.image || product?.thumbnail || product?.image || "";
};

const resolveSku = (product, variant) => {
    return variant?.sku || product?.sku || "";
};

export const useCartStore = defineStore("cart", {
    state: () => ({
        items: [],
    }),
    getters: {
        totalQuantity: (state) =>
            state.items.reduce((total, item) => total + item.quantity, 0),
        subtotal: (state) =>
            state.items.reduce(
                (total, item) => total + item.price * item.quantity,
                0
            ),
    },
    actions: {
        addItem({ product, variant = null, quantity = 1, selectedOptions = {} }) {
            if (!product || quantity <= 0) {
                return;
            }
            const optionsKey = normalizeOptions(selectedOptions);
            const key = `${product.id}-${variant?.id ?? variant?.sku ?? "base"}-${optionsKey}`;
            const existing = this.items.find((item) => item.key === key);
            const price = Number(resolvePrice(product, variant)) || 0;
            const nextQuantity = existing
                ? existing.quantity + quantity
                : quantity;

            const options = Object.entries(selectedOptions).map(([name, value]) => ({
                name,
                value,
            }));

            const itemPayload = {
                key,
                productId: product.id,
                variantId: variant?.id ?? null,
                name: product.name || product.title || "Product",
                slug: product.slug || "",
                sku: resolveSku(product, variant),
                image: resolveImage(product, variant),
                price,
                quantity: nextQuantity,
                options,
            };

            if (existing) {
                Object.assign(existing, itemPayload);
            } else {
                this.items.push(itemPayload);
            }
        },
        setQuantity(key, quantity) {
            const item = this.items.find((entry) => entry.key === key);
            if (!item) {
                return;
            }
            item.quantity = Math.max(1, quantity);
        },
        increment(key) {
            const item = this.items.find((entry) => entry.key === key);
            if (item) {
                item.quantity += 1;
            }
        },
        decrement(key) {
            const item = this.items.find((entry) => entry.key === key);
            if (!item) {
                return;
            }
            item.quantity = Math.max(1, item.quantity - 1);
        },
        removeItem(key) {
            this.items = this.items.filter((entry) => entry.key !== key);
        },
        clearCart() {
            this.items = [];
        },
    },
    persist: {
        storage: localStorage,
        paths: ["items"],
    },
});

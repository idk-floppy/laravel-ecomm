async function GetCartService() {
    let response = await axios.get("cart/get");
    if (response.data.cart.items.length > 0) {
        response.data.cart.items = response.data.cart.items.map((item) => ({
            ...item,
            showQuantityField: false,
        }));
    };
    return response.data.cart;
};

export { GetCartService };

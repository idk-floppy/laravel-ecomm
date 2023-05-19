async function addToCart(productId, qty = 1, addOrSet = "add") {
    const response = await axios.post('cart/add', {
        product_data: productId,
        addOrSet: addOrSet,
        qty: qty,
    });
    return response.data.success;
}

export { addToCart };

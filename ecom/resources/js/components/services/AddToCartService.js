async function addToCart(productId, qty = 1, addOrSet = "add") {
    const response = await axios.post('cart/add', {
        product_id: productId,
        addOrSet: addOrSet,
        qty: qty,
    });
    return response;
}

export { addToCart };

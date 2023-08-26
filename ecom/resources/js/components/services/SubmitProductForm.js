async function submitProductForm(formData, productId = null) {
    const response = productId
        ? await axios.post(`api/products/${productId}`, formData) // send request to the update url if an id was submitted.
        : await axios.post('api/products', formData); // otherwise create the product
    return response.data;
}

export { submitProductForm };

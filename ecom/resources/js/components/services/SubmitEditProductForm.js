async function submitEditProductForm(formData, productId) {
    const response = await axios.post(`api/products/${productId}`, formData);
    return response.data;
}

export { submitEditProductForm };

async function submitEditProductForm(formData, productId) {
    formData.append('_method', 'PATCH')
    const response = await axios.post(`api/products/${productId}`, formData);
    return response.data;
}

export { submitEditProductForm };

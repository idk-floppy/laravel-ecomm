async function SubmitEditProductForm(formData, productId) {
    const response = await axios.post(`api/products/${productId}`, formData);
    console.log(response);
    return response.data;
}

export { SubmitEditProductForm };

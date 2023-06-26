async function submitProductForm(formData) {
    const response = await axios.post('api/products', formData);
    return response.data;
}

export { submitProductForm };

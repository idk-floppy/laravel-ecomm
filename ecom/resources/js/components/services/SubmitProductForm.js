async function submitProductForm(formData) {
    const response = await axios.post('products', formData);
    return response.data;
}

export { submitProductForm };

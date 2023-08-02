async function getProductImage(image) {
    if (image) {
        return window.location.origin + "/storage/" + image;
    }
    return null;
}

export { getProductImage };

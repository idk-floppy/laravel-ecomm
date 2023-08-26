/**
 * Handles callback function with a ".then()" for functions fired up with events. If a function can have a .then(), it can be used here.
 * @param { callbackableFunction } callbackableFunction
 * @param { function } callback
 */
function handleCallback(callbackableFunction, callback) {
    if (callback && typeof callback === "function") {
        callbackableFunction.then(() => {
            callback();
        });
    }
}

export { handleCallback };

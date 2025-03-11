function getStoredItems() {
    const emptyCart = {
        dance: [],
        yummy: [],
        history: []
    }

    const items = localStorage.getItem('orderedItems');
    if (!items) {
        localStorage.setItem('orderedItems', JSON.stringify(emptyCart));
        return emptyCart;
    }
    return JSON.parse(items);
}

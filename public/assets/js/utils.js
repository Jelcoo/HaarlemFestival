function setUrlQuery(...data) {
    const urlParams = new URLSearchParams(window.location.search);
    for (const d of data) {
        urlParams.set(d[0], d[1]);
    }
    window.location.search = urlParams.toString();
}

function getUrlQuery(key) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(key);
}

function removeUrlQuery(keys) {
    const urlParams = new URLSearchParams(window.location.search);
    for (const key of keys) {
        urlParams.delete(key);
    }

    window.location.search = urlParams.toString();
}

// Source: https://gist.github.com/ionurboz/51b505ee3281cd713747b4a84d69f434
function debounce(fn, delay) {
    var timer = null;
    return function () {
        var context = this,
            args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function () {
            fn.apply(context, args);
        }, delay);
    };
}

function formatBytes(bytes, decimals = 2) {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + " " + sizes[i];
}

function escapeHtml(unsafe) {
    return unsafe
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");
}

function getNextOccurrence(dateStr) {
    const [, month, day, time] = dateStr.split(" ");
    const now = new Date();
    let occurrence = new Date(`${month} ${day} ${now.getFullYear()} ${time}`);

    // If that date/time has already passed, use the next year.
    if (occurrence < now) {
        occurrence = new Date(
            `${month} ${day} ${now.getFullYear() + 1} ${time}`
        );
    }

    return occurrence.getUTCFullYear();
}

function updateURL() {
    let sort = document.getElementById("sortSelect").value;
    let direction = document.getElementById("directionSelect").value;
    let searchParams = new URLSearchParams(window.location.search);

    if (sort) {
        searchParams.set("sort", sort);
    } else {
        searchParams.delete("sort");
    }

    if (direction) {
        searchParams.set("direction", direction);
    } else {
        searchParams.delete("direction");
    }

    window.location.href =
        window.location.pathname + "?" + searchParams.toString();
}

function resetSort() {
    let searchParams = new URLSearchParams(window.location.search);

    searchParams.delete("sort");
    searchParams.delete("direction");

    window.location.href =
        window.location.pathname + "?" + searchParams.toString();
}

function fillFileInput(input, url) {
    if (!url) {
        input.files = [];
        return;
    }

    fetch(url)
        .then((response) => response.blob())
        .then((blob) => {
            const file = new File([blob], url.split("/").pop(), { type: blob.type });

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
    
            input.files = dataTransfer.files;
        });
}

class VatPriceHelper {
    constructor(configs) {
        this.vatField = document.getElementById(configs.vatFieldId);
        this.bindings = configs.bindings;

        this.bindEvents();
        this.autoCalculate();
    }

    getVatDecimal() {
        return parseFloat(this.vatField.value) / 100 || 0;
    }

    calcWithVat(base) {
        return base * (1 + this.getVatDecimal());
    }

    calcBaseFromVat(inclVat) {
        return inclVat / (1 + this.getVatDecimal());
    }

    bindEvents() {
        this.bindings.forEach(binding => {
            document.getElementById(binding.base).addEventListener('input', () => this.updateIncl(binding));
            document.getElementById(binding.incl).addEventListener('input', () => this.updateBase(binding));
        });

        this.vatField.addEventListener('input', () => this.updateAllIncl());
    }

    updateIncl(binding) {
        const baseVal = parseFloat(document.getElementById(binding.base).value) || 0;
        document.getElementById(binding.incl).value = this.calcWithVat(baseVal).toFixed(2);
    }

    updateBase(binding) {
        const inclVal = parseFloat(document.getElementById(binding.incl).value) || 0;
        document.getElementById(binding.base).value = this.calcBaseFromVat(inclVal).toFixed(2);
    }

    updateAllIncl() {
        this.bindings.forEach(binding => this.updateIncl(binding));
    }

    autoCalculate() {
        this.updateAllIncl();
    }
}

// Example usage:
// new VatPriceHelper({
//     vatFieldId: 'vat',
//     bindings: [
//         { base: 'kids_price', incl: 'kids_price_vat' },
//         { base: 'adult_price', incl: 'adult_price_vat' },
//         { base: 'reservation_cost', incl: 'reservation_cost_vat' },
//         { base: 'single_price', incl: 'single_price_vat' },
//         { base: 'family_price', incl: 'family_price_vat' },
//     ]
// });

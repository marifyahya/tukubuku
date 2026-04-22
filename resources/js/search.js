document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.querySelector(
        'input[placeholder*="Cari judul buku"]',
    );
    if (!searchInput) return;

    let debounceTimer;
    const dropdown = document.createElement("div");
    dropdown.className =
        "absolute z-50 mt-2 w-full bg-white rounded-lg shadow-lg border border-gray-200 hidden";
    dropdown.setAttribute("role", "listbox");
    searchInput.parentNode.appendChild(dropdown);

    function debounce(func, delay) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(func, delay);
    }

    function performSearch() {
        const query = searchInput.value.trim();
        if (query.length < 2) {
            dropdown.innerHTML = "";
            dropdown.classList.add("hidden");
            return;
        }

        // Show loading state
        dropdown.innerHTML =
            '<div class="px-4 py-2 text-gray-500">Mencari...</div>';
        dropdown.classList.remove("hidden");

        // Fetch suggestions
        fetch(`/api/search/suggestions?q=${encodeURIComponent(query)}`)
            .then((response) => response.json())
            .then((data) => {
                renderSuggestions(data);
            })
            .catch((error) => {
                console.error("Error fetching suggestions:", error);
                dropdown.innerHTML =
                    '<div class="px-4 py-2 text-red-500">Terjadi kesalahan</div>';
            });
    }

    function renderSuggestions(suggestions) {
        if (suggestions.length === 0) {
            dropdown.innerHTML =
                '<div class="px-4 py-2 text-gray-500">Buku tidak ditemukan</div>';
            return;
        }

        dropdown.innerHTML = suggestions
            .map(
                (book, index) => `
            <div 
                class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer"
                role="option"
                aria-selected="false"
                data-id="${book.slug}"
                data-index="${index}"
            >
                ${
                    book.cover_url
                        ? `<img src="${book.cover_url}" alt="${book.title}" class="w-10 h-10 rounded mr-3 object-cover">`
                        : `<div class="w-10 h-10 bg-gray-200 rounded mr-3 flex items-center justify-center"><i class="fas fa-book text-gray-400"></i></div>`
                }
                <div class="flex-1">
                    <div class="font-medium text-gray-900">${book.title}</div>
                    <div class="text-sm text-gray-500">${book.author}</div>
                </div>
                <div class="text-sm font-semibold text-primary">${formatCurrency(book.price)}</div>
            </div>
        `,
            )
            .join("");

        // Add click and keyboard events to items
        const items = dropdown.querySelectorAll('[role="option"]');
        items.forEach((item) => {
            item.addEventListener("click", () => {
                const bookId = item.getAttribute("data-id");
                window.location.href = `/books/${bookId}`;
            });

            item.addEventListener("mouseenter", () => {
                items.forEach((i) => i.setAttribute("aria-selected", "false"));
                item.setAttribute("aria-selected", "true");
            });

            item.addEventListener("mouseleave", () => {
                item.setAttribute("aria-selected", "false");
            });
        });
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
        }).format(amount);
    }

    // Event listeners
    searchInput.addEventListener("input", () => {
        debounce(performSearch, 300);
    });

    // Keyboard navigation
    let activeIndex = -1;
    searchInput.addEventListener("keydown", (e) => {
        const items = dropdown.querySelectorAll('[role="option"]');
        if (e.key === "ArrowDown") {
            e.preventDefault();
            if (items.length === 0) return;
            activeIndex = (activeIndex + 1) % items.length;
            setActiveItem(items, activeIndex);
        } else if (e.key === "ArrowUp") {
            e.preventDefault();
            if (items.length === 0) return;
            activeIndex = (activeIndex - 1 + items.length) % items.length;
            setActiveItem(items, activeIndex);
        } else if (e.key === "Enter") {
            e.preventDefault();
            if (activeIndex >= 0 && items[activeIndex]) {
                const bookId = items[activeIndex].getAttribute("data-id");
                window.location.href = `/books/${bookId}`;
            } else if (items.length > 0) {
                // Click first item if no active selection
                const bookId = items[0].getAttribute("data-id");
                window.location.href = `/books/${bookId}`;
            }
        } else if (e.key === "Escape") {
            dropdown.classList.add("hidden");
            activeIndex = -1;
        }
    });

    // Hide dropdown when clicking outside
    document.addEventListener("click", (e) => {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add("hidden");
            activeIndex = -1;
        }
    });

    function setActiveItem(items, index) {
        items.forEach((item, i) => {
            if (i === index) {
                item.classList.add("bg-gray-100");
                item.setAttribute("aria-selected", "true");
                activeIndex = i;
            } else {
                item.classList.remove("bg-gray-100");
                item.setAttribute("aria-selected", "false");
            }
        });
        // Scroll into view if needed
        const activeItem = items[index];
        if (activeItem) {
            const dropdownRect = dropdown.getBoundingClientRect();
            const itemRect = activeItem.getBoundingClientRect();
            if (itemRect.top < dropdownRect.top) {
                dropdown.scrollTop = activeItem.offsetTop;
            } else if (itemRect.bottom > dropdownRect.bottom) {
                dropdown.scrollTop =
                    activeItem.offsetTop -
                    dropdown.clientHeight +
                    activeItem.clientHeight;
            }
        }
    }
});

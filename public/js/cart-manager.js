/**
 * Centralized Cart Management System
 * Handles all cart operations across the application
 */

// Centralized Cart Manager for all pages
// Usage: Just include this file in your layout or relevant pages

const CartManager = (function() {
    // Flag to prevent duplicate initialization
    let isInitialized = false;
    
    // Get CSRF token - try multiple sources and get fresh token each time
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
               document.querySelector('input[name="_token"]')?.value ||
               window.csrf_token || '';
    }

    // Utility: Update cart preview in header
    function updateCartPreview(previewHtml) {
        document.querySelectorAll('.cart-dropdown').forEach(el => {
            el.innerHTML = previewHtml;
        });
    }
    // Utility: Update cart count badge
    function updateCartCount(count) {
        document.querySelectorAll('.cart-count').forEach(el => {
            el.textContent = count || 0;
        });
    }
    // Utility: Show alert (can be replaced with toast)
    function showAlert(msg) {
        alert(msg);
    }
    // Utility: Update cart summary on cart page
    function updateCartSummary(data) {
        console.log('Updating cart summary with data:', data);
        if (data.subtotal !== undefined) {
            const subtotalEl = document.getElementById('cart-subtotal');
            const taxEl = document.getElementById('cart-tax');
            const shippingEl = document.getElementById('cart-shipping');
            const totalEl = document.getElementById('cart-total');
            
            console.log('Summary elements found:', {
                subtotal: !!subtotalEl,
                tax: !!taxEl,
                shipping: !!shippingEl,
                total: !!totalEl
            });
            
            if (subtotalEl) subtotalEl.textContent = '$' + data.subtotal.toFixed(2);
            if (taxEl) taxEl.textContent = '$' + data.tax.toFixed(2);
            if (shippingEl) shippingEl.textContent = '$' + data.shipping.toFixed(2);
            if (totalEl) totalEl.textContent = '$' + data.total.toFixed(2);
        }
    }
    // Update available stock for product grid
    function updateAvailableStock(productId) {
        fetch('/product/' + productId + '/available-stock')
            .then(response => response.json())
            .then(data => {
                const stockElem = document.getElementById('available-stock-' + productId);
                const btn = document.getElementById('add-to-cart-btn-' + productId);
                
                if (stockElem) {
                    if (data.available_stock > 0) {
                        stockElem.textContent = data.available_stock + ' in stock';
                    } else {
                        stockElem.textContent = 'Out of Stock';
                    }
                }
                
                if (btn) {
                    if (data.available_stock > 0) {
                        btn.disabled = false;
                        btn.textContent = 'Add to Cart';
                    } else {
                        btn.disabled = true;
                        btn.textContent = 'Out of Stock';
                    }
                }
            })
            .catch(error => {
                console.error('Error updating stock:', error);
            });
    }
    // Add to cart (from grid or single page)
    function addToCart(productId, quantity = 1, onSuccess, onError) {
        console.log('Adding to cart:', productId, 'quantity:', quantity);
        const csrfToken = getCsrfToken();
        if (!csrfToken) {
            showAlert('CSRF token not found. Please refresh the page.');
            return;
        }

        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ product_id: productId, quantity: quantity })
        })
        .then(res => {
            if (!res.ok) {
                return res.json().then(data => {
                    throw new Error(data.error || `HTTP error! status: ${res.status}`);
                });
            }
            return res.json();
        })
        .then(data => {
            console.log('Cart add success:', data);
            if (data.preview) updateCartPreview(data.preview);
            if (data.count !== undefined) updateCartCount(data.count);
            // Update stock for product grid
            updateAvailableStock(productId);
            if (data.error) {
                showAlert(data.error);
                if (onError) onError(data);
            } else if (onSuccess) {
                onSuccess(data);
            }
        })
        .catch(err => { 
            console.error('Cart add error:', err);
            showAlert(err.message); 
            if (onError) onError(err); 
        });
    }
    // Update cart item (cart page only)
    function updateCart(productId, quantity, onSuccess, onError) {
        const csrfToken = getCsrfToken();
        if (!csrfToken) {
            showAlert('CSRF token not found. Please refresh the page.');
            return;
        }

        fetch('/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ product_id: productId, quantity: quantity })
        })
        .then(res => {
            if (!res.ok) {
                return res.json().then(data => {
                    throw new Error(data.error || `HTTP error! status: ${res.status}`);
                });
            }
            return res.json();
        })
        .then(data => {
            if (data.preview) updateCartPreview(data.preview);
            if (data.count !== undefined) updateCartCount(data.count);
            updateCartSummary(data);
            if (data.error) {
                showAlert(data.error);
                if (onError) onError(data);
            } else if (onSuccess) {
                onSuccess(data);
            }
        })
        .catch(err => { 
            console.error('Cart update error:', err);
            showAlert(err.message); 
            if (onError) onError(err); 
        });
    }
    // Remove from cart
    function removeFromCart(productId, onSuccess, onError) {
        const csrfToken = getCsrfToken();
        if (!csrfToken) {
            showAlert('CSRF token not found. Please refresh the page.');
            return;
        }

        fetch('/cart/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(res => {
            if (!res.ok) {
                return res.json().then(data => {
                    throw new Error(data.error || `HTTP error! status: ${res.status}`);
                });
            }
            return res.json();
        })
        .then(data => {
            if (data.preview) updateCartPreview(data.preview);
            if (data.count !== undefined) updateCartCount(data.count);
            updateCartSummary(data);
            if (data.error) {
                showAlert(data.error);
                if (onError) onError(data);
            } else if (onSuccess) {
                onSuccess(data);
            }
        })
        .catch(err => { 
            console.error('Cart remove error:', err);
            showAlert(err.message); 
            if (onError) onError(err); 
        });
    }
    // Quantity controls (single product, cart page)
    function setupQuantityControls() {
        // Single product page
        const decreaseBtn = document.getElementById('decrease');
        const increaseBtn = document.getElementById('increase');
        const quantityInput = document.getElementById('quantity');
        const priceElement = document.querySelector('.text-2xl.font-semibold.mb-8');
        const unitPrice = priceElement ? parseFloat(priceElement.dataset.unitPrice || priceElement.textContent.replace(/[^\d.]/g, '')) : null;
        const maxStock = parseInt(quantityInput?.getAttribute('max')) || 9999;
        if (decreaseBtn && increaseBtn && quantityInput) {
            function updateQuantity(qty) {
                qty = parseInt(qty) || 1;
                qty = Math.max(1, qty);
                qty = Math.min(maxStock, qty);
                quantityInput.value = qty;
                if (unitPrice && priceElement) {
                    priceElement.textContent = `$${(unitPrice * qty).toFixed(2)}`;
                }
                decreaseBtn.disabled = qty <= 1;
                increaseBtn.disabled = qty >= maxStock;
                decreaseBtn.classList.toggle('opacity-50', qty <= 1);
                increaseBtn.classList.toggle('opacity-50', qty >= maxStock);
            }
            decreaseBtn.addEventListener('click', e => { e.preventDefault(); updateQuantity(quantityInput.value - 1); });
            increaseBtn.addEventListener('click', e => { e.preventDefault(); updateQuantity(Number(quantityInput.value) + 1); });
            quantityInput.addEventListener('input', e => { updateQuantity(e.target.value); });
            quantityInput.addEventListener('change', e => { if (e.target.value === '' || isNaN(e.target.value)) { e.target.value = 1; updateQuantity(1); } });
            updateQuantity(quantityInput.value);
        }
        // Cart page
        document.querySelectorAll('.cart-increment, .cart-decrement').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                const input = row.querySelector('.quantity');
                let qty = parseInt(input.value) || 1;
                if (this.classList.contains('cart-increment')) {
                    qty++;
                } else if (qty > 1) {
                    qty--;
                }
                input.value = qty;
                updateCart(row.dataset.productId, qty, function(data) {
                    // Update row total
                    const priceCell = row.querySelector('td:nth-child(2)');
                    const totalCell = row.querySelector('td:nth-child(4)');
                    if (priceCell && totalCell) {
                        const price = parseFloat(priceCell.textContent.replace('$', '')) || 0;
                        totalCell.textContent = '$' + (price * qty).toFixed(2);
                    }
                });
            });
        });
        document.querySelectorAll('.quantity').forEach(function(input) {
            input.addEventListener('change', function() {
                let qty = parseInt(this.value) || 1;
                if (qty < 1) qty = 1;
                this.value = qty;
                const row = this.closest('tr');
                updateCart(row.dataset.productId, qty, function(data) {
                    // Update row total
                    const priceCell = row.querySelector('td:nth-child(2)');
                    const totalCell = row.querySelector('td:nth-child(4)');
                    if (priceCell && totalCell) {
                        const price = parseFloat(priceCell.textContent.replace('$', '')) || 0;
                        totalCell.textContent = '$' + (price * qty).toFixed(2);
                    }
                });
            });
        });
    }
    // Add to cart buttons (grid, single, etc.) - using event delegation
    function setupAddToCartButtons() {
        // Remove any existing event listeners by cloning and replacing
        document.querySelectorAll('.add-to-cart-btn').forEach(function(btn) {
            const newBtn = btn.cloneNode(true);
            btn.parentNode.replaceChild(newBtn, btn);
        });
        
        // Use event delegation to handle add to cart clicks
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('add-to-cart-btn')) {
                e.preventDefault();
                e.stopPropagation();
                
                let productId = e.target.getAttribute('data-product-id');
                let quantity = 1;
                
                // If on single product page, get quantity from input
                const quantityInput = document.getElementById('quantity');
                if (quantityInput) {
                    quantity = parseInt(quantityInput.value) || 1;
                }
                
                console.log('Add to cart clicked:', productId, 'quantity:', quantity);
                
                // Always use addToCart (not updateCart) to add the quantity
                addToCart(productId, quantity);
            }
        });
    }
    // Remove from cart buttons
    function setupRemoveFromCartButtons() {
        document.querySelectorAll('.cart-remove').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                const productId = row.dataset.productId;
                if (confirm('Are you sure you want to remove this item from the cart?')) {
                    removeFromCart(productId, function() {
                        row.remove();
                        // If cart is empty, show empty message
                        if (document.querySelectorAll('#cart-items tr').length === 0) {
                            document.getElementById('cart-items').innerHTML = '<tr><td colspan="5" class="text-center">Your cart is empty.</td></tr>';
                        }
                    });
                }
            });
        });
    }
    // Initialize stock updates for product grid
    function initializeStockUpdates() {
        // Update stock for all products on page load
        document.querySelectorAll('[id^="available-stock-"]').forEach(function(el) {
            const productId = el.id.replace('available-stock-', '');
            updateAvailableStock(productId);
        });
    }
    // Initialize all cart functionality
    function initialize() {
        if (isInitialized) {
            console.log('CartManager already initialized, skipping...');
            return;
        }
        
        console.log('Initializing CartManager...');
        setupQuantityControls();
        setupAddToCartButtons();
        setupRemoveFromCartButtons();
        initializeStockUpdates();
        isInitialized = true;
        console.log('CartManager initialized successfully');
    }
    // Public API
    return {
        addToCart,
        updateCart,
        removeFromCart,
        updateAvailableStock,
        setupQuantityControls,
        setupAddToCartButtons,
        setupRemoveFromCartButtons,
        initializeStockUpdates,
        initialize
    };
})();

// Auto-setup on DOMContentLoaded
if (typeof window !== 'undefined') {
    document.addEventListener('DOMContentLoaded', function() {
        CartManager.initialize();
    });
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = CartManager;
} 
function renderProducts() {
    const grid = document.getElementById('productGrid');
    if (!grid) return;
    
    const categoryFilter = document.getElementById('categoryFilter')?.value;
    
    let filtered = products;
    if (categoryFilter) {
        filtered = filtered.filter(p => p.category_id == categoryFilter);
    }
    
    grid.innerHTML = filtered.map(product => {
        const onClick = (product.variants && product.variants.length) 
            ? `showVariants(${product.id})` 
            : `addToCart(${product.id}, null, ${product.base_price}, '${product.name.replace(/'/g, "\\'")}', null, ${product.base_price})`;
        
        const hasStock = !product.variants || product.variants.length === 0 || product.variants.some(v => v.quantity > 0);
        const cardClass = hasStock ? 'product-card' : 'product-card opacity-50';
        
        return `
        <div class="col-6 col-md-4 col-xl-3">
            <div class="card ${cardClass} h-100 border-0 shadow-sm rounded-3" onclick="${onClick}" style="${!hasStock ? 'cursor:not-allowed;' : ''}">
                ${product.image ? `<img src="/storage/${product.image}" class="card-img-top rounded-top-3" alt="${product.name}" style="height: 100px; object-fit: cover;">` : '<div class="product-image-placeholder d-flex align-items-center justify-content-center bg-light rounded-top-3" style="height: 100px;"><i class="bi bi-image text-muted" style="font-size: 2rem;"></i></div>'}
                <div class="card-body p-2">
                    <h6 class="card-title mb-1 fw-semibold">${product.name}</h6>
                    <p class="product-price mb-1 fw-bold">Rs. ${parseFloat(product.base_price).toFixed(2)}</p>
                    ${product.category ? `<small class="badge bg-light text-dark">${product.category.name}</small>` : ''}
                </div>
            </div>
        </div>`;
    }).join('');
}

function showVariants(productId) {
    const product = products.find(p => p.id === productId);
    if (!product || !product.variants || product.variants.length === 0) return;
    
    const availableVariants = product.variants.filter(v => v.quantity > 0);
    
    if (availableVariants.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Out of Stock',
            text: 'All variants of this product are out of stock.',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    const productImage = product.image ? `/storage/${product.image}` : null;
    let html = `
        <div class="variant-modal-header mb-3 p-3 bg-light rounded-3">
            <div class="d-flex align-items-center gap-3">
                ${productImage ? `<img src="${productImage}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">` : '<div style="width: 60px; height: 60px; background: #e9ecef; border-radius: 8px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-image text-muted fs-4"></i></div>'}
                <div>
                    <h6 class="mb-1 fw-bold">${product.name}</h6>
                    <small class="text-muted">${availableVariants.length} variants available</small>
                </div>
            </div>
        </div>
        <div class="variant-list" style="max-height: 280px; overflow-y: auto; border: 1px solid #e9ecef; border-radius: 8px;">
    `;
    
    availableVariants.forEach((v, idx) => {
        const originalIndex = product.variants.indexOf(v);
        const colorHex = getColorHex(v.color);
        html += `
            <label class="d-flex align-items-center p-3 border-bottom variant-item" style="cursor:pointer; transition: background 0.2s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='white'">
                <input type="radio" name="variantSelect" value="${originalIndex}" class="me-3" style="width: 18px; height: 18px; accent-color: #357960;">
                <div class="d-flex align-items-center flex-grow-1 justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        ${colorHex ? `<span style="width: 24px; height: 24px; background: ${colorHex}; border-radius: 4px; border: 1px solid #dee2e6;"></span>` : ''}
                        <div>
                            <strong>${v.size || 'One Size'}</strong>
                            ${v.color ? `<span class="text-muted ms-1">${v.color}</span>` : ''}
                        </div>
                    </div>
                    <span class="badge" style="background: ${v.quantity <= 5 ? '#ffc107' : '#198754'}; color: ${v.quantity <= 5 ? '#000' : '#fff'};">${v.quantity} in stock</span>
                </div>
            </label>
        `;
    });
    html += '</div>';
    
    Swal.fire({
        title: 'Select Variant',
        html: html,
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-cart-plus me-1"></i> Add to Cart',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#357960',
        customClass: {
            confirmButton: 'rounded-2',
            cancelButton: 'rounded-2'
        },
        preConfirm: () => {
            const selected = document.querySelector('input[name="variantSelect"]:checked');
            if (!selected) {
                Swal.showValidationMessage('Please select a variant');
                return false;
            }
            return selected.value;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const idx = parseInt(result.value);
            const variant = product.variants[idx];
            const costPrice = variant.cost_price || variant.price;
            addToCart(product.id, variant.id, variant.price, product.name, (variant.size || '') + ' ' + (variant.color || ''), costPrice);
        }
    });
}

function getColorHex(colorName) {
    if (!colorName) return null;
    const colors = {
        'red': '#dc3545', 'blue': '#0d6efd', 'black': '#212529', 'white': '#f8f9fa',
        'green': '#198754', 'yellow': '#ffc107', 'orange': '#fd7e14', 'purple': '#6f42c1',
        'pink': '#d63384', 'gray': '#6c757d', 'grey': '#6c757d', 'navy': '#001f3f',
        'brown': '#795548', 'cream': '#fffdd0', 'beige': '#f5f5dc', 'maroon': '#800000',
        'olive': '#808000', 'teal': '#008080', 'cyan': '#0dcaf0', 'gold': '#ffd700',
        'silver': '#c0c0c0', 'magenta': '#ff00ff', 'lime': '#32cd32', 'aqua': '#00ffff'
    };
    return colors[colorName.toLowerCase()] || null;
}

function addToCart(productId, variantId, unitPrice, productName, variantName, costPrice) {
    const existingItem = cart.find(item => item.productId === productId && item.variantId === variantId);
    
    if (existingItem) {
        existingItem.quantity += 1;
        existingItem.subtotal = existingItem.quantity * existingItem.unitPrice;
    } else {
        cart.push({
            productId,
            variantId,
            productName,
            variantName,
            unitPrice: parseFloat(unitPrice),
            costPrice: parseFloat(costPrice) || parseFloat(unitPrice),
            quantity: 1,
            subtotal: parseFloat(unitPrice)
        });
    }
    
    renderCart();
}

function updateCartItemQuantity(index, newQty) {
    if (newQty < 1) {
        cart.splice(index, 1);
    } else {
        cart[index].quantity = newQty;
        cart[index].subtotal = cart[index].quantity * cart[index].unitPrice;
    }
    renderCart();
}

function renderCart() {
    const container = document.getElementById('cartItems');
    if (!container) return;
    
    if (cart.length === 0) {
        container.innerHTML = '<p class="text-muted text-center py-3">No items in cart</p>';
        updateTotals();
        return;
    }
    
    container.innerHTML = '';
    cart.forEach((item, index) => {
        const div = document.createElement('div');
        div.className = 'cart-item d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 p-2 border-bottom';
        
        const variantInfo = item.variantName ? `<small class="text-muted d-block">${item.variantName}</small>` : '';
        
        div.innerHTML = `
            <div class="cart-item-info flex-grow-1">
                <div class="fw-semibold">${item.productName}</div>
                ${variantInfo}
                <div class="d-flex align-items-center gap-2 mt-1">
                    <input type="number" class="form-control form-control-sm" style="width: 80px;" 
                        value="${item.unitPrice}" min="${item.costPrice}" step="0.01" 
                        onchange="updateCartItemPrice(${index}, this.value)">
                    <span class="text-muted">x ${item.quantity}</span>
                    <span class="badge bg-secondary">Rs. ${item.subtotal.toFixed(2)}</span>
                </div>
            </div>
            <div class="cart-item-actions d-flex align-items-center gap-1">
                <button class="btn btn-sm btn-outline-secondary" onclick="updateCartItemQuantity(${index}, ${item.quantity - 1})">-</button>
                <input type="number" class="form-control form-control-sm cart-qty" value="${item.quantity}" min="1" style="width: 50px;" onchange="updateCartItemQuantity(${index}, parseInt(this.value))">
                <button class="btn btn-sm btn-outline-secondary" onclick="updateCartItemQuantity(${index}, ${item.quantity + 1})">+</button>
                <button class="btn btn-sm btn-outline-danger" onclick="updateCartItemQuantity(${index}, 0)"><i class="bi bi-trash"></i></button>
            </div>
        `;
        container.appendChild(div);
    });
    
    updateTotals();
}

function updateCartItemPrice(index, newPrice) {
    const price = parseFloat(newPrice);
    const costPrice = cart[index].costPrice;
    
    if (price < costPrice) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Price',
            text: 'Price cannot be less than cost price: Rs. ' + costPrice.toFixed(2),
            confirmButtonText: 'OK'
        });
        renderCart();
        return;
    }
    
    cart[index].unitPrice = price;
    cart[index].subtotal = cart[index].quantity * price;
    renderCart();
}

function updateTotals() {
    const subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
    const discount = parseFloat(document.getElementById('cartDiscount')?.value) || 0;
    const total = subtotal - discount;
    const paid = parseFloat(document.getElementById('paidAmount')?.value) || 0;
    const change = paid - total;
    
    const cartSubtotalEl = document.getElementById('cartSubtotal');
    const cartTotalEl = document.getElementById('cartTotal');
    const changeAmountEl = document.getElementById('changeAmount');
    
    if (cartSubtotalEl) cartSubtotalEl.textContent = 'Rs. ' + subtotal.toFixed(2);
    if (cartTotalEl) cartTotalEl.textContent = 'Rs. ' + total.toFixed(2);
    if (changeAmountEl) changeAmountEl.textContent = 'Rs. ' + change.toFixed(2);
    
    const staffId = document.getElementById('saleStaff')?.value;
    const completeBtn = document.getElementById('completeSale');
    if (completeBtn) {
        completeBtn.disabled = cart.length === 0 || !staffId || paid < total;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.body.classList.add('pos-layout');
    
    document.getElementById('cartDiscount')?.addEventListener('input', updateTotals);
    document.getElementById('paidAmount')?.addEventListener('input', updateTotals);
    document.getElementById('saleStaff')?.addEventListener('change', updateTotals);
    document.getElementById('categoryFilter')?.addEventListener('change', renderProducts);

    document.getElementById('completeSale')?.addEventListener('click', async function() {
        const staffId = document.getElementById('saleStaff').value;
        const customerId = document.getElementById('saleCustomer').value || null;
        const discount = parseFloat(document.getElementById('cartDiscount').value) || 0;
        const paidAmount = parseFloat(document.getElementById('paidAmount').value) || 0;
        const paymentMethod = document.getElementById('paymentMethod').value;
        
        const subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
        const total = subtotal - discount;
        
        if (!staffId) {
            alert('Please select a staff member');
            return;
        }
        
        if (cart.length === 0) {
            alert('Cart is empty');
            return;
        }
        
        if (paidAmount < total) {
            alert('Insufficient payment amount');
            return;
        }
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const response = await fetch('/sales', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    staff_id: parseInt(staffId),
                    customer_id: customerId ? parseInt(customerId) : null,
                    subtotal: subtotal,
                    discount: discount,
                    total: total,
                    paid_amount: paidAmount,
                    change_amount: paidAmount - total,
                    payment_method: paymentMethod,
                    items: cart
                })
            });
            
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.error || 'Failed to complete sale');
            }
            
            Swal.fire({
                icon: 'success',
                title: 'Sale Completed',
                text: 'Sale completed successfully!',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            });
            cart = [];
            renderCart();
            document.getElementById('paidAmount').value = 0;
            updateTotals();
            if (data.products) {
                products = data.products;
                renderProducts();
            }
        } catch (error) {
            console.error('Error completing sale:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message,
                confirmButtonText: 'OK'
            });
        }
    });
});

function searchCustomer() {
    const phoneInput = document.getElementById('customerPhone');
    const customerInfo = document.getElementById('customerInfo');
    const customerHidden = document.getElementById('saleCustomer');
    const phone = phoneInput.value.trim();
    
    if (phone.length !== 10 || !/^\d+$/.test(phone)) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Number',
            text: 'Please enter a valid 10-digit phone number',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    fetch(`/customers/search?phone=${encodeURIComponent(phone)}`)
        .then(response => response.json())
        .then(data => {
                if (data.found) {
                customerInfo.innerHTML = `<i class="bi bi-check-circle me-1"></i> Found: ${data.customer.name}`;
                customerInfo.className = 'mt-2 text-success small';
                customerHidden.value = data.customer.id;
            } else {
                Swal.fire({
                    title: 'Customer Not Found',
                    html: `No customer found with phone: ${phone}<br><br>Would you like to create a new customer?`,
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Create',
                    cancelButtonText: 'No',
                    confirmButtonColor: '#357960'
                }).then((result) => {
                    if (result.isConfirmed) {
                        createNewCustomer(phone);
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error searching customer:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to search customer',
                confirmButtonText: 'OK'
            });
        });
}

function createNewCustomer(phone) {
    Swal.fire({
        title: 'Create New Customer',
        html: `
            <div class="text-start">
                <label class="form-label">Mobile Number</label>
                <input type="text" id="newCustomerPhone" class="form-control mb-3" value="${phone}" readonly>
                <label class="form-label">Customer Name *</label>
                <input type="text" id="newCustomerName" class="form-control" placeholder="Enter customer name">
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Create',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#357960',
        preConfirm: () => {
            const name = document.getElementById('newCustomerName').value.trim();
            if (!name) {
                Swal.showValidationMessage('Please enter customer name');
                return false;
            }
            return { name: name, phone: phone };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch('/customers', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(result.value)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const customerInfo = document.getElementById('customerInfo');
                    const customerHidden = document.getElementById('saleCustomer');
                    customerHidden.value = data.customer.id;
                    customerInfo.innerHTML = `<i class="bi bi-check-circle me-1"></i> Created: ${data.customer.name}`;
                    customerInfo.className = 'mt-2 text-success small';
                    Swal.fire({
                        icon: 'success',
                        title: 'Customer Created',
                        text: `${data.customer.name} has been added successfully`,
                        confirmButtonText: 'OK',
                        timer: 2000
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: data.errors?.phone?.[0] || 'Failed to add customer',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error creating customer:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to create customer',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}
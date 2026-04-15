function showVariants(productId) {
    const product = products.find(p => p.id === productId);
    if (!product || !product.variants || product.variants.length === 0) return;
    
    const variantOptions = product.variants.map((v, i) => {
        return {
            text: (v.size || '') + ' ' + (v.color || '') + ' - Rs. ' + parseFloat(v.price).toFixed(2),
            value: i
        };
    });
    
    Swal.fire({
        title: 'Select Variant',
        text: product.name,
        input: 'radio',
        inputOptions: Object.fromEntries(variantOptions.map(v => [v.value, v.text])),
        inputValidator: (value) => {
            if (!value) return 'Please select a variant';
            return null;
        },
        confirmButtonText: 'Add to Cart',
        showCancelButton: true,
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const idx = parseInt(result.value);
            const variant = product.variants[idx];
            const costPrice = variant.cost_price || variant.price;
            addToCart(product.id, variant.id, variant.price, product.name, (variant.size || '') + ' ' + (variant.color || ''), costPrice);
        }
    });
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
/**
 * إدارة السلة مع AJAX
 */

// تحديث عداد السلة
function updateCartBadge(count) {
    // Desktop badge
    let badge = document.getElementById('cartBadge');
    if (badge) {
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    } else if (count > 0) {
        // إنشاء badge جديد
        const cartIcon = document.querySelector('.cart-icon');
        if (cartIcon) {
            badge = document.createElement('span');
            badge.className = 'cart-badge';
            badge.id = 'cartBadge';
            badge.textContent = count;
            cartIcon.appendChild(badge);
        }
    }
    
    // Mobile badge
    let mobileBadge = document.getElementById('cartBadgeMobile');
    if (mobileBadge) {
        if (count > 0) {
            mobileBadge.textContent = count;
            mobileBadge.style.display = 'flex';
        } else {
            mobileBadge.style.display = 'none';
        }
    } else if (count > 0) {
        // إنشاء mobile badge جديد
        const mobileCartIcon = document.querySelector('.mobile-bottom-nav .cart-icon');
        if (mobileCartIcon) {
            mobileBadge = document.createElement('span');
            mobileBadge.className = 'cart-badge';
            mobileBadge.id = 'cartBadgeMobile';
            mobileBadge.textContent = count;
            mobileCartIcon.appendChild(mobileBadge);
        }
    }
}

// إضافة منتج للسلة
async function addToCart(productId, quantity = 1) {
    try {
        const response = await fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        });

        const data = await response.json();

        if (data.success) {
            // تحديث عداد السلة
            updateCartBadge(data.cartCount);
            
            // عرض notification
            showNotification('تم إضافة المنتج إلى السلة بنجاح', 'success');
            
            return data;
        } else {
            showNotification(data.message || 'حدث خطأ أثناء الإضافة', 'error');
            return null;
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        showNotification('حدث خطأ أثناء الإضافة', 'error');
        return null;
    }
}

// تحديث كمية منتج في السلة
async function updateCartItem(itemId, quantity) {
    try {
        const response = await fetch(`/cart/update/${itemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ quantity: quantity })
        });

        const data = await response.json();

        if (data.success) {
            return data;
        } else {
            showNotification(data.message || 'حدث خطأ أثناء التحديث', 'error');
            return null;
        }
    } catch (error) {
        console.error('Error updating cart:', error);
        showNotification('حدث خطأ أثناء التحديث', 'error');
        return null;
    }
}

// حذف منتج من السلة
async function removeFromCart(itemId) {
    try {
        const response = await fetch(`/cart/remove/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            // تحديث عداد السلة
            updateCartBadge(data.cartCount);
            
            showNotification('تم حذف المنتج من السلة', 'success');
            
            return data;
        } else {
            showNotification(data.message || 'حدث خطأ أثناء الحذف', 'error');
            return null;
        }
    } catch (error) {
        console.error('Error removing from cart:', error);
        showNotification('حدث خطأ أثناء الحذف', 'error');
        return null;
    }
}

// عرض notification
function showNotification(message, type = 'success') {
    // إزالة أي notification سابق
    const existingNotification = document.querySelector('.notification-toast');
    if (existingNotification) {
        existingNotification.remove();
    }

    // إنشاء notification جديد
    const notification = document.createElement('div');
    notification.className = `notification-toast notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-circle-fill'}"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    // عرض مع أنيميشن
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);

    // إخفاء بعد 3 ثواني
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// CSS للـ notifications (سيتم إضافته في layout)
const notificationStyles = `
    .notification-toast {
        position: fixed;
        top: 80px;
        right: 20px;
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transform: translateX(400px);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .notification-toast.show {
        transform: translateX(0);
        opacity: 1;
    }

    .notification-toast.notification-success {
        border-right: 4px solid #25D366;
    }

    .notification-toast.notification-error {
        border-right: 4px solid #DC3545;
    }

    .notification-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .notification-content i {
        font-size: 1.5rem;
    }

    .notification-success .notification-content i {
        color: #25D366;
    }

    .notification-error .notification-content i {
        color: #DC3545;
    }

    .notification-content span {
        font-size: 0.95rem;
        font-weight: 500;
        color: #333;
    }

    @media (max-width: 768px) {
        .notification-toast {
            right: 10px;
            left: 10px;
            top: 70px;
        }
    }
`;

// إضافة CSS للصفحة
if (!document.querySelector('#notification-styles')) {
    const styleSheet = document.createElement('style');
    styleSheet.id = 'notification-styles';
    styleSheet.textContent = notificationStyles;
    document.head.appendChild(styleSheet);
}

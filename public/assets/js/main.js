/**
 * DaNang PC - Main Client JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {
  // 1. Toast Notification Helper
  function showToast(message, type = 'success') {
    let container = document.getElementById('toast-container');
    if (!container) {
      container = document.createElement('div');
      container.id = 'toast-container';
      container.style.cssText = `
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-width: 380px;
      `;
      document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    const bgColor = type === 'success' ? '#0f172a' : '#b91c1c';
    toast.style.cssText = `
      background-color: ${bgColor};
      color: #ffffff;
      padding: 14px 18px;
      border-radius: 8px;
      font-size: 0.9rem;
      font-weight: 500;
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
      display: flex;
      align-items: center;
      gap: 12px;
      animation: toastIn 0.3s ease;
      border-left: 4px solid ${type === 'success' ? '#22c55e' : '#f87171'};
    `;

    const icon = type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation';
    toast.innerHTML = `<i class="fa-solid ${icon}"></i> <span>${message}</span>`;
    container.appendChild(toast);

    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(10px)';
      toast.style.transition = 'all 0.3s ease';
      setTimeout(() => toast.remove(), 300);
    }, 3500);
  }

  // 2. AJAX Add To Cart
  document.querySelectorAll('.btn-add-cart-ajax').forEach(function (button) {
    button.addEventListener('click', function (e) {
      e.preventDefault();
      const form = this.closest('form');
      if (!form) return;

      const formData = new FormData(form);
      const originalText = this.innerHTML;

      this.disabled = true;
      this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang thêm...';

      fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        }
      })
      .then(res => res.json())
      .then(data => {
        this.disabled = false;
        this.innerHTML = originalText;

        if (data.success) {
          showToast(data.message, 'success');
          // Update cart badge counter in header
          const badge = document.querySelector('.cart-badge');
          if (badge) {
            badge.textContent = data.cart_count;
            badge.style.display = data.cart_count > 0 ? 'flex' : 'none';
          }
        } else {
          showToast(data.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng.', 'error');
        }
      })
      .catch(err => {
        this.disabled = false;
        this.innerHTML = originalText;
        showToast('Lỗi kết nối máy chủ. Vui lòng thử lại.', 'error');
      });
    });
  });

  // 3. Quantity Increment / Decrement Buttons
  document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      const input = this.parentElement.querySelector('.qty-input');
      if (!input) return;

      let val = parseInt(input.value) || 1;
      const max = parseInt(input.getAttribute('max')) || 999;
      const min = parseInt(input.getAttribute('min')) || 1;

      if (this.dataset.action === 'plus') {
        if (val < max) val++;
      } else if (this.dataset.action === 'minus') {
        if (val > min) val--;
      }

      input.value = val;

      // If inside a cart table auto-submit form
      const cartForm = this.closest('.cart-update-form');
      if (cartForm) {
        cartForm.submit();
      }
    });
  });

  // 4. Payment Method Switcher in Checkout Page
  const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
  const bankInfoBox = document.getElementById('bank-transfer-info');

  if (bankInfoBox && paymentRadios.length > 0) {
    function toggleBankInfo() {
      const checked = document.querySelector('input[name="payment_method"]:checked');
      if (checked && checked.value === 'bank_transfer') {
        bankInfoBox.style.display = 'block';
      } else {
        bankInfoBox.style.display = 'none';
      }
    }
    paymentRadios.forEach(r => r.addEventListener('change', toggleBankInfo));
    toggleBankInfo();
  }

  // 5. Confirmation helper
  document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', function (e) {
      const msg = this.dataset.confirm || 'Bạn có chắc chắn muốn thực hiện hành động này?';
      if (!confirm(msg)) {
        e.preventDefault();
      }
    });
  });
});

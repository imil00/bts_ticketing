// Mobile Menu Toggle
document.addEventListener('DOMContentLoaded', function () {
  const menuToggle = document.querySelector('.menu-toggle');
  const navMenu = document.querySelector('.nav-menu');
  const mobileBodyOverlay = document.getElementById('mobile-body-overlay');

  if (menuToggle && navMenu) {
    menuToggle.addEventListener('click', function () {
      navMenu.classList.toggle('active');
      menuToggle.innerHTML = navMenu.classList.contains('active') ? '<i class="icon icon-close"></i>' : '<i class="icon icon-menu"></i>';

      if (mobileBodyOverlay) {
        mobileBodyOverlay.style.display = navMenu.classList.contains('active') ? 'block' : 'none';
      }
    });

    if (mobileBodyOverlay) {
      mobileBodyOverlay.addEventListener('click', function () {
        navMenu.classList.remove('active');
        menuToggle.innerHTML = '<i class="icon icon-menu"></i>';
        mobileBodyOverlay.style.display = 'none';
      });
    }
  }

  // Modal Functions
  window.openModal = function (modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }
  };

  window.closeModal = function (modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.classList.remove('active');
      document.body.style.overflow = 'auto';
    }
  };

  document.querySelectorAll('.modal').forEach((modal) => {
    modal.addEventListener('click', function (e) {
      if (e.target === this) {
        this.classList.remove('active');
        document.body.style.overflow = 'auto';
      }
    });
  });

  // Form Validation
  const registerForm = document.getElementById('registerForm');
  if (registerForm) {
    registerForm.addEventListener('submit', function (e) {
      const password = document.getElementById('reg-password');
      const confirmPassword = document.getElementById('reg-confirm-password');

      if (password && confirmPassword && password.value !== confirmPassword.value) {
        e.preventDefault();
        alert('❌ Passwords do not match!');
        return false;
      }

      if (password && password.value.length < 6) {
        e.preventDefault();
        alert('❌ Password must be at least 6 characters!');
        return false;
      }

      // Show loading
      const submitBtn = this.querySelector('button[type="submit"]');
      if (submitBtn) {
        submitBtn.innerHTML = '<i class="icon icon-spinner"></i> Processing...';
        submitBtn.disabled = true;
      }
    });
  }

  // Ticket Quantity Calculation
  document.querySelectorAll('input[name="jml_kursi"]').forEach((input) => {
    input.addEventListener('change', function () {
      const form = this.closest('form');
      const priceInput = form.querySelector('input[name="biaya"]');
      const totalDisplay = form.querySelector('.total-price');

      if (priceInput && totalDisplay) {
        const price = parseInt(priceInput.value) || 0;
        const quantity = parseInt(this.value) || 0;
        const total = price * quantity;
        const formatted = 'Rp ' + total.toLocaleString('id-ID');
        totalDisplay.textContent = 'Total: ' + formatted;
      }
    });
  });

  // Password Strength Indicator
  const passwordInput = document.getElementById('reg-password');
  const strengthBar = document.getElementById('password-strength-bar');

  if (passwordInput && strengthBar) {
    passwordInput.addEventListener('input', function () {
      const password = this.value;
      let strength = 0;

      if (password.length >= 6) strength = 1;
      if (password.length >= 8) strength = 2;
      if (password.length >= 10 && /[A-Z]/.test(password) && /[0-9]/.test(password)) strength = 3;

      strengthBar.className = 'strength-bar';
      strengthBar.style.width = strength * 33.33 + '%';

      if (strength === 1) strengthBar.style.background = '#dc3545';
      if (strength === 2) strengthBar.style.background = '#ffc107';
      if (strength === 3) strengthBar.style.background = '#28a745';
    });
  }

  // Password Match Check
  const confirmPasswordInput = document.getElementById('reg-confirm-password');
  const matchMessage = document.getElementById('password-match-message');

  if (confirmPasswordInput && matchMessage) {
    confirmPasswordInput.addEventListener('input', function () {
      const password = document.getElementById('reg-password')?.value || '';
      const confirm = this.value;

      if (confirm === '') {
        matchMessage.textContent = '';
        matchMessage.className = '';
      } else if (password === confirm) {
        matchMessage.textContent = '✓ Passwords match';
        matchMessage.className = 'text-success';
      } else {
        matchMessage.textContent = '✗ Passwords do not match';
        matchMessage.className = 'text-danger';
      }
    });
  }

  // Back to Top Button
  const backToTop = document.querySelector('.back-to-top');
  if (backToTop) {
    window.addEventListener('scroll', function () {
      if (window.pageYOffset > 100) {
        backToTop.style.display = 'flex';
      } else {
        backToTop.style.display = 'none';
      }
    });

    backToTop.addEventListener('click', function (e) {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // Smooth Scrolling for Anchor Links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', function (e) {
      const targetId = this.getAttribute('href');
      if (targetId === '#') return;

      const target = document.querySelector(targetId);
      if (target) {
        e.preventDefault();
        const headerHeight = document.querySelector('.header')?.offsetHeight || 70;
        const targetPosition = target.offsetTop - headerHeight;

        window.scrollTo({
          top: targetPosition,
          behavior: 'smooth',
        });

        if (navMenu && navMenu.classList.contains('active')) {
          navMenu.classList.remove('active');
          menuToggle.innerHTML = '<i class="icon icon-menu"></i>';
          if (mobileBodyOverlay) {
            mobileBodyOverlay.style.display = 'none';
          }
        }
      }
    });
  });

  // Initialize tooltips
  document.querySelectorAll('[data-tooltip]').forEach((element) => {
    element.addEventListener('mouseenter', function () {
      const tooltip = document.createElement('div');
      tooltip.className = 'tooltip';
      tooltip.textContent = this.getAttribute('data-tooltip');
      document.body.appendChild(tooltip);

      const rect = this.getBoundingClientRect();
      tooltip.style.position = 'fixed';
      tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';
      tooltip.style.left = rect.left + rect.width / 2 - tooltip.offsetWidth / 2 + 'px';
      tooltip.style.zIndex = '10000';

      this._tooltip = tooltip;
    });

    element.addEventListener('mouseleave', function () {
      if (this._tooltip) {
        this._tooltip.remove();
        this._tooltip = null;
      }
    });
  });
});

// Utility Functions
function formatCurrency(amount) {
  return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function showAlert(message, type = 'info') {
  const alert = document.createElement('div');
  alert.className = `alert alert-${type}`;
  alert.innerHTML = `
        <i class="icon ${type === 'success' ? 'icon-check' : type === 'danger' ? 'icon-times' : 'icon-info'}"></i>
        ${message}
    `;

  document.body.appendChild(alert);

  setTimeout(() => {
    alert.remove();
  }, 5000);
}

function confirmAction(message) {
  return confirm(message);
}

// Ticket Functions
function calculateTicketTotal(form) {
  const quantity = form.querySelector('input[name="jml_kursi"]').value;
  const price = form.querySelector('input[name="biaya"]').value;
  return quantity * price;
}

function printTicket(ticketId) {
  window.open(`my_tickets.php?print=${ticketId}`, '_blank');
}

function downloadTicket(ticketId) {
  window.open(`my_tickets.php?print=${ticketId}&download=true`, '_blank');
}

window.openModal = function (modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
};

window.closeModal = function (modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.remove('active');
    document.body.style.overflow = 'auto';
  }
};

function openVideoModal() {
  const modal = document.getElementById('videoModal');
  if (modal) {
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
}

function closeVideoModal() {
  const modal = document.getElementById('videoModal');
  if (modal) {
    modal.classList.remove('active');
    document.body.style.overflow = 'auto';
    const iframe = modal.querySelector('iframe');
    if (iframe) {
      iframe.src = iframe.src;
    }
  }
}

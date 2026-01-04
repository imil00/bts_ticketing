// Admin Panel JavaScript

document.addEventListener('DOMContentLoaded', function () {
  // Admin Menu Toggle
  const adminMenuToggle = document.querySelector('.admin-menu-toggle');
  const adminNav = document.querySelector('.admin-nav');

  if (adminMenuToggle && adminNav) {
    adminMenuToggle.addEventListener('click', function () {
      adminNav.classList.toggle('active');
    });
  }

  // Initialize Data Tables
  const initDataTables = () => {
    const tables = document.querySelectorAll('.data-table');
    tables.forEach((table) => {
      // Add sorting functionality
      const headers = table.querySelectorAll('th[data-sort]');
      headers.forEach((header) => {
        header.style.cursor = 'pointer';
        header.addEventListener('click', function () {
          const column = this.getAttribute('data-sort');
          const direction = this.getAttribute('data-direction') || 'asc';
          const newDirection = direction === 'asc' ? 'desc' : 'asc';
          this.setAttribute('data-direction', newDirection);

          sortTable(table, column, newDirection);
        });
      });
    });
  };

  const sortTable = (table, column, direction) => {
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    rows.sort((a, b) => {
      const aVal = a.querySelector(`td:nth-child(${getColumnIndex(table, column)})`).textContent;
      const bVal = b.querySelector(`td:nth-child(${getColumnIndex(table, column)})`).textContent;

      if (direction === 'asc') {
        return aVal.localeCompare(bVal);
      } else {
        return bVal.localeCompare(aVal);
      }
    });

    // Clear and re-add sorted rows
    while (tbody.firstChild) {
      tbody.removeChild(tbody.firstChild);
    }

    rows.forEach((row) => tbody.appendChild(row));
  };

  const getColumnIndex = (table, columnName) => {
    const headers = table.querySelectorAll('th');
    for (let i = 0; i < headers.length; i++) {
      if (headers[i].getAttribute('data-sort') === columnName) {
        return i + 1;
      }
    }
    return 1;
  };

  // Pagination for tables
  const initPagination = () => {
    document.querySelectorAll('.table-container').forEach((container) => {
      const rows = container.querySelectorAll('tbody tr');
      const rowsPerPage = 10;
      const pageCount = Math.ceil(rows.length / rowsPerPage);

      if (pageCount > 1) {
        createPaginationControls(container, rows, rowsPerPage, pageCount);
      }
    });
  };

  const createPaginationControls = (container, rows, rowsPerPage, pageCount) => {
    const controls = document.createElement('div');
    controls.className = 'pagination-controls';

    for (let i = 1; i <= pageCount; i++) {
      const button = document.createElement('button');
      button.textContent = i;
      button.className = 'page-btn';
      if (i === 1) button.classList.add('active');

      button.addEventListener('click', function () {
        showPage(rows, i, rowsPerPage);
        document.querySelectorAll('.page-btn').forEach((btn) => btn.classList.remove('active'));
        this.classList.add('active');
      });

      controls.appendChild(button);
    }

    container.appendChild(controls);
    showPage(rows, 1, rowsPerPage);
  };

  const showPage = (rows, page, rowsPerPage) => {
    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;

    rows.forEach((row, index) => {
      row.style.display = index >= start && index < end ? '' : 'none';
    });
  };

  // Search functionality
  const initSearch = () => {
    document.querySelectorAll('.search-box').forEach((searchBox) => {
      const input = searchBox.querySelector('input');
      const tableId = searchBox.getAttribute('data-table');
      const table = document.getElementById(tableId);

      if (input && table) {
        input.addEventListener('input', function () {
          const searchTerm = this.value.toLowerCase();
          const rows = table.querySelectorAll('tbody tr');

          rows.forEach((row) => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
          });
        });
      }
    });
  };

  // Modal functions for admin
  window.adminOpenModal = function (modalId) {
    openModal(modalId);
  };

  window.adminCloseModal = function (modalId) {
    closeModal(modalId);
  };

  // Delete confirmation
  document.querySelectorAll('.btn-delete').forEach((btn) => {
    btn.addEventListener('click', function (e) {
      if (!confirm('Are you sure you want to delete this item?')) {
        e.preventDefault();
      }
    });
  });

  // Export functionality
  window.exportTable = function (tableId, format = 'csv') {
    const table = document.getElementById(tableId);
    if (!table) return;

    let data = '';
    const rows = table.querySelectorAll('tr');

    rows.forEach((row) => {
      const cells = row.querySelectorAll('th, td');
      const rowData = Array.from(cells)
        .map((cell) => {
          let text = cell.textContent.trim();
          // Escape quotes for CSV
          if (format === 'csv') {
            if (text.includes(',') || text.includes('"') || text.includes('\n')) {
              text = '"' + text.replace(/"/g, '""') + '"';
            }
          }
          return text;
        })
        .join(format === 'csv' ? ',' : '\t');

      data += rowData + '\n';
    });

    if (format === 'csv') {
      downloadFile(data, 'export.csv', 'text/csv');
    } else {
      downloadFile(data, 'export.txt', 'text/plain');
    }
  };

  const downloadFile = (data, filename, type) => {
    const blob = new Blob([data], { type: type });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
  };

  // Initialize all admin functionality
  initDataTables();
  initPagination();
  initSearch();

  // Real-time updates for dashboard
  if (document.getElementById('stats')) {
    setInterval(() => {
      // In a real system, you would fetch updated stats from server
      console.log('Checking for updates...');
    }, 30000); // Every 30 seconds
  }
});

// Admin-specific utility functions
function showAdminAlert(message, type = 'info') {
  const alert = document.createElement('div');
  alert.className = `admin-alert alert-${type}`;
  alert.innerHTML = `
        <div class="alert-content">
            <i class="icon ${type === 'success' ? 'icon-check' : type === 'error' ? 'icon-times' : 'icon-info'}"></i>
            <span>${message}</span>
            <button class="alert-close" onclick="this.parentElement.parentElement.remove()">
                <i class="icon icon-close"></i>
            </button>
        </div>
    `;

  document.querySelector('.admin-main').prepend(alert);

  setTimeout(() => {
    if (alert.parentElement) {
      alert.remove();
    }
  }, 5000);
}

function updateStatCard(cardId, newValue) {
  const card = document.getElementById(cardId);
  if (card) {
    const numberElement = card.querySelector('.admin-stat-number');
    if (numberElement) {
      const oldValue = parseInt(numberElement.textContent.replace(/,/g, ''));
      const newNum = parseInt(newValue);

      // Animate number change
      animateNumber(numberElement, oldValue, newNum, 1000);
    }
  }
}

function animateNumber(element, start, end, duration) {
  const startTime = performance.now();
  const updateValue = (currentTime) => {
    const elapsed = currentTime - startTime;
    const progress = Math.min(elapsed / duration, 1);

    const current = Math.floor(start + (end - start) * progress);
    element.textContent = current.toLocaleString('id-ID');

    if (progress < 1) {
      requestAnimationFrame(updateValue);
    }
  };

  requestAnimationFrame(updateValue);
}

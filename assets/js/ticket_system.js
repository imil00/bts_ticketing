// Ticket System JavaScript

// Function to view ticket preview in modal
function viewTicketPreview(ticketId) {
  const modal = document.getElementById('ticketPreviewModal');
  const modalContent = document.getElementById('ticketPreviewContent');

  if (!modal || !modalContent) {
    console.error('Modal elements not found');
    return;
  }

  // Show loading
  modalContent.innerHTML = `
        <div style="text-align: center; padding: 60px 20px;">
            <div style="display: inline-block; width: 60px; height: 60px; border: 6px solid #f3f3f3; border-top: 6px solid #8a2be2; border-radius: 50%; animation: spin 1s linear infinite;"></div>
            <p style="margin-top: 20px; color: var(--text-muted);">Loading ticket preview...</p>
        </div>
    `;

  // Open modal
  modal.classList.add('active');
  document.body.style.overflow = 'hidden';

  // Fetch ticket data
  fetch(`process/ticketDataAPI.php?id=${ticketId}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.error) {
        throw new Error(data.error);
      }

      const ticket = data.ticket;

      // Render ticket preview
      modalContent.innerHTML = `
                <div style="max-width: 700px; margin: 0 auto;">
                    <!-- Ticket Header -->
                    <div style="background: linear-gradient(135deg, ${ticket.colors.primary}, ${
        ticket.colors.secondary
      }); padding: 40px 20px; text-align: center; border-radius: 15px 15px 0 0; color: white;">
                        <h2 style="margin: 0 0 10px 0; font-size: 2.5rem; font-weight: 900;">🎤 BTS LOVE YOURSELF</h2>
                        <p style="margin: 0; font-size: 1.1rem;">December 15-17, 2026 | Gelora Bung Karno</p>
                    </div>
                    
                    <!-- Ticket Body -->
                    <div style="background: var(--bts-dark-tertiary); padding: 30px; border-radius: 0 0 15px 15px;">
                        <!-- Transaction Info -->
                        <div style="text-align: center; margin-bottom: 30px; padding: 20px; background: rgba(138, 43, 226, 0.1); border-radius: 10px;">
                            <p style="color: var(--text-muted); margin-bottom: 5px;">Transaction ID</p>
                            <h3 style="margin: 0; font-size: 2rem; background: var(--bts-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                TRX${String(ticket.id_transaksi).padStart(6, '0')}
                            </h3>
                        </div>
                        
                        <!-- Ticket Details Grid -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                            <div>
                                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 5px;">Ticket Type</p>
                                <p style="color: var(--text-light); font-weight: bold; font-size: 1.1rem;">${ticket.kelas}</p>
                            </div>
                            <div>
                                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 5px;">Quantity</p>
                                <p style="color: var(--text-light); font-weight: bold; font-size: 1.1rem;">${ticket.jml_kursi} Ticket(s)</p>
                            </div>
                            <div>
                                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 5px;">Customer</p>
                                <p style="color: var(--text-light); font-weight: bold;">${ticket.nama_lengkap}</p>
                            </div>
                            <div>
                                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 5px;">ARMY ID</p>
                                <p style="color: var(--text-light); font-weight: bold;">#${ticket.army_id}</p>
                            </div>
                        </div>
                        
                        <!-- Seat Information -->
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 30px; padding: 25px; background: linear-gradient(135deg, ${
                          ticket.colors.primary
                        }, ${ticket.colors.secondary}); border-radius: 15px; text-align: center; color: white;">
                            <div>
                                <p style="font-size: 0.85rem; margin-bottom: 5px; opacity: 0.9;">SEAT ROW</p>
                                <p style="font-size: 2rem; font-weight: 900; margin: 0;">${ticket.seat_row}</p>
                            </div>
                            <div>
                                <p style="font-size: 0.85rem; margin-bottom: 5px; opacity: 0.9;">SEAT #</p>
                                <p style="font-size: 2rem; font-weight: 900; margin: 0;">${ticket.seat_number}</p>
                            </div>
                            <div>
                                <p style="font-size: 0.85rem; margin-bottom: 5px; opacity: 0.9;">GATE</p>
                                <p style="font-size: 2rem; font-weight: 900; margin: 0;">${ticket.gate}</p>
                            </div>
                        </div>
                        
                        <!-- Price -->
                        <div style="text-align: center; padding: 25px; background: rgba(138, 43, 226, 0.1); border-radius: 15px; margin-bottom: 30px;">
                            <p style="color: var(--text-muted); margin-bottom: 5px;">Total Amount</p>
                            <p style="font-size: 2.5rem; font-weight: 900; margin: 0; background: var(--bts-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                Rp ${ticket.total_formatted}
                            </p>
                        </div>
                        
                        <!-- Additional Info -->
                        <div style="padding: 20px; background: rgba(138, 43, 226, 0.05); border-radius: 10px; border-left: 4px solid ${ticket.colors.primary};">
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 8px;">
                                <strong>Purchase Date:</strong> ${ticket.tanggal_transaksi}
                            </p>
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 8px;">
                                <strong>Email:</strong> ${ticket.email}
                            </p>
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">
                                <strong>Phone:</strong> ${ticket.nomor_telp}
                            </p>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 30px;">
                            <a href="process/ticketPrint.php?id=${ticket.id_transaksi}" target="_blank" class="btn" style="text-align: center; padding: 15px;">
                                🖨️ Print Full Ticket
                            </a>
                            <button onclick="closeModal('ticketPreviewModal')" class="btn" style="background: #6c757d; padding: 15px;">
                                ❌ Close Preview
                            </button>
                        </div>
                    </div>
                </div>
            `;
    })
    .catch((error) => {
      console.error('Error loading ticket:', error);
      modalContent.innerHTML = `
                <div style="text-align: center; padding: 60px 20px;">
                    <div style="font-size: 80px; margin-bottom: 20px; opacity: 0.3;">❌</div>
                    <h3 style="color: var(--text-light); margin-bottom: 15px;">Failed to Load Ticket</h3>
                    <p style="color: var(--text-muted); margin-bottom: 30px;">
                        ${error.message || 'An error occurred while loading the ticket.'}
                    </p>
                    <button onclick="closeModal('ticketPreviewModal')" class="btn">
                        Close
                    </button>
                </div>
            `;
    });
}

// Function to close any modal
function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.remove('active');
    document.body.style.overflow = 'auto';
  }
}

// Add spin animation for loading spinner
if (!document.getElementById('spinner-style')) {
  const style = document.createElement('style');
  style.id = 'spinner-style';
  style.textContent = `
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
  document.head.appendChild(style);
}

// Export functions to window for global access
window.viewTicketPreview = viewTicketPreview;
window.closeModal = closeModal;

// Initialize on page load
document.addEventListener('DOMContentLoaded', function () {
  console.log('Ticket system initialized');

  // Close modal when clicking outside
  document.querySelectorAll('.modal').forEach((modal) => {
    modal.addEventListener('click', function (e) {
      if (e.target === this) {
        this.classList.remove('active');
        document.body.style.overflow = 'auto';
      }
    });
  });

  // Close modal with Escape key
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      const activeModal = document.querySelector('.modal.active');
      if (activeModal) {
        activeModal.classList.remove('active');
        document.body.style.overflow = 'auto';
      }
    }
  });
});

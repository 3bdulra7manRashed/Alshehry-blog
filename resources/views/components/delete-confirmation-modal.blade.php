{{--
    Reusable Delete Confirmation Modal Component
    
    Usage:
    1. Include this component in your Blade view: <x-delete-confirmation-modal />
    2. Add class="js-confirm" to any delete button
    3. Add data-confirm-message="Your message here" to customize the message
    
    Features:
    - RTL aligned
    - ESC key closes modal
    - Backdrop click closes modal
    - Focus trap inside modal
    - Proper ARIA attributes for accessibility
    - focus-visible for keyboard-only focus indicators
--}}

<!-- Delete Confirmation Modal -->
<div id="deleteModal" 
     class="fixed inset-0 bg-gray-900/50 hidden items-center justify-center z-50 backdrop-blur-sm"
     style="display: none;"
     role="dialog"
     aria-modal="true"
     aria-labelledby="deleteModalTitle"
     aria-describedby="deleteModalMessage">
    
    {{-- 
        Modal Content Container
        - tabindex="-1" allows programmatic focus without triggering focus-visible
        - This receives initial focus instead of buttons to avoid unwanted focus rings
    --}}
    <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full mx-4 transform transition-all scale-95 opacity-0 outline-none"
         id="deleteModalContent"
         tabindex="-1">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
            <h3 id="deleteModalTitle" class="text-lg font-bold text-gray-900">تأكيد الإجراء</h3>
            <button type="button" 
                    id="deleteModalCloseBtn"
                    class="text-gray-400 hover:text-gray-600 transition-colors rounded-lg p-1 hover:bg-gray-100 
                           focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-1"
                    aria-label="إغلاق">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="px-5 py-6">
            <div class="flex items-start gap-4">
                <!-- Warning Icon -->
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <!-- Message -->
                <div class="flex-1 pt-1">
                    <p id="deleteModalMessage" class="text-gray-700 text-base leading-relaxed">
                        <!-- Message will be set by JavaScript -->
                    </p>
                </div>
            </div>
        </div>

        <!-- Modal Footer (RTL: Cancel on left, Confirm on right) -->
        <div class="flex items-center justify-center gap-3 px-5 py-4 border-t border-gray-100 bg-gray-50/50 rounded-b-xl">
            <!-- Confirm (Destructive) -->
            <button type="button" 
                    id="deleteModalConfirmBtn"
                    class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium text-sm transition-colors 
                           focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2">
                تأكيد
            </button>
            <!-- Cancel (Safe) -->
            <button type="button" 
                    id="deleteModalCancelBtn"
                    class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-sm transition-colors 
                           focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2">
                إلغاء
            </button>
        </div>
    </div>
</div>

@pushOnce('scripts')
<script>
(function() {
    'use strict';
    
    let deleteFormToSubmit = null;
    let previouslyFocusedElement = null;
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('deleteModalContent');
    const messageEl = document.getElementById('deleteModalMessage');
    const cancelBtn = document.getElementById('deleteModalCancelBtn');
    const confirmBtn = document.getElementById('deleteModalConfirmBtn');
    const closeBtn = document.getElementById('deleteModalCloseBtn');
    
    // Focusable elements inside modal for focus trap
    const focusableElements = [closeBtn, confirmBtn, cancelBtn].filter(Boolean);
    
    /**
     * Show the delete confirmation modal
     * @param {HTMLFormElement} form - The form to submit on confirm
     * @param {string} message - The confirmation message to display
     */
    window.showDeleteModal = function(form, message) {
        deleteFormToSubmit = form;
        previouslyFocusedElement = document.activeElement;
        
        // Set message
        if (messageEl) {
            messageEl.textContent = message || 'هل أنت متأكد من حذف هذا العنصر؟';
        }
        
        // Show modal
        if (modal) {
            modal.style.display = 'flex';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            
            // Animate in
            requestAnimationFrame(() => {
                if (modalContent) {
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                }
            });
            
            // Focus on modal content container (not buttons)
            // This allows screen readers to announce the dialog
            // without showing a visual focus ring on buttons
            setTimeout(() => {
                if (modalContent) {
                    modalContent.focus();
                }
            }, 100);
        }
    };
    
    /**
     * Close the delete confirmation modal
     */
    window.closeDeleteModal = function() {
        deleteFormToSubmit = null;
        
        if (modal && modalContent) {
            // Animate out
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.style.display = 'none';
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
                
                // Restore focus to previously focused element
                if (previouslyFocusedElement && previouslyFocusedElement.focus) {
                    previouslyFocusedElement.focus();
                }
            }, 150);
        }
    };
    
    /**
     * Confirm and execute the delete action
     */
    window.confirmDelete = function() {
        if (deleteFormToSubmit) {
            deleteFormToSubmit.submit();
        }
        closeDeleteModal();
    };
    
    // Event Listeners
    if (cancelBtn) {
        cancelBtn.addEventListener('click', closeDeleteModal);
    }
    
    if (confirmBtn) {
        confirmBtn.addEventListener('click', confirmDelete);
    }
    
    if (closeBtn) {
        closeBtn.addEventListener('click', closeDeleteModal);
    }
    
    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            closeDeleteModal();
        }
        
        // Focus trap - Tab key
        if (e.key === 'Tab' && modal && !modal.classList.contains('hidden')) {
            const firstFocusable = focusableElements[0];
            const lastFocusable = focusableElements[focusableElements.length - 1];
            
            // If focus is on modal content (initial state), move to first button
            if (document.activeElement === modalContent) {
                e.preventDefault();
                if (e.shiftKey) {
                    lastFocusable.focus();
                } else {
                    firstFocusable.focus();
                }
                return;
            }
            
            if (e.shiftKey) {
                // Shift + Tab
                if (document.activeElement === firstFocusable) {
                    e.preventDefault();
                    lastFocusable.focus();
                }
            } else {
                // Tab
                if (document.activeElement === lastFocusable) {
                    e.preventDefault();
                    firstFocusable.focus();
                }
            }
        }
    });
    
    // Close on backdrop click
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeDeleteModal();
            }
        });
    }
    
    // Initialize all .js-confirm buttons on page load
    function initConfirmButtons() {
        document.querySelectorAll('.js-confirm').forEach(function(button) {
            // Remove any existing listeners to prevent duplicates
            button.removeEventListener('click', handleConfirmClick);
            button.addEventListener('click', handleConfirmClick);
        });
    }
    
    function handleConfirmClick(e) {
        e.preventDefault();
        e.stopPropagation();
        const form = this.closest('form');
        const message = this.getAttribute('data-confirm-message') || 'هل أنت متأكد من حذف هذا العنصر؟';
        showDeleteModal(form, message);
    }
    
    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initConfirmButtons);
    } else {
        initConfirmButtons();
    }
    
    // Re-initialize if content is dynamically loaded (for SPAs or AJAX)
    window.reinitConfirmButtons = initConfirmButtons;
})();
</script>
@endPushOnce

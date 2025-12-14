/**
 * Playpass Admin CMS JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ============================================
    // Auto-dismiss flash messages
    // ============================================
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // ============================================
    // File Upload Preview
    // ============================================
    const fileInputs = document.querySelectorAll('.file-upload-input');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            const reader = new FileReader();
            const wrapper = this.closest('.file-upload-wrapper');
            const label = wrapper.querySelector('.file-upload-label');
            
            reader.onload = function(event) {
                // Update label to show preview
                label.innerHTML = `
                    <img src="${event.target.result}" style="max-height: 100px; border-radius: 8px;">
                    <span style="margin-top: 8px;">Click to change</span>
                `;
            };
            
            reader.readAsDataURL(file);
        });
    });

    // ============================================
    // Sortable Lists (Drag & Drop)
    // ============================================
    const sortableLists = document.querySelectorAll('.sortable-list');
    sortableLists.forEach(list => {
        let draggedItem = null;
        
        list.querySelectorAll('.sortable-item').forEach(item => {
            item.setAttribute('draggable', 'true');
            
            item.addEventListener('dragstart', function(e) {
                draggedItem = this;
                this.classList.add('dragging');
                e.dataTransfer.effectAllowed = 'move';
            });
            
            item.addEventListener('dragend', function() {
                this.classList.remove('dragging');
                draggedItem = null;
                
                // Update order via AJAX
                const items = Array.from(list.querySelectorAll('.sortable-item'));
                const order = items.map(item => item.dataset.id);
                
                // Determine endpoint based on list ID
                let endpoint = '/admin/carousel/update-order';
                if (list.id === 'stepsSortable') {
                    endpoint = '/admin/how-it-works/update-order';
                }
                
                fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ items: order })
                });
            });
            
            item.addEventListener('dragover', function(e) {
                e.preventDefault();
                const rect = this.getBoundingClientRect();
                const midpoint = rect.top + rect.height / 2;
                
                if (e.clientY < midpoint) {
                    this.parentNode.insertBefore(draggedItem, this);
                } else {
                    this.parentNode.insertBefore(draggedItem, this.nextSibling);
                }
            });
        });
    });

    // ============================================
    // Search/Filter Tables
    // ============================================
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const table = document.querySelector('.admin-table tbody');
            if (!table) return;
            
            table.querySelectorAll('tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }

    // ============================================
    // Delete Confirmation Modal
    // ============================================
    const deleteButtons = document.querySelectorAll('[data-confirm]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm(this.dataset.confirm || 'Are you sure?')) {
                e.preventDefault();
            }
        });
    });

    // ============================================
    // Color Picker Sync
    // ============================================
    const colorPickers = document.querySelectorAll('.color-picker-input');
    colorPickers.forEach(picker => {
        picker.addEventListener('input', function() {
            const textInput = this.nextElementSibling;
            if (textInput && textInput.tagName === 'INPUT') {
                textInput.value = this.value;
            }
        });
    });

    // ============================================
    // Form Dirty Check
    // ============================================
    const forms = document.querySelectorAll('.admin-form');
    forms.forEach(form => {
        const initialData = new FormData(form);
        let isDirty = false;
        
        form.addEventListener('change', () => isDirty = true);
        form.addEventListener('input', () => isDirty = true);
        
        window.addEventListener('beforeunload', function(e) {
            if (isDirty) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
        
        form.addEventListener('submit', () => isDirty = false);
    });

    // ============================================
    // Keyboard Shortcuts
    // ============================================
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + S to save form
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            const form = document.querySelector('.admin-form');
            if (form) {
                e.preventDefault();
                form.submit();
            }
        }
        
        // Escape to go back
        if (e.key === 'Escape') {
            const backLink = document.querySelector('a[href*="Back"]') || 
                            document.querySelector('a.btn-admin-secondary');
            if (backLink && backLink.href) {
                window.location.href = backLink.href;
            }
        }
    });

    // ============================================
    // Toggle Sidebar on Mobile (future)
    // ============================================
    const sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            document.querySelector('.admin-sidebar').classList.toggle('active');
        });
    }

    // ============================================
    // AJAX Form Submit with Loading State
    // ============================================
    const ajaxForms = document.querySelectorAll('[data-ajax-form]');
    console.log('AJAX SETUP: Found', ajaxForms.length, 'form(s) with data-ajax-form attribute');
    ajaxForms.forEach(form => {
        console.log('AJAX SETUP: Attaching handler to form:', form.action);
        form.addEventListener('submit', async function(e) {
            console.log('AJAX HANDLER: Submit event caught!');
            e.preventDefault();
            e.stopPropagation();
            
            const submitBtn = form.querySelector('[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            
            try {
                console.log('AJAX HANDLER: Starting form submission');
                
                // Build FormData manually to ensure file is included
                const formData = new FormData();
                
                // Add all form fields except file inputs
                const formInputs = form.querySelectorAll('input, textarea, select');
                formInputs.forEach(input => {
                    if (input.type === 'file') {
                        // Skip file inputs for now, we'll add them separately
                        return;
                    }
                    if (input.type === 'checkbox' || input.type === 'radio') {
                        if (input.checked) {
                            formData.append(input.name, input.value);
                        }
                    } else if (input.name && input.value !== '') {
                        formData.append(input.name, input.value);
                    }
                });
                
                // Explicitly add file inputs
                const fileInputs = form.querySelectorAll('input[type="file"]');
                console.log('AJAX: Found', fileInputs.length, 'file input(s)');
                fileInputs.forEach(fileInput => {
                    if (fileInput.files && fileInput.files.length > 0) {
                        console.log('AJAX: Adding file', fileInput.name, '-', fileInput.files[0].name, '(' + fileInput.files[0].size + ' bytes)');
                        formData.append(fileInput.name, fileInput.files[0]);
                    } else {
                        console.log('AJAX: File input', fileInput.name, 'has no file selected');
                    }
                });
                
                // Debug: Log all FormData entries
                console.log('AJAX: FormData entries:');
                for (let pair of formData.entries()) {
                    if (pair[1] instanceof File) {
                        console.log('  -', pair[0], ':', pair[1].name, '(' + pair[1].size + ' bytes)');
                    } else {
                        console.log('  -', pair[0], ':', pair[1]);
                    }
                }
                
                const response = await fetch(form.action, {
                    method: form.method,
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                // Check if response is a redirect
                if (response.redirected || response.status === 302 || response.status === 301) {
                    // Follow the redirect
                    window.location.href = response.url || form.action.replace(/\/create$/, '') || form.action.replace(/\/update\/\d+$/, '');
                    return;
                }
                
                // Check for redirect in response headers
                const redirectUrl = response.headers.get('Location');
                if (redirectUrl) {
                    window.location.href = redirectUrl;
                    return;
                }
                
                // Try to parse JSON response
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    const data = await response.json();
                    if (data.success && data.redirect) {
                        // Show success message if provided
                        if (data.message) {
                            submitBtn.innerHTML = '<i class="fas fa-check"></i> ' + data.message;
                        } else {
                            submitBtn.innerHTML = '<i class="fas fa-check"></i> Saved!';
                        }
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 1000);
                        return;
                    }
                }
                
                // If response is HTML (likely a redirect page), check for redirect
                if (response.ok) {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('text/html')) {
                        const text = await response.text();
                        // Check if it's a redirect page
                        const redirectMatch = text.match(/window\.location\.href\s*=\s*['"]([^'"]+)['"]/);
                        if (redirectMatch) {
                            window.location.href = redirectMatch[1];
                            return;
                        }
                    }
                    
                    // If we get here and it's not JSON, it might be a successful HTML response
                    // Check if we got a JSON response but content-type wasn't set
                    try {
                        const data = await response.json();
                        if (data.success && data.redirect) {
                            submitBtn.innerHTML = '<i class="fas fa-check"></i> ' + (data.message || 'Saved!');
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 1000);
                            return;
                        }
                    } catch (e) {
                        // Not JSON, continue with HTML handling
                    }
                    
                    // Otherwise show success and redirect after delay
                    submitBtn.innerHTML = '<i class="fas fa-check"></i> Saved!';
                    setTimeout(() => {
                        // Extract redirect URL from form action
                        const redirectPath = form.action.includes('/update/') 
                            ? form.action.replace(/\/update\/\d+$/, '')
                            : form.action.replace(/\/create$/, '');
                        window.location.href = redirectPath;
                    }, 1000);
                } else {
                    const errorText = await response.text();
                    console.error('AJAX Error Response:', errorText);
                    throw new Error('Save failed: ' + response.statusText);
                }
            } catch (error) {
                console.error('AJAX Form Error:', error);
                submitBtn.innerHTML = '<i class="fas fa-times"></i> Error!';
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 2000);
            }
        });
    });

    // ============================================
    // Bulk Selection
    // ============================================
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulkActions();
        });
        
        document.querySelectorAll('.row-checkbox').forEach(cb => {
            cb.addEventListener('change', updateBulkActions);
        });
    }
    
    function updateBulkActions() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        const bulkActions = document.getElementById('bulkActions');
        if (bulkActions) {
            bulkActions.style.display = checked.length > 0 ? 'flex' : 'none';
            const count = document.getElementById('selectedCount');
            if (count) count.textContent = checked.length;
        }
    }

    console.log('🎮 Playpass Admin CMS Initialized');
});


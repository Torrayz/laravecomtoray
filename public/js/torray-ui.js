(function (window, document) {
  'use strict';

  function ensureToastContainer() {
    let el = document.getElementById('tr-toast-container');
    if (!el) {
      el = document.createElement('div');
      el.id = 'tr-toast-container';
      el.className = 'tr-toast-container';
      document.body.appendChild(el);
    }
    return el;
  }

  function torrayToast(message, type) {
    const container = ensureToastContainer();
    const toast = document.createElement('div');
    const variant = type === 'error' || type === 'failed' ? 'error' : 'success';
    toast.className = 'tr-toast tr-toast--' + variant;
    toast.setAttribute('role', 'status');

    const textSpan = document.createElement('span');
    textSpan.textContent = message;

    const closeBtn = document.createElement('button');
    closeBtn.type = 'button';
    closeBtn.setAttribute('aria-label', 'Close');
    closeBtn.textContent = '×';
    closeBtn.addEventListener('click', function () {
      toast.remove();
    });

    toast.appendChild(textSpan);
    toast.appendChild(closeBtn);
    container.appendChild(toast);

    setTimeout(function () {
      if (toast.parentNode) {
        toast.remove();
      }
    }, 3200);
  }

  function ensureConfirmModal() {
    let modalEl = document.getElementById('trConfirmModal');
    if (modalEl) return modalEl;

    modalEl = document.createElement('div');
    modalEl.id = 'trConfirmModal';
    modalEl.className = 'modal fade';
    modalEl.tabIndex = -1;
    modalEl.setAttribute('aria-hidden', 'true');
    modalEl.innerHTML =
      '<div class="modal-dialog modal-dialog-centered">' +
      '  <div class="modal-content">' +
      '    <div class="modal-header">' +
      '      <h5 class="modal-title" id="trConfirmTitle">Confirm Action</h5>' +
      '      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
      '    </div>' +
      '    <div class="modal-body" id="trConfirmBody">Are you sure?</div>' +
      '    <div class="modal-footer">' +
      '      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>' +
      '      <button type="button" class="btn btn-danger" id="trConfirmActionBtn">Confirm</button>' +
      '    </div>' +
      '  </div>' +
      '</div>';
    document.body.appendChild(modalEl);
    return modalEl;
  }

  function torrayConfirm(options) {
    const title = (options && options.title) || 'Confirm Action';
    const text = (options && options.text) || 'This action cannot be undone.';
    const confirmText = (options && options.confirmText) || 'Yes, proceed';
    const onConfirm = options && options.onConfirm;

    if (typeof window.bootstrap === 'undefined' || !window.bootstrap.Modal) {
      if (window.confirm(title + '\n' + text) && typeof onConfirm === 'function') {
        onConfirm();
      }
      return;
    }

    const modalEl = ensureConfirmModal();
    modalEl.querySelector('#trConfirmTitle').textContent = title;
    modalEl.querySelector('#trConfirmBody').textContent = text;
    const actionBtn = modalEl.querySelector('#trConfirmActionBtn');
    actionBtn.textContent = confirmText;

    const modal = window.bootstrap.Modal.getOrCreateInstance(modalEl);
    const nextBtn = actionBtn.cloneNode(true);
    actionBtn.parentNode.replaceChild(nextBtn, actionBtn);

    nextBtn.addEventListener('click', function () {
      modal.hide();
      if (typeof onConfirm === 'function') {
        onConfirm();
      }
    });

    modal.show();
  }

  function torrayAutosize(selector) {
    const elements = document.querySelectorAll(selector || '.autosize');
    elements.forEach(function (el) {
      function resize() {
        el.style.overflow = 'hidden';
        el.style.height = 'auto';
        el.style.height = el.scrollHeight + 'px';
      }
      el.setAttribute('rows', '1');
      resize();
      el.addEventListener('input', resize);
    });
  }

  function torrayTableFilter(inputSelector, tableSelector) {
    const input = document.querySelector(inputSelector);
    const table = document.querySelector(tableSelector);
    if (!input || !table) return;

    const rows = Array.from(table.querySelectorAll('tbody tr'));
    input.addEventListener('input', function () {
      const term = input.value.trim().toLowerCase();
      rows.forEach(function (row) {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
      });
    });
  }

  function torrayUploadPreview(inputSelector, previewSelector, dropZoneSelector) {
    const input = document.querySelector(inputSelector);
    const preview = document.querySelector(previewSelector);
    const zone = dropZoneSelector ? document.querySelector(dropZoneSelector) : null;
    if (!input || !preview) return;

    function showFile(file) {
      if (!file || !file.type || !file.type.startsWith('image/')) {
        torrayToast('Please select a valid image file.', 'error');
        return;
      }
      const reader = new FileReader();
      reader.onload = function (e) {
        preview.src = e.target.result;
        preview.classList.remove('d-none');
      };
      reader.readAsDataURL(file);
    }

    input.addEventListener('change', function () {
      if (input.files && input.files[0]) {
        showFile(input.files[0]);
      }
    });

    if (zone) {
      zone.addEventListener('dragover', function (e) {
        e.preventDefault();
        zone.classList.add('is-dragover');
      });
      zone.addEventListener('dragleave', function () {
        zone.classList.remove('is-dragover');
      });
      zone.addEventListener('drop', function (e) {
        e.preventDefault();
        zone.classList.remove('is-dragover');
        if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0]) {
          input.files = e.dataTransfer.files;
          showFile(e.dataTransfer.files[0]);
        }
      });
    }
  }

  window.torrayToast = torrayToast;
  window.torrayConfirm = torrayConfirm;
  window.torrayAutosize = torrayAutosize;
  window.torrayTableFilter = torrayTableFilter;
  window.torrayUploadPreview = torrayUploadPreview;
})(window, document);

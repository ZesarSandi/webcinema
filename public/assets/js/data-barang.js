(function(){
  if(window.USE_APP_DATA_TABLE) return;
  const openBtn = document.querySelector('.btn-add');
  const modal = document.getElementById('modal-tambah');
  const closeButtons = modal ? modal.querySelectorAll('[data-close], .modal-close') : [];
  const form = document.getElementById('form-tambah');
  const tbody = document.querySelector('.data-barang tbody');
  const dropzone = document.getElementById('dropzone');
  const fileInput = document.getElementById('foto_file');
  const fotoUrl = form ? form.querySelector('[name="foto_url"]') : null;
  const preview = document.getElementById('foto_preview');
  const btnFile = document.querySelector('.btn-file');
  const modalTitle = document.getElementById('modal-title');

  let currentImageData = null; // DataURL
  let currentEditRow = null;
  let currentEditId = null; // ID dari database untuk update
  let items = []; // Array untuk menyimpan data items

  if(!modal || !openBtn || !form || !tbody) return;

  // --- API Base URL ---
  const API_BASE = window.API_BASE || 'http://localhost:3001';
  const API_ITEMS_URL = `${API_BASE}/api/items`;

  // --- API Functions ---
  async function loadItems(){
    try {
      const response = await fetch(API_ITEMS_URL);
      if (!response.ok) throw new Error(`HTTP ${response.status}`);
      items = await response.json();
      console.log('✅ Data dimuat dari database. Total:', items.length, 'items');
      renderTableFromStorage();
    } catch (err) {
      console.error('❌ Gagal memuat data dari API:', err);
      alert('⚠️ Tidak bisa terhubung ke database. Periksa backend server running.\n\nError: ' + err.message);
      items = [];
      renderTableFromStorage();
    }
  }

  async function saveItems(item, isUpdate = false) {
    try {
      const options = {
        method: isUpdate ? 'PUT' : 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(item)
      };

      const url = isUpdate ? `${API_ITEMS_URL}/${item.id}` : API_ITEMS_URL;
      const response = await fetch(url, options);
      
      if (!response.ok) throw new Error(`HTTP ${response.status}`);
      
      const result = await response.json();
      console.log(isUpdate ? '✏️ Item diperbarui di database' : '✅ Item disimpan ke database', item);
      return result;
    } catch (err) {
      console.error('❌ Gagal menyimpan ke database:', err);
      alert('Gagal menyimpan data:\n' + err.message);
      throw err;
    }
  }

  async function deleteItem(id) {
    try {
      const response = await fetch(`${API_ITEMS_URL}/${id}`, { method: 'DELETE' });
      if (!response.ok) throw new Error(`HTTP ${response.status}`);
      console.log('🗑️ Item dihapus dari database (ID:', id, ')');
      return await response.json();
    } catch (err) {
      console.error('❌ Gagal menghapus item:', err);
      alert('Gagal menghapus data:\n' + err.message);
      throw err;
    }
  }

  function renderTableFromStorage(){
    tbody.innerHTML = '';
    items.forEach((item, idx) => {
      const tr = document.createElement('tr');
      tr.dataset.itemId = item.id; // Store database ID
      const code = item.code || 'N/A';
      tr.innerHTML = `
        <td>${idx + 1}</td>
        <td><img src="${escapeHtml(item.img || item.foto || '')}" alt="${escapeHtml(item.nama)}" width="60"></td>
        <td>${escapeHtml(item.nama || '')}</td>
        <td><span class="kondisi ${escapeHtml(item.kondisi || 'baik')}">${capitalize(item.kondisi || 'baik')}</span></td>
        <td>${escapeHtml(item.jenis || '')}</td>
        <td>${escapeHtml(item.tanggal || '')}</td>
        <td style="font-family:monospace;font-size:12px;color:#666">${escapeHtml(code)}</td>
        <td class="table-actions">
          <button type="button" class="btn btn-outline action-qr" data-code="${escapeHtml(code)}" title="Lihat QR Label">🏷️ QR</button>
          <button type="button" class="btn btn-outline action-edit">Edit</button>
          <button type="button" class="btn btn-outline action-delete" style="border-color:var(--danger);color:var(--danger)">Hapus</button>
        </td>
      `;
      tbody.appendChild(tr);
    });
    updateItemCount();
  }

  function updateItemCount(){
    const countEl = document.getElementById('total-items-count');
    if(countEl) countEl.textContent = items.length;
  }

  // Load data saat halaman pertama kali di-load
  loadItems();

  function showModal(title){ modal.setAttribute('aria-hidden','false'); document.body.style.overflow='hidden'; modalTitle && (modalTitle.textContent = title || 'Tambah Barang'); form.querySelector('button[type="submit"]').textContent = currentEditRow ? 'Perbarui' : 'Simpan'; }
  function hideModal(){ 
    modal.setAttribute('aria-hidden','true'); 
    document.body.style.overflow=''; 
    form.reset(); 
    clearPreview(); 
    currentImageData = null; 
    currentEditRow = null; 
    modalTitle && (modalTitle.textContent = 'Tambah Barang'); 
    form.querySelector('button[type="submit"]').textContent = 'Simpan';
    // Unlock QR code
    if(window.qrGenerator) {
      window.qrGenerator.setQRLocked(false);
    }
  }

  openBtn.addEventListener('click', function(){ 
    currentEditRow = null; 
    // Unlock QR for new item
    if(window.qrGenerator) {
      window.qrGenerator.setQRLocked(false);
      window.qrGenerator.setOriginalCode('');
    }
    showModal('Tambah Barang'); 
  });
  closeButtons.forEach(b => b.addEventListener('click', hideModal));
  modal.querySelector('.modal-overlay').addEventListener('click', hideModal);

  // Dropzone behavior
  if(dropzone){
    ['dragenter','dragover'].forEach(ev => dropzone.addEventListener(ev, function(e){ e.preventDefault(); e.stopPropagation(); dropzone.classList.add('dragover'); }));
    ['dragleave','drop'].forEach(ev => dropzone.addEventListener(ev, function(e){ e.preventDefault(); e.stopPropagation(); dropzone.classList.remove('dragover'); }));

    dropzone.addEventListener('drop', function(e){
      const files = e.dataTransfer && e.dataTransfer.files; if(!files || !files.length) return; handleFile(files[0]);
    });

    if(btnFile){ btnFile.addEventListener('click', function(){ fileInput.click() }); }
    fileInput.addEventListener('change', function(e){ if(e.target.files && e.target.files[0]) handleFile(e.target.files[0]) });
  }

  function handleFile(file){
    if(!file.type.startsWith('image/')){ alert('Mohon pilih file gambar.'); return }
    const reader = new FileReader();
    reader.onload = function(ev){ currentImageData = ev.target.result; showPreview(currentImageData); };
    reader.readAsDataURL(file);
    if(fotoUrl) fotoUrl.value = '';
  }

  function showPreview(src){ preview.setAttribute('aria-hidden','false'); preview.innerHTML = '<img src="'+escapeHtml(src)+'" alt="Preview">'; }
  function clearPreview(){ preview.setAttribute('aria-hidden','true'); preview.innerHTML = '' }

  function dateToInputValue(s){ if(!s) return ''; const parts = s.split('-'); if(parts.length===3){ const [d,m,y]=parts; return `${y}-${m.padStart(2,'0')}-${d.padStart(2,'0')}` } return '' }
  function inputValueToDisplayDate(v){ if(!v) return ''; const parts=v.split('-'); if(parts.length===3){ return `${parts[2]}-${parts[1]}-${parts[0]}` } return v }

  // delegate actions (qr/edit/delete)
  tbody.addEventListener('click', function(e){
    const qrBtn = e.target.closest('.action-qr');
    const edit = e.target.closest('.action-edit');
    const del = e.target.closest('.action-delete');
    if(qrBtn){ const code = qrBtn.dataset.code; if(code && code !== 'N/A') openQRLabel(code); }
    if(edit){ const tr = edit.closest('tr'); openEdit(tr); }
    if(del){ const tr = del.closest('tr'); doDelete(tr); }
  });

  function openEdit(tr){ 
    if(!tr) return; 
    currentEditRow = tr;
    // Ambil ID dari data attribute
    const itemId = tr.dataset.itemId;
    currentEditId = itemId;
    
    const cells = tr.children; 
    const imgEl = tr.querySelector('td img'); 
    const imgSrc = imgEl ? imgEl.src : '';
    
    // Get existing QR code
    const existingQRCode = (cells[6] && cells[6].textContent) || '';
    const nama = cells[2] ? cells[2].textContent : '';
    
    // Lock QR code during edit - prevent auto-generate from changing it
    if(window.qrGenerator) {
      window.qrGenerator.setQRLocked(true);
      window.qrGenerator.setOriginalCode(existingQRCode);
      // Display existing QR preview
      if(existingQRCode && existingQRCode !== 'N/A') {
        setTimeout(() => {
          window.qrGenerator.displayQRCode(existingQRCode);
        }, 100);
      }
    }
    
    // populate form
    form.querySelector('[name="nama"]').value = nama;
    const kondisiSpan = cells[3] && cells[3].querySelector('.kondisi');
    form.querySelector('[name="kondisi"]').value = (kondisiSpan && (kondisiSpan.classList.contains('baik') ? 'baik' : (kondisiSpan.classList.contains('rusak') ? 'rusak' : 'perbaikan')))||'baik';
    form.querySelector('[name="jenis"]').value = (cells[4] && cells[4].textContent)||'';
    form.querySelector('[name="tanggal"]').value = dateToInputValue((cells[5] && cells[5].textContent)||'');
    // Set QR code input from existing data
    if(form.querySelector('#qr-code-input')) form.querySelector('#qr-code-input').value = existingQRCode;
    
    // image - if data URL set preview; else set foto_url
    if(imgSrc && imgSrc.startsWith('data:')){ currentImageData = imgSrc; showPreview(currentImageData); if(fotoUrl) fotoUrl.value = ''; }
    else if(imgSrc){ clearPreview(); currentImageData = null; if(fotoUrl) fotoUrl.value = imgSrc; }
    showModal('Edit Barang');
  }

  async function doDelete(tr){ 
    if(!tr) return; 
    if(!confirm('Hapus item ini?')) return; 
    
    const itemId = tr.dataset.itemId;
    if (!itemId) {
      alert('ID item tidak ditemukan');
      return;
    }

    try {
      await deleteItem(itemId);
      tr.remove(); 
      // renumber
      Array.from(tbody.querySelectorAll('tr')).forEach((r,i)=>{ if(r.children[0]) r.children[0].textContent = i+1 });
    } catch (err) {
      // Error already handled in deleteItem function
    }
  }

  form.addEventListener('submit', async function(e){ 
    e.preventDefault(); 
    const data = new FormData(form); 
    const foto_url = (fotoUrl && fotoUrl.value && fotoUrl.value.trim()) ? fotoUrl.value.trim() : null; 
    const nama = (data.get('nama')||'').trim(); 
    const kondisi = data.get('kondisi'); 
    const jenis = (data.get('jenis')||'').trim(); 
    const tanggal = data.get('tanggal'); 
    if(!currentImageData && !foto_url){ alert('Mohon sertakan foto (upload atau URL).'); return } 
    const imgSrc = currentImageData || foto_url; 
    const qrCode = (form.querySelector('#qr-code-input') && form.querySelector('#qr-code-input').value) || 'N/A';
    console.log('💾 Form submitted - QR Code:', qrCode, 'Nama:', nama);
    
    try {
      if(currentEditRow){ // UPDATE
        const itemPayload = {
          id: currentEditId,
          code: qrCode,
          nama: nama,
          kondisi: kondisi,
          jenis: jenis,
          tanggal: tanggal,
          foto: imgSrc
        };
        
        await saveItems(itemPayload, true);
        
        const cells = currentEditRow.children; 
        cells[1].querySelector('img').src = imgSrc; 
        cells[2].textContent = nama; 
        cells[3].innerHTML = `<span class="kondisi ${escapeHtml(kondisi)}">${capitalize(kondisi)}</span>`; 
        cells[4].textContent = jenis; 
        cells[5].textContent = inputValueToDisplayDate(tanggal); 
        cells[6].textContent = qrCode;
        const qrBtn = cells[7] && cells[7].querySelector('.action-qr');
        if(qrBtn) qrBtn.dataset.code = qrCode;
        hideModal(); 
        console.log('✏️ Item updated with QR:', qrCode);
        return 
      }
      
      // CREATE (POST)
      const newItem = {
        code: qrCode,
        nama: nama,
        kondisi: kondisi,
        jenis: jenis,
        tanggal: tanggal,
        foto: imgSrc
      };
      
      const result = await saveItems(newItem, false);
      
      // Update items array with new item including ID dari server
      const savedItem = {
        id: result.id || Date.now(),
        code: qrCode,
        nama: nama,
        kondisi: kondisi,
        jenis: jenis,
        tanggal: tanggal,
        img: imgSrc
      };
      items.push(savedItem);
      
      const tr = document.createElement('tr');
      tr.dataset.itemId = savedItem.id;
      const count = tbody.querySelectorAll('tr').length + 1; 
      tr.innerHTML = `
        <td>${count}</td>
        <td><img src="${escapeHtml(imgSrc)}" alt="${escapeHtml(nama)}" width="60"></td>
        <td>${escapeHtml(nama)}</td>
        <td><span class="kondisi ${escapeHtml(kondisi)}">${capitalize(kondisi)}</span></td>
        <td>${escapeHtml(jenis)}</td>
        <td>${escapeHtml(inputValueToDisplayDate(tanggal))}</td>
        <td style="font-family:monospace;font-size:12px;color:#666">${escapeHtml(qrCode)}</td>
        <td class="table-actions">
          <button type="button" class="btn btn-outline action-qr" data-code="${escapeHtml(qrCode)}" title="Lihat QR Label">🏷️ QR</button>
          <button type="button" class="btn btn-outline action-edit">Edit</button>
          <button type="button" class="btn btn-outline action-delete" style="border-color:var(--danger);color:var(--danger)">Hapus</button>
        </td>
      `; 
      tbody.appendChild(tr); 
      hideModal(); 
      console.log('✅ New item added with QR:', qrCode);
    } catch (err) {
      console.error('❌ Form submission error:', err);
      // Error already handled in saveItems function
    }
  });

  // Open QR Label modal
  function openQRLabel(code){
    const modalLabel = document.getElementById('modal-label');
    const labelImg = document.getElementById('label-qr-img');
    const labelText = document.getElementById('label-code-text');
    const labelPrint = document.getElementById('label-print');
    
    if(!modalLabel || !labelImg || !labelText) return;

    // Sync with peminjaman selection
    try{ localStorage.setItem('loanExpectedCode', String(code||'')); }catch(e){}
    
    // Generate QR for this code
    generateQRForLabel(code, function(qrUrl){
      if(labelImg) labelImg.src = qrUrl;
      if(labelText) labelText.textContent = code;
      
      // Show modal
      modalLabel.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
      
      // Setup print handler
      if(labelPrint){
        labelPrint.onclick = function(){
          const printWindow = window.open('', '', 'width=400,height=500');
          printWindow.document.write('<html><head><title>Print Label QR - ' + code + '</title></head><body>');
          printWindow.document.write('<div style="text-align:center;padding:30px">');
          printWindow.document.write('<img src="' + qrUrl + '" style="max-width:300px;border:2px solid #1a237e;border-radius:8px">');
          printWindow.document.write('<p style="margin-top:20px;font-family:monospace;font-weight:bold;font-size:18px">' + code + '</p>');
          printWindow.document.write('<p style="color:#666;font-size:13px">Scan untuk form peminjaman</p>');
          printWindow.document.write('</div></body></html>');
          printWindow.document.close();
          setTimeout(() => printWindow.print(), 250);
        };
      }
    });
    
    // Setup close handlers
    const closeButtons = modalLabel.querySelectorAll('[data-close], .modal-close');
    closeButtons.forEach(btn => {
      btn.onclick = function(){
        modalLabel.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      };
    });
  }
  
  // Generate QR code for label
  function generateQRForLabel(code, callback){
    try{
      let base = window.PUBLIC_BASE_URL || window.location.origin;
      if(base === 'null' || base === 'file://' || !base){
        base = 'http://' + (window.location.host || 'localhost:8080');
      }
      const path = window.location.pathname.replace(/\/[^\/]*$/, '');
      const url = base + path + '/peminjaman.html?code=' + encodeURIComponent(code);
      
      if(typeof QRCode !== 'undefined' && QRCode.toDataURL) {
        QRCode.toDataURL(url, {
          width: 300,
          margin: 2,
          color: { dark: '#1a237e', light: '#ffffff' }
        }, function(err, dataUrl) {
          if(err) {
            const fallbackUrl = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' + encodeURIComponent(url);
            if(callback) callback(fallbackUrl);
          } else {
            if(callback) callback(dataUrl);
          }
        });
      } else {
        const fallbackUrl = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' + encodeURIComponent(url);
        if(callback) callback(fallbackUrl);
      }
    }catch(e){ 
      console.error('Generate QR error:', e);
      const fallbackUrl = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' + encodeURIComponent(code);
      if(callback) callback(fallbackUrl);
    }
  }

  function capitalize(s){ return s ? s.charAt(0).toUpperCase()+s.slice(1) : s }
  function escapeHtml(str){ return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') }
})();
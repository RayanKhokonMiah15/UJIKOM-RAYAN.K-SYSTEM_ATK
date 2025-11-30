<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Permintaan Barang</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('css/barang.css') }}" rel="stylesheet">

  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
      <a class="navbar-brand" href="{{ url('/') }}">
        <span class="solar--box-bold"></span>
        <span class="brand-text">
          <span class="first-half">GUDANG</span><span class="second-half">KANTOR</span>
        </span>
      </a>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="hero-content">
      <h1 class="hero-title">Sistem Permintaan ATK<br>PTUN Bandung</h1>
      <p class="hero-description">
        Kelola permintaan alat tulis kantor dengan mudah dan efisien. 
        Sistem terintegrasi untuk memudahkan proses pengajuan dan monitoring kebutuhan ATK Anda.
      </p>
      <a href="#form-section" class="hero-btn">
        Ajukan Permintaan
      </a>
      
      <div class="hero-illustration">
        <div class="illustration-box"></div>
        <div class="illustration-box"></div>
        <div class="illustration-box"></div>
      </div>
    </div>
  </section>

  <!-- Form Section with AI Chat -->
  <section id="form-section" class="form-section">
    <div class="container">
      <div class="form-chat-wrapper">
        
        <!-- AI Chat Box (Left) -->
        <div class="chat-container">
          <div class="chat-header">
            <div class="chat-header-content">
              <div class="bot-avatar">🤖</div>
              <div>
                <h5 class="mb-0">Asisten ATK</h5>
                <small class="text-white-50">Online</small>
              </div>
            </div>
          </div>
          
          <div class="chat-messages" id="chatMessages">
            <div class="ai-message">
              <div class="message-avatar">🤖</div>
              <div class="message-content">
                <p>Halo! Saya asisten virtual ATK. Saya bisa membantu Anda:</p>
                <ul class="mb-0">
                  <li>Cek ketersediaan barang</li>
                  <li>Info detail barang</li>
                  <li>Tambahkan barang ke form</li>
                </ul>
                <p class="mb-0 mt-2"><small><strong>Contoh:</strong> "apakah ada pulpen?", "tambahin pulpen 5"</small></p>
              </div>
            </div>
          </div>

          <div class="chat-input-wrapper">
            <input 
              type="text" 
              class="chat-input" 
              id="chatInput" 
              placeholder="Ketik pesan Anda..."
              autocomplete="off"
            >
            <button class="chat-send-btn" id="chatSendBtn">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M22 2L11 13M22 2L15 22L11 13M22 2L2 9L11 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Form Box (Right) -->
        <div class="request-box shadow-sm p-4 rounded bg-white">
          <div class="text-center mb-4">
            <h4 class="fw-semibold">Permintaan Barang</h4>
          </div>

          @if(session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
          @elseif ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="text-center mb-3">
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#stokModal">
              Lihat Stok
            </button>
          </div>

          <form action="{{ route('permintaan.store') }}" method="POST" enctype="multipart/form-data" id="multiForm">
            @csrf

            <!-- Dropdown Barang -->
            <div class="mb-3">
              <label>Barang</label>
              <select id="barangSelect" class="form-select js-example-basic-single">
                <option value="">Pilih Barang</option>
                @foreach ($barangs as $b)
                  <option value="{{ $b->id }}" 
                          data-nama="{{ $b->nama_barang }}" 
                          data-stok="{{ $b->stok }}"
                          data-satuan="{{ $b->satuan ?? 'pcs' }}"
                          data-kategori="{{ $b->kategori ?? '' }}"
                          data-foto="{{ $b->foto ?? '' }}">
                    {{ $b->nama_barang }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="mb-3">
              <label>Nama Peminta</label>
              <input type="text" name="nama_peminta" class="form-control" required>
            </div>

            <div class="mb-3">
              <label>Nama Ruangan</label>
              <input type="text" name="nama_ruangan" class="form-control" required>
            </div>

            <!-- ✍️ Tanda Tangan -->
            <div class="mb-4">
              <label for="signature" class="form-label fw-semibold">Tanda Tangan Peminta</label>
              <p class="text-muted small mb-2">Silakan tanda tangan di bawah ini menggunakan mouse atau sentuhan layar.</p>

              <div class="border rounded p-2 bg-light">
                <canvas id="signature-pad" width="500" height="200" style="width: 100%; border: 1px solid #ccc; border-radius: 8px;"></canvas>
              </div>

              <div class="d-flex justify-content-end mt-2 gap-2">
                <button type="button" class="btn btn-sm btn-secondary" id="clear-signature">Hapus</button>
              </div>

              <input type="hidden" name="tanda_tangan" id="tanda_tangan">
            </div>

            <!-- 📦 Daftar Barang Dipilih -->
            <div id="barangList" class="mt-4">
              <h6 class="mb-3 fw-semibold text-secondary">Daftar Barang Dipilih</h6>
              <p class="text-muted small" id="emptyText">Belum ada barang dipilih.</p>
            </div>

            <div class="text-center mt-4">
              <button type="submit" class="btn btn-primary px-4">Kirim Permintaan</button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </section>

  <!-- 🧾 Modal Stok -->
  <div class="modal fade" id="stokModal" tabindex="-1" aria-labelledby="stokModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content border-0">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="stokModalLabel">Daftar Stok Barang</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          @if(count($barangs) > 0)
            <div class="barang-grid">
              @foreach ($barangs as $barang)
                <div class="barang-card">
                  @if(!empty($barang->foto))
                    <div class="mb-2 text-center">
                      <img src="{{ asset('storage/' . $barang->foto) }}" alt="Foto {{ $barang->nama_barang }}" class="preview-img-modal">
                    </div>
                  @endif
                  <h6 class="barang-nama">{{ $barang->nama_barang }}</h6>
                  <p><strong>Stok:</strong> {{ $barang->stok }}</p>
                  <p><strong>Satuan:</strong> {{ $barang->satuan ?? '-' }}</p>
                  <p><strong>Kategori:</strong> {{ $barang->kategori ?? '-' }}</p>
                </div>
              @endforeach
            </div>
          @else
            <p class="text-center text-muted m-0">Belum ada data barang tersedia.</p>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

  <script>
    // Pass Laravel data to JavaScript
    const barangsData = @json($barangs);

    document.addEventListener("DOMContentLoaded", function () {
      // ✅ Aktifkan fitur search Select2
      $('.js-example-basic-single').select2({
        placeholder: "Cari atau pilih barang...",
        allowClear: true,
        width: '100%'
      });

      const select = document.getElementById("barangSelect");
      const list = document.getElementById("barangList");
      const emptyText = document.getElementById("emptyText");
      const chatMessages = document.getElementById("chatMessages");
      const chatInput = document.getElementById("chatInput");
      const chatSendBtn = document.getElementById("chatSendBtn");

      // ============================================
      // AI CHAT FUNCTIONS
      // ============================================

      // Add message to chat
      function addMessage(content, isUser = false, isHtml = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = isUser ? 'user-message' : 'ai-message';
        
        const messageContent = document.createElement('div');
        messageContent.className = 'message-content';
        
        if (isHtml) {
          messageContent.innerHTML = content;
        } else {
          messageContent.textContent = content;
        }
        
        if (!isUser) {
          const avatar = document.createElement('div');
          avatar.className = 'message-avatar';
          avatar.textContent = '🤖';
          messageDiv.appendChild(avatar);
        }
        
        messageDiv.appendChild(messageContent);
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
      }

      // Process AI Chat
      function processAIChat(message) {
        const lowerMsg = message.toLowerCase().trim();
        
        // Greeting
        if (lowerMsg.match(/^(hai|halo|hi|hello|hey)/)) {
          addMessage('Halo! Ada yang bisa saya bantu? Tanyakan ketersediaan barang atau langsung tambahkan ke form!');
          return;
        }

        // Help command
        if (lowerMsg.match(/bantuan|help|tolong/)) {
          addMessage(`
            <p><strong>Perintah yang tersedia:</strong></p>
            <ul>
              <li>"apakah ada [nama barang]?" - Cek stok</li>
              <li>"info [nama barang]" - Lihat detail</li>
              <li>"tambahin [nama barang] [jumlah]" - Tambah ke form</li>
              <li>"barang kategori [kategori]" - Lihat per kategori</li>
            </ul>
          `, false, true);
          return;
        }

        // Cek ketersediaan - "apakah ada pulpen?"
        if (lowerMsg.match(/apakah ada|ada (ga|gak|tidak|nggak)?|tersedia/)) {
          const keyword = lowerMsg.replace(/apakah ada|ada (ga|gak|tidak|nggak)?|tersedia|\?/gi, '').trim();
          const found = barangsData.filter(b => 
            b.nama_barang.toLowerCase().includes(keyword)
          );

          if (found.length > 0) {
            let response = `<p>Saya menemukan ${found.length} barang:</p><ul>`;
            found.forEach(b => {
              const statusStok = b.stok > 0 ? `✅ <strong>Tersedia</strong> (${b.stok} ${b.satuan || 'pcs'})` : '❌ <strong>Habis</strong>';
              response += `<li><strong>${b.nama_barang}</strong> - ${statusStok}</li>`;
            });
            response += '</ul>';
            addMessage(response, false, true);
          } else {
            addMessage(`Maaf, saya tidak menemukan barang dengan kata kunci "${keyword}". Coba kata kunci lain atau lihat daftar stok.`);
          }
          return;
        }

        // Info detail - "info pulpen"
        if (lowerMsg.match(/^info/)) {
          const keyword = lowerMsg.replace(/^info/i, '').trim();
          const found = barangsData.filter(b => 
            b.nama_barang.toLowerCase().includes(keyword)
          );

          if (found.length > 0) {
            const barang = found[0];
            let response = `
              <div class="barang-info-card">
                ${barang.foto ? `<img src="/storage/${barang.foto}" alt="${barang.nama_barang}" style="max-width:120px; border-radius:8px; margin-bottom:10px;">` : ''}
                <p><strong>Nama:</strong> ${barang.nama_barang}</p>
                <p><strong>Kode:</strong> ${barang.kode_barang}</p>
                <p><strong>Stok:</strong> ${barang.stok} ${barang.satuan || 'pcs'}</p>
                ${barang.kategori ? `<p><strong>Kategori:</strong> ${barang.kategori}</p>` : ''}
              </div>
            `;
            addMessage(response, false, true);
          } else {
            addMessage(`Barang "${keyword}" tidak ditemukan.`);
          }
          return;
        }

        // Tambah ke form - "tambahin pulpen 5"
        if (lowerMsg.match(/tambahin|tambahkan|add|masukin/)) {
          const parts = lowerMsg.match(/(?:tambahin|tambahkan|add|masukin)\s+(.+?)\s+(\d+)/);
          
          if (parts) {
            const keyword = parts[1].trim();
            const jumlah = parseInt(parts[2]);
            
            const found = barangsData.find(b => 
              b.nama_barang.toLowerCase().includes(keyword)
            );

            if (found) {
              if (jumlah > found.stok) {
                addMessage(`⚠️ Jumlah yang diminta (${jumlah}) melebihi stok tersedia (${found.stok} ${found.satuan || 'pcs'}). Silakan kurangi jumlahnya.`);
                return;
              }

              // Trigger add to form
              addBarangToForm(found.id, found.nama_barang, found.stok, jumlah);
              addMessage(`✅ Berhasil menambahkan <strong>${found.nama_barang}</strong> sebanyak <strong>${jumlah} ${found.satuan || 'pcs'}</strong> ke form!`, false, true);
            } else {
              addMessage(`Barang "${keyword}" tidak ditemukan. Coba cek ejaan atau lihat daftar stok.`);
            }
          } else {
            addMessage('Format salah. Contoh: "tambahin pulpen 5"');
          }
          return;
        }

        // Kategori - "barang kategori ATK Tulis"
        if (lowerMsg.match(/kategori/)) {
          const keyword = lowerMsg.replace(/barang|kategori/gi, '').trim();
          const found = barangsData.filter(b => 
            b.kategori && b.kategori.toLowerCase().includes(keyword)
          );

          if (found.length > 0) {
            let response = `<p>Barang kategori <strong>${keyword}</strong>:</p><ul>`;
            found.forEach(b => {
              response += `<li><strong>${b.nama_barang}</strong> - Stok: ${b.stok} ${b.satuan || 'pcs'}</li>`;
            });
            response += '</ul>';
            addMessage(response, false, true);
          } else {
            addMessage(`Tidak ada barang dengan kategori "${keyword}".`);
          }
          return;
        }

        // Default: cari barang by name
        const found = barangsData.filter(b => 
          b.nama_barang.toLowerCase().includes(lowerMsg)
        );

        if (found.length > 0) {
          let response = `<p>Hasil pencarian:</p><ul>`;
          found.forEach(b => {
            response += `<li><strong>${b.nama_barang}</strong> - Stok: ${b.stok} ${b.satuan || 'pcs'}</li>`;
          });
          response += '</ul><p><small>Ketik "info [nama barang]" untuk detail atau "tambahin [nama barang] [jumlah]" untuk menambah ke form.</small></p>';
          addMessage(response, false, true);
        } else {
          addMessage('Maaf, saya tidak mengerti. Ketik "bantuan" untuk melihat perintah yang tersedia.');
        }
      }

      // Chat send handler
      function sendMessage() {
        const message = chatInput.value.trim();
        if (message === '') return;

        addMessage(message, true);
        chatInput.value = '';

        setTimeout(() => {
          processAIChat(message);
        }, 500);
      }

      chatSendBtn.addEventListener('click', sendMessage);
      chatInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage();
      });

      // ============================================
      // FORM FUNCTIONS (Existing)
      // ============================================

      // Function to add barang to form
      function addBarangToForm(id, nama, stok, jumlah = 1) {
        if (document.getElementById(`barang-item-${id}`)) {
          addMessage(`⚠️ ${nama} sudah ada di form!`, false, true);
          return;
        }

        emptyText.style.display = "none";

        const div = document.createElement("div");
        div.classList.add("border", "p-3", "mb-3", "rounded");
        div.id = `barang-item-${id}`;
        div.innerHTML = `
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <input type="hidden" name="barangs[${id}][barang_id]" value="${id}">
              <strong>${nama}</strong><br>
              <small class="text-muted">Stok: ${stok}</small>
            </div>
            <div class="d-flex align-items-center gap-2">
              <button type="button" class="btn btn-outline-secondary btn-sm minus-btn">−</button>
              <input type="number" name="barangs[${id}][jumlah]" class="form-control text-center" value="${jumlah}" min="1" max="${stok}" style="width:70px">
              <button type="button" class="btn btn-outline-success btn-sm plus-btn">+</button>
              <button type="button" class="btn btn-outline-danger btn-sm remove-btn">×</button>
            </div>
          </div>
          <div class="mt-2">
            <textarea name="barangs[${id}][catatan]" class="form-control form-control-sm" placeholder="Tambahkan catatan untuk ${nama}" rows="2"></textarea>
          </div>
        `;
        list.appendChild(div);
      }

      // Tambah barang ke daftar saat dipilih
      $('#barangSelect').on('select2:select', function (e) {
        const selectedOption = e.params.data.element;
        const id = selectedOption.value;
        const nama = selectedOption.dataset.nama;
        const stok = selectedOption.dataset.stok;

        if (!id || document.getElementById(`barang-item-${id}`)) return;

        addBarangToForm(id, nama, stok);

        // reset dropdown setelah pilih
        $('#barangSelect').val(null).trigger('change');
      });

      // Tombol tambah/kurang/hapus barang
      list.addEventListener("click", function (e) {
        if (e.target.classList.contains("plus-btn")) {
          const input = e.target.parentElement.querySelector("input[type='number']");
          if (parseInt(input.value) < parseInt(input.max)) input.value++;
        } else if (e.target.classList.contains("minus-btn")) {
          const input = e.target.parentElement.querySelector("input[type='number']");
          if (parseInt(input.value) > 1) input.value--;
        } else if (e.target.classList.contains("remove-btn")) {
          e.target.closest(".border").remove();
          if (list.querySelectorAll(".border").length === 0) emptyText.style.display = "block";
        }
      });

      // ✍️ Inisialisasi tanda tangan
      const canvas = document.getElementById("signature-pad");
      const signaturePad = new SignaturePad(canvas);
      const clearBtn = document.getElementById("clear-signature");
      const inputTandaTangan = document.getElementById("tanda_tangan");

      clearBtn.addEventListener("click", () => signaturePad.clear());

      document.getElementById("multiForm").addEventListener("submit", function (e) {
        inputTandaTangan.value = signaturePad.isEmpty() ? "" : signaturePad.toDataURL("image/png");
      });
    });
  </script>
</body>
</html>
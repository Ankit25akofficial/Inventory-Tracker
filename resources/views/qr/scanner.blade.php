<x-app-layout>
    <x-slot name="title">QR Scanner</x-slot>

    <div class="page-header">
        <div>
            <h1 class="page-title">QR Scanner</h1>
            <div class="page-subtitle">Scan product QR codes to manage inventory</div>
        </div>
    </div>

    <style>
        /* ─── Suppress ALL default html5-qrcode chrome ─── */
        #reader { border:none!important; background:transparent!important; padding:0!important; width:100%!important; }
        #reader__header_message { display:none!important; }
        #reader__status_span    { display:none!important; }
        #reader img             { display:none!important; }

        /* Camera selector row */
        #reader__camera_selection {
            width:100%!important; background:rgba(15,23,42,.8)!important;
            color:#f1f5f9!important; border:1px solid rgba(255,255,255,.1)!important;
            border-radius:10px!important; padding:10px 14px!important;
            font-size:13px!important; outline:none!important; margin-bottom:0!important;
            font-family:inherit!important;
        }
        #reader__camera_permission_button,
        #reader__camera_start_button {
            width:100%!important; background:linear-gradient(135deg,#6366f1,#a855f7)!important;
            color:#fff!important; border:none!important; border-radius:10px!important;
            padding:12px 20px!important; font-size:14px!important; font-weight:600!important;
            cursor:pointer!important; transition:all .25s!important; margin-top:12px!important;
            box-shadow:0 4px 14px rgba(99,102,241,.35)!important; font-family:inherit!important;
            letter-spacing:.01em!important;
        }
        #reader__camera_start_button:hover,
        #reader__camera_permission_button:hover {
            transform:translateY(-2px)!important;
            box-shadow:0 8px 24px rgba(99,102,241,.5)!important;
        }
        #reader__camera_stop_button {
            width:100%!important; background:rgba(248,113,113,.12)!important;
            color:#f87171!important; border:1px solid rgba(248,113,113,.25)!important;
            border-radius:10px!important; padding:10px 20px!important;
            font-size:13px!important; font-weight:600!important; cursor:pointer!important;
            transition:all .25s!important; margin-top:8px!important; font-family:inherit!important;
        }
        #reader__dashboard_section_swaplink { display:none!important; }
        #reader__scan_region {
            border-radius:16px!important; overflow:hidden!important;
            background:#0a0e1a!important; border:1px solid rgba(255,255,255,.06)!important;
        }
        #reader__scan_region video { border-radius:14px!important; width:100%!important; }
        #reader__scan_region img   { display:none!important; }
        #reader__filescan_input    { display:none!important; }

        #reader__dashboard_section { padding:0!important; margin-top:16px!important; }
        #reader__dashboard_section_csr { display:flex!important; flex-direction:column!important; gap:0!important; }
        #reader__dashboard_section_csr span { color:#94a3b8!important; font-size:13px!important; margin-bottom:8px!important; }

        /* ─── Mode buttons ─── */
        .mode-btn {
            flex:1; padding:14px 8px; border:1px solid rgba(255,255,255,.07);
            background:rgba(255,255,255,.02); border-radius:14px; cursor:pointer;
            color:#475569; font-size:12px; font-weight:600; text-align:center;
            transition:all .25s; display:flex; flex-direction:column; align-items:center; gap:8px;
            letter-spacing:.02em; text-transform:uppercase;
        }
        .mode-btn .icon { font-size:20px; filter:grayscale(1); transition:filter .25s; }
        .mode-btn.active {
            background:linear-gradient(135deg,rgba(99,102,241,.18),rgba(168,85,247,.1));
            border-color:rgba(99,102,241,.5); color:#c4b5fd;
            box-shadow:0 0 0 1px rgba(99,102,241,.2), 0 4px 16px rgba(99,102,241,.1);
        }
        .mode-btn.active .icon { filter:grayscale(0); }
        .mode-btn:hover:not(.active) { border-color:rgba(255,255,255,.12); color:#94a3b8; background:rgba(255,255,255,.04); }

        /* ─── Drop zone ─── */
        .drop-zone {
            border:2px dashed rgba(99,102,241,.25); border-radius:16px;
            padding:28px 16px; text-align:center; cursor:pointer;
            background:rgba(99,102,241,.03); transition:all .25s; display:block; width:100%;
        }
        .drop-zone:hover { border-color:rgba(99,102,241,.55); background:rgba(99,102,241,.07); }

        /* ─── Stat rows ─── */
        .qr-stat {
            display:flex; justify-content:space-between; align-items:center;
            padding:12px 0; border-bottom:1px solid rgba(255,255,255,.04);
        }
        .qr-stat:last-child { border-bottom:none; padding-bottom:0; }
        .qr-stat-label { font-size:12px; color:#475569; text-transform:uppercase; letter-spacing:.06em; }
        .qr-stat-val   { font-size:18px; font-weight:700; }

        /* ─── Spinner + border animations ─── */
        @keyframes spin         { to { transform:rotate(360deg); } }
        @keyframes rotateBorder { to { transform:rotate(360deg); } }

        /* ─── Scan result ─── */
        @keyframes slideUp {
            from { opacity:0; transform:translateY(12px); }
            to   { opacity:1; transform:translateY(0); }
        }
        @keyframes glowGreen { 0%,100%{box-shadow:0 0 0 0 rgba(16,185,129,0);} 50%{box-shadow:0 0 0 8px rgba(16,185,129,.15);} }
        @keyframes glowRed   { 0%,100%{box-shadow:0 0 0 0 rgba(248,113,113,0);} 50%{box-shadow:0 0 0 8px rgba(248,113,113,.15);} }
        .result-in      { animation:slideUp .3s cubic-bezier(.16,1,.3,1) both; }
        .glow-success   { animation:glowGreen 1s ease-out; border-color:rgba(16,185,129,.3)!important; }
        .glow-error     { animation:glowRed   1s ease-out; border-color:rgba(248,113,113,.3)!important; }

        .product-chip {
            background:linear-gradient(135deg,rgba(99,102,241,.12),rgba(168,85,247,.08));
            border:1px solid rgba(99,102,241,.2); border-radius:14px; padding:16px;
        }
    </style>

    <div style="display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start;">

        {{-- ════ LEFT: Scanner ════ --}}
        <div class="card">
            <div class="card-header" style="border-bottom:1px solid rgba(255,255,255,.05);">
                <div class="card-title">
                    <i class="fas fa-camera" style="color:#6366f1;margin-right:8px;"></i>Camera Scanner
                </div>
                <span style="font-size:12px;color:#334155;">Point at any product QR code</span>
            </div>
            <div class="card-body" style="padding:24px;">

                {{-- Tabs: Upload (default) / Camera --}}
                <div style="display:flex;gap:4px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:12px;padding:4px;margin-bottom:20px;">
                    <button id="tabUpload" onclick="switchTab('upload')"
                        style="flex:1;padding:9px;border:none;border-radius:9px;cursor:pointer;font-size:13px;font-weight:600;transition:all .2s;background:linear-gradient(135deg,#6366f1,#a855f7);color:#fff;box-shadow:0 2px 8px rgba(99,102,241,.3);">
                        <i class="fas fa-image" style="margin-right:6px;"></i>Upload Image
                    </button>
                    <button id="tabCamera" onclick="switchTab('camera')"
                        style="flex:1;padding:9px;border:none;border-radius:9px;cursor:pointer;font-size:13px;font-weight:600;transition:all .2s;background:transparent;color:#475569;">
                        <i class="fas fa-video" style="margin-right:6px;"></i>Camera Scan
                    </button>
                </div>

                {{-- Upload Panel (shown by default) --}}
                <div id="panelUpload">
                    <label for="fileUploadCustom" class="drop-zone">
                        <i class="fas fa-cloud-upload-alt" style="font-size:36px;color:#6366f1;display:block;margin-bottom:12px;"></i>
                        <div style="font-weight:600;color:#cbd5e1;font-size:15px;margin-bottom:4px;">Drop QR image here</div>
                        <div style="font-size:12px;color:#475569;">PNG, JPG, or GIF supported</div>
                        <input type="file" id="fileUploadCustom" accept="image/*" style="display:none;">
                    </label>
                    <div id="uploadReaderWrap" style="display:none;margin-top:16px;">
                        <div id="readerUpload"></div>
                    </div>
                </div>

                {{-- Camera Panel (hidden by default) --}}
                <div id="panelCamera" style="display:none;">
                    <div id="reader"></div>
                </div>

                <div style="margin-top:18px;display:flex;align-items:center;gap:8px;color:#1e293b;font-size:12px;background:rgba(255,255,255,.02);border:1px solid rgba(255,255,255,.04);border-radius:8px;padding:10px 14px;">
                    <i class="fas fa-lock" style="color:#6366f1;"></i>
                    All scans are securely logged and attributed to your account
                </div>
            </div>
        </div>

        {{-- ════ RIGHT: Controls + Result ════ --}}
        <div style="display:flex;flex-direction:column;gap:20px;">

            {{-- Scan Mode --}}
            <div class="card">
                <div class="card-header" style="border-bottom:1px solid rgba(255,255,255,.05);">
                    <div class="card-title">
                        <i class="fas fa-bolt" style="color:#f59e0b;margin-right:8px;"></i>Scan Mode
                    </div>
                </div>
                <div class="card-body" style="padding:20px;">
                    <div style="display:flex;gap:10px;">
                        <button class="mode-btn active" id="modeCheck" onclick="setMode('check')">
                            <span class="icon">👁️</span><span>Check</span>
                        </button>
                        <button class="mode-btn" id="modeIn" onclick="setMode('in')">
                            <span class="icon">📦</span><span>Stock In</span>
                        </button>
                        <button class="mode-btn" id="modeOut" onclick="setMode('out')">
                            <span class="icon">🚚</span><span>Stock Out</span>
                        </button>
                    </div>
                    <div id="qtyGroup" style="display:none;margin-top:16px;">
                        <label class="form-label" style="font-size:12px;margin-bottom:6px;">Quantity</label>
                        <input type="number" id="scanQty" class="form-control" value="1" min="1"
                               style="text-align:center;font-size:18px;font-weight:700;">
                    </div>
                </div>
            </div>

            {{-- Scan Result --}}
            <div class="card" id="scanResult" style="display:none;border:1px solid rgba(255,255,255,.06);">
                <div class="card-header" style="border-bottom:1px solid rgba(255,255,255,.05);">
                    <div class="card-title" id="scanStatus">Result</div>
                </div>
                <div class="card-body" style="padding:20px;">

                    {{-- QR Preview + loading state --}}
                    <div id="scanningState" style="display:none;text-align:center;padding:8px 0 16px;">
                        <div style="position:relative;display:inline-block;margin-bottom:14px;">
                            <img id="qrPreviewImg" src="" alt="QR Code"
                                 style="width:100px;height:100px;object-fit:contain;border-radius:12px;
                                        border:2px solid rgba(99,102,241,.4);background:#0a0e1a;padding:6px;"/>
                            <div style="position:absolute;inset:-4px;border-radius:16px;border:2px solid transparent;
                                        background:linear-gradient(135deg,#6366f1,#a855f7) border-box;
                                        -webkit-mask:linear-gradient(#fff 0 0) padding-box,linear-gradient(#fff 0 0);
                                        -webkit-mask-composite:destination-out;mask-composite:exclude;
                                        animation:rotateBorder 1.5s linear infinite;"></div>
                        </div>
                        <div style="display:flex;align-items:center;justify-content:center;gap:8px;color:#a855f7;font-size:13px;font-weight:600;">
                            <span class="spinner" style="width:14px;height:14px;border:2px solid rgba(168,85,247,.3);border-top-color:#a855f7;border-radius:50%;display:inline-block;animation:spin .7s linear infinite;"></span>
                            Processing QR Code...
                        </div>
                    </div>

                    <div id="productInfo"></div>
                    <a id="productLink" href="#"
                       style="display:none;align-items:center;justify-content:center;gap:8px;
                              margin-top:16px;padding:12px;border-radius:12px;text-decoration:none;
                              background:linear-gradient(135deg,rgba(99,102,241,.15),rgba(168,85,247,.1));
                              border:1px solid rgba(99,102,241,.25);color:#c4b5fd;font-size:13px;font-weight:600;">
                        View Full Details <i class="fas fa-arrow-right" style="font-size:11px;"></i>
                    </a>
                </div>
            </div>

            {{-- Session Stats --}}
            <div class="card">
                <div class="card-header" style="border-bottom:1px solid rgba(255,255,255,.05);">
                    <div class="card-title">
                        <i class="fas fa-chart-pie" style="color:#a855f7;margin-right:8px;"></i>Session Stats
                    </div>
                </div>
                <div class="card-body" style="padding:20px;">
                    <div class="qr-stat">
                        <span class="qr-stat-label">Total Scans</span>
                        <span class="qr-stat-val" id="statTotal" style="color:#f1f5f9;">0</span>
                    </div>
                    <div class="qr-stat">
                        <span class="qr-stat-label">Successful</span>
                        <span class="qr-stat-val" id="statSuccess" style="color:#10b981;">0</span>
                    </div>
                    <div class="qr-stat">
                        <span class="qr-stat-label">Failed</span>
                        <span class="qr-stat-val" id="statFailed" style="color:#f87171;">0</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        let mode = 'check', processing = false;
        let total = 0, success = 0, failed = 0;
        let cameraScanner = null;

        // ── Tab switching ──
        const GradientStyle = 'linear-gradient(135deg,#6366f1,#a855f7)';
        window.switchTab = function(tab) {
            const isCamera = tab === 'camera';
            document.getElementById('panelCamera').style.display = isCamera ? 'block' : 'none';
            document.getElementById('panelUpload').style.display = isCamera ? 'none'  : 'block';

            const btnCam = document.getElementById('tabCamera');
            const btnUp  = document.getElementById('tabUpload');

            // Reset both
            [btnCam, btnUp].forEach(b => {
                b.style.background  = 'transparent';
                b.style.color       = '#475569';
                b.style.boxShadow   = 'none';
            });

            // Activate chosen
            const active = isCamera ? btnCam : btnUp;
            active.style.background = GradientStyle;
            active.style.color      = '#fff';
            active.style.boxShadow  = '0 2px 8px rgba(99,102,241,.3)';

            if (isCamera) {
                startCameraScanner();
            } else {
                if (cameraScanner) { try { cameraScanner.clear(); } catch(e){} cameraScanner = null; }
            }
        };

        // ── Camera scanner ──
        function startCameraScanner() {
            if (cameraScanner) return;
            cameraScanner = new Html5QrcodeScanner('reader', { fps:10, qrbox:{width:240,height:240} }, false);
            cameraScanner.render(handleScan, ()=>{});
        }
        // Default tab: Upload (do NOT auto-start camera)

        // ── File upload ──
        document.getElementById('fileUploadCustom').addEventListener('change', function(e) {
            const file = e.target.files[0]; if (!file) return;

            // Show QR preview immediately
            const reader = new FileReader();
            reader.onload = function(ev) {
                showScanningState(ev.target.result);
            };
            reader.readAsDataURL(file);

            document.getElementById('uploadReaderWrap').style.display = 'block';
            const html5QrCode = new Html5Qrcode('readerUpload');
            html5QrCode.scanFile(file, true)
                .then(text => { handleScan(text, file); html5QrCode.clear(); })
                .catch(() => { showResult(false, null, 'Could not read QR from image.'); html5QrCode.clear(); });
        });

        // ── Mode toggle ──
        window.setMode = function(m) {
            mode = m;
            ['Check','In','Out'].forEach(n => {
                document.getElementById('mode'+n).classList.toggle('active', n.toLowerCase() === m.replace('check','check').replace('in','in').replace('out','out'));
                // fix: mode IDs are modeCheck, modeIn, modeOut
            });
            document.getElementById('modeCheck').classList.toggle('active', m==='check');
            document.getElementById('modeIn').classList.toggle('active', m==='in');
            document.getElementById('modeOut').classList.toggle('active', m==='out');
            document.getElementById('qtyGroup').style.display = (m==='check') ? 'none' : 'block';
        };

        // ── Show scanning state with QR preview ──
        function showScanningState(imgSrc) {
            const card = document.getElementById('scanResult');
            card.style.display = 'block';
            card.className = 'card result-in';
            document.getElementById('scanStatus').innerHTML = `<i class="fas fa-qrcode" style="color:#6366f1;margin-right:6px;"></i>Scanning...`;
            document.getElementById('scanningState').style.display = 'block';
            document.getElementById('productInfo').innerHTML = '';
            document.getElementById('productLink').style.display = 'none';
            if (imgSrc) document.getElementById('qrPreviewImg').src = imgSrc;
        }

        // ── Handle scanned result ──
        function handleScan(text, file) {
            if (processing) return;
            processing = true;
            total++; document.getElementById('statTotal').textContent = total;

            // If coming from camera (no file), show scanning state with QR icon
            if (!file) {
                showScanningState(null);
                document.getElementById('qrPreviewImg').src = 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect fill="%230a0e1a" width="100" height="100"/><text y=".9em" font-size="80" x="10">📷</text></svg>';
            }

            try { new Audio('https://assets.mixkit.co/sfx/preview/mixkit-positive-notification-951.mp3').play(); } catch(e){}

            fetch("{{ route('qr.scan') }}", {
                method:'POST',
                headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                body:JSON.stringify({ sku:text, action:mode, quantity:document.getElementById('scanQty').value })
            })
            .then(r=>r.json())
            .then(data => {
                document.getElementById('scanningState').style.display = 'none';
                if (data.success) {
                    success++; document.getElementById('statSuccess').textContent = success;
                    showResult(true, data.product, null);
                } else {
                    failed++; document.getElementById('statFailed').textContent = failed;
                    showResult(false, null, data.message);
                }
                setTimeout(()=>{ processing = false; }, 2500);
            })
            .catch(()=>{
                document.getElementById('scanningState').style.display = 'none';
                setTimeout(()=>{ processing = false; }, 2500);
            });
        }

        window.handleScan = handleScan;

        function showResult(ok, product, errMsg) {
            const card   = document.getElementById('scanResult');
            const status = document.getElementById('scanStatus');
            const info   = document.getElementById('productInfo');
            const link   = document.getElementById('productLink');

            card.style.display = 'block';
            card.className = 'card result-in';
            card.classList.add(ok ? 'glow-success' : 'glow-error');

            if (ok) {
                const modeLabel = {check:'Info Retrieved', in:'Stock Added', out:'Stock Deducted'}[mode];
                status.innerHTML = `<i class="fas fa-check-circle" style="color:#10b981;margin-right:6px;"></i>${modeLabel}`;
                info.innerHTML = `
                    <div class="product-chip" style="margin-bottom:14px;">
                        <div style="font-size:16px;font-weight:700;color:#f1f5f9;margin-bottom:4px;">${product.name}</div>
                        <code style="font-size:11px;color:#475569;">${product.sku}</code>
                    </div>
                    <div style="display:flex;gap:10px;">
                        <div style="flex:1;background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.15);border-radius:10px;padding:12px;text-align:center;">
                            <div style="font-size:11px;color:#64748b;margin-bottom:4px;">STOCK</div>
                            <div style="font-size:22px;font-weight:700;color:#10b981;">${product.quantity}</div>
                        </div>
                        <div style="flex:1;background:rgba(168,85,247,.08);border:1px solid rgba(168,85,247,.15);border-radius:10px;padding:12px;text-align:center;">
                            <div style="font-size:11px;color:#64748b;margin-bottom:4px;">PRICE</div>
                            <div style="font-size:22px;font-weight:700;color:#a855f7;">$${product.price}</div>
                        </div>
                    </div>`;
                link.href = product.url;
                link.style.display = 'flex';
            } else {
                status.innerHTML = `<i class="fas fa-exclamation-circle" style="color:#f87171;margin-right:6px;"></i>Scan Failed`;
                info.innerHTML = `<div style="color:#f87171;background:rgba(248,113,113,.08);border:1px solid rgba(248,113,113,.2);border-radius:10px;padding:14px;font-size:14px;">${errMsg}</div>`;
                link.style.display = 'none';
            }
        }
    });
    </script>
    @endpush
</x-app-layout>

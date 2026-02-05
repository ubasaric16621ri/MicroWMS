<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nano WMS Ops</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:300,400,500,600,700,800|space-mono:400,700&display=swap" rel="stylesheet" />
    <style>
        :root {
            --bg: #0b0f14;
            --bg-2: #0f172a;
            --panel: #111827;
            --panel-2: #0b1220;
            --text: #e2e8f0;
            --muted: #9aa4b2;
            --accent: #f97316;
            --accent-2: #38bdf8;
            --accent-3: #22c55e;
            --border: rgba(148, 163, 184, 0.2);
            --ring: rgba(56, 189, 248, 0.35);
            --shadow: rgba(2, 6, 23, 0.6);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Manrope", system-ui, sans-serif;
            color: var(--text);
            background:
                radial-gradient(900px 500px at 12% -10%, #1f2937 0%, transparent 60%),
                radial-gradient(900px 500px at 90% 10%, #0ea5e9 0%, transparent 55%),
                linear-gradient(160deg, var(--bg), var(--bg-2));
            min-height: 100vh;
        }

        .page {
            max-width: 1200px;
            margin: 0 auto;
            padding: 28px 20px 64px;
        }

        .topbar {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
        }

        .brand {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .logo {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: conic-gradient(from 140deg, #f97316, #38bdf8, #22c55e, #f97316);
            display: grid;
            place-items: center;
            font-weight: 800;
            color: #0b1220;
            letter-spacing: 0.5px;
            box-shadow: 0 16px 30px rgba(15, 23, 42, 0.6);
        }

        .brand h1 {
            margin: 0;
            font-size: 24px;
        }

        .brand p {
            margin: 2px 0 0;
            color: var(--muted);
            font-size: 13px;
        }

        .quick-stats {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .stat {
            padding: 10px 14px;
            border-radius: 14px;
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid var(--border);
            font-size: 12px;
            display: grid;
            gap: 4px;
        }

        .stat strong {
            font-size: 15px;
        }

        .toolbar {
            display: grid;
            gap: 12px;
            margin-bottom: 22px;
            padding: 16px;
            border-radius: 18px;
            background: linear-gradient(150deg, rgba(15, 23, 42, 0.8), rgba(11, 18, 32, 0.9));
            border: 1px solid var(--border);
            box-shadow: 0 12px 30px var(--shadow);
        }

        .toolbar label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            color: var(--muted);
        }

        .toolbar-row {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: center;
            justify-content: space-between;
        }

        .toolbar input {
            min-width: 260px;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid rgba(148, 163, 184, 0.3);
            background: rgba(8, 13, 25, 0.9);
            color: var(--text);
            outline: none;
        }

        .toolbar input:focus {
            border-color: var(--accent-2);
            box-shadow: 0 0 0 3px var(--ring);
        }

        .chip {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            padding: 6px 12px;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: rgba(15, 23, 42, 0.6);
            font-size: 12px;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--accent-3);
            box-shadow: 0 0 8px rgba(34, 197, 94, 0.7);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
            gap: 18px;
        }

        .card {
            background: linear-gradient(160deg, rgba(15, 23, 42, 0.95), rgba(10, 16, 30, 0.95));
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 18px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 40px var(--shadow);
            animation: rise 0.7s ease both;
        }

        .card::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(240px 120px at 90% 0%, rgba(56, 189, 248, 0.15), transparent 60%);
            pointer-events: none;
        }

        .card h2 {
            margin: 0 0 10px;
            font-size: 18px;
        }

        .card p {
            margin: 0 0 14px;
            color: var(--muted);
            font-size: 13px;
        }

        .field {
            display: grid;
            gap: 6px;
            margin-bottom: 10px;
        }

        .field label {
            font-size: 11px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .field input {
            width: 100%;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid rgba(148, 163, 184, 0.25);
            background: rgba(8, 13, 25, 0.9);
            color: var(--text);
            outline: none;
        }

        .field input:focus {
            border-color: var(--accent-2);
            box-shadow: 0 0 0 3px var(--ring);
        }

        .btn {
            width: 100%;
            border: none;
            background: linear-gradient(120deg, var(--accent), #fbbf24);
            color: #0b1220;
            padding: 10px 14px;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.2s ease;
        }

        .btn.secondary {
            background: linear-gradient(120deg, #38bdf8, #60a5fa);
        }

        .btn.tertiary {
            background: linear-gradient(120deg, #22c55e, #4ade80);
        }

        .btn:hover { transform: translateY(-1px); }

        .output {
            margin-top: 12px;
            padding: 10px;
            border-radius: 10px;
            background: rgba(2, 6, 23, 0.7);
            border: 1px solid rgba(148, 163, 184, 0.15);
            font-family: "Space Mono", ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 12px;
            line-height: 1.4;
            max-height: 200px;
            overflow: auto;
            white-space: pre-wrap;
        }

        .strip {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
        }

        footer {
            margin-top: 32px;
            color: var(--muted);
            font-size: 12px;
            text-align: center;
        }

        @keyframes rise {
            from { transform: translateY(12px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="topbar">
            <div class="brand">
                <div class="logo">NW</div>
                <div>
                    <h1>Nano WMS Ops</h1>
                    <p>Real-time inventory actions and operational visibility.</p>
                </div>
            </div>
            <div class="quick-stats">
                <div class="stat"><span>Environment</span><strong>Local</strong></div>
                <div class="stat"><span>Mode</span><strong>Live</strong></div>
                <div class="stat"><span>Route Set</span><strong>Web</strong></div>
            </div>
        </div>

        <div class="toolbar">
            <div class="toolbar-row">
                <div>
                    <label for="base_url">Base URL (optional)</label>
                    <input id="base_url" type="text" placeholder="http://localhost" />
                </div>
                <div class="strip">
                    <div class="chip"><span class="dot"></span>Connected</div>
                    <div class="chip">Routes: `/inventory/*`, `/dashboard`, `/locations/*`</div>
                </div>
            </div>
        </div>

        <section class="grid">
            <div class="card">
                <h2>Inventory In</h2>
                <p>Receive stock into a location.</p>
                <div class="field">
                    <label>Product ID</label>
                    <input id="in_product" type="number" min="1" placeholder="1" />
                </div>
                <div class="field">
                    <label>Location ID</label>
                    <input id="in_location" type="number" min="1" placeholder="1" />
                </div>
                <div class="field">
                    <label>Quantity</label>
                    <input id="in_quantity" type="number" min="1" placeholder="10" />
                </div>
                <button class="btn" data-action="inventory-in">Receive Inventory</button>
                <div class="output" id="out_in">Ready.</div>
            </div>

            <div class="card">
                <h2>Move Stock</h2>
                <p>Transfer inventory between locations.</p>
                <div class="field">
                    <label>Product ID</label>
                    <input id="move_product" type="number" min="1" placeholder="1" />
                </div>
                <div class="field">
                    <label>From Location ID</label>
                    <input id="move_from" type="number" min="1" placeholder="1" />
                </div>
                <div class="field">
                    <label>To Location ID</label>
                    <input id="move_to" type="number" min="1" placeholder="2" />
                </div>
                <div class="field">
                    <label>Quantity</label>
                    <input id="move_quantity" type="number" min="1" placeholder="3" />
                </div>
                <button class="btn" data-action="inventory-move">Transfer Stock</button>
                <div class="output" id="out_move">Ready.</div>
            </div>

            <div class="card">
                <h2>Dashboard Snapshot</h2>
                <p>Totals by product and empty locations.</p>
                <div class="field">
                    <label>Per Page</label>
                    <input id="dash_per" type="number" min="1" max="100" placeholder="15" />
                </div>
                <button class="btn tertiary" data-action="dashboard">Load Dashboard</button>
                <div class="output" id="out_dashboard">Ready.</div>
            </div>

            <div class="card">
                <h2>Location Lookup</h2>
                <p>Inventory and logs for one location.</p>
                <div class="field">
                    <label>Location ID</label>
                    <input id="loc_id" type="number" min="1" placeholder="1" />
                </div>
                <button class="btn secondary" data-action="location">Load Location</button>
                <div class="output" id="out_location">Ready.</div>
            </div>
        </section>

        <footer>
            WMS Ops Console for quick manual testing.
        </footer>
    </div>

    <script>
        const baseInput = document.getElementById('base_url');

        function getBase() {
            const raw = baseInput.value.trim();
            if (!raw) return '';
            return raw.replace(/\/+$/, '');
        }

        function buildUrl(path) {
            const base = getBase();
            if (!base) return path;
            return base + path;
        }

        async function apiRequest(method, path, body, output) {
            const url = buildUrl(path);
            const options = {
                method,
                headers: {
                    'Accept': 'application/json',
                },
            };

            if (body) {
                options.headers['Content-Type'] = 'application/json';
                options.body = JSON.stringify(body);
            }

            output.textContent = 'Loading...';

            try {
                const response = await fetch(url, options);
                const contentType = response.headers.get('content-type') || '';
                let payload;

                if (contentType.includes('application/json')) {
                    payload = await response.json();
                } else {
                    payload = await response.text();
                }

                const status = `Status ${response.status} ${response.statusText}`;
                const bodyText = typeof payload === 'string' ? payload : JSON.stringify(payload, null, 2);
                output.textContent = `${status}\nURL ${url}\n\n${bodyText || '(empty response)'}`;
            } catch (error) {
                output.textContent = `Error\n\n${error.message}`;
            }
        }

        document.querySelector('[data-action="inventory-in"]').addEventListener('click', () => {
            apiRequest('POST', '/inventory/in', {
                product_id: Number(document.getElementById('in_product').value),
                location_id: Number(document.getElementById('in_location').value),
                quantity: Number(document.getElementById('in_quantity').value),
            }, document.getElementById('out_in'));
        });

        document.querySelector('[data-action="inventory-move"]').addEventListener('click', () => {
            apiRequest('POST', '/inventory/move', {
                product_id: Number(document.getElementById('move_product').value),
                from_location_id: Number(document.getElementById('move_from').value),
                to_location_id: Number(document.getElementById('move_to').value),
                quantity: Number(document.getElementById('move_quantity').value),
            }, document.getElementById('out_move'));
        });

        document.querySelector('[data-action="dashboard"]').addEventListener('click', () => {
            const perPageValue = document.getElementById('dash_per').value;
            const perPage = perPageValue ? `?per_page=${encodeURIComponent(perPageValue)}` : '';
            apiRequest('GET', `/dashboard${perPage}`, null, document.getElementById('out_dashboard'));
        });

        document.querySelector('[data-action="location"]').addEventListener('click', () => {
            const id = Number(document.getElementById('loc_id').value);
            apiRequest('GET', `/locations/${id}`, null, document.getElementById('out_location'));
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page_title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --accent-primary: #38bdf8;
            --text-main: #f1f5f9;
            --text-dim: #94a3b8;
            --border-color: #334155;
            
            --get-color: #10b981;
            --post-color: #3b82f6;
            --put-color: #f59e0b;
            --delete-color: #ef4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            line-height: 1.6;
            padding: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            margin-bottom: 3rem;
            text-align: center;
        }

        header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(to right, #38bdf8, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        header p {
            color: var(--text-dim);
            font-size: 1.1rem;
        }

        .version-section {
            margin-bottom: 3rem;
        }

        .version-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--accent-primary);
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .api-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            margin-bottom: 1rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .api-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border-color: #475569;
        }

        .api-header {
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            cursor: pointer;
            gap: 1rem;
        }

        .method-badge {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            min-width: 70px;
            text-align: center;
            color: white;
            text-transform: uppercase;
        }

        .method-GET { background-color: var(--get-color); }
        .method-POST { background-color: var(--post-color); }
        .method-PUT, .method-PATCH { background-color: var(--put-color); }
        .method-DELETE { background-color: var(--delete-color); }

        .api-uri {
            font-family: 'Courier New', Courier, monospace;
            font-weight: 600;
            color: var(--text-main);
            flex-grow: 1;
        }

        .api-name {
            font-size: 0.85rem;
            color: var(--text-dim);
        }

        .api-body {
            padding: 0 1.5rem 1.5rem;
            border-top: 1px solid var(--border-color);
            display: none;
            background-color: rgba(15, 23, 42, 0.3);
        }

        .api-card.active .api-body {
            display: block;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .info-item h4 {
            font-size: 0.8rem;
            text-transform: uppercase;
            color: var(--text-dim);
            margin-bottom: 0.5rem;
            letter-spacing: 0.05em;
        }

        .info-item p, .info-item code {
            font-size: 0.9rem;
        }

        .middleware-tag {
            display: inline-block;
            background-color: #334155;
            color: #cbd5e1;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            margin-right: 0.4rem;
            margin-bottom: 0.4rem;
        }

        .action-code {
            color: #f472b6;
            font-family: monospace;
        }

        .description-box {
            margin-top: 1rem;
            padding: 1rem;
            background-color: #0f172a;
            border-radius: 8px;
            font-size: 0.9rem;
            color: #cbd5e1;
            border-left: 3px solid var(--accent-primary);
        }

        .chevron {
            transition: transform 0.3s ease;
        }

        .api-card.active .chevron {
            transform: rotate(180deg);
        }

        @media (max-width: 768px) {
            .api-header {
                flex-wrap: wrap;
            }
            .api-uri {
                width: 100%;
                order: 3;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-rocket"></i> API Documentation</h1>
            <p>Interactive documentation for all routes defined in your application</p>
        </header>

        <section class="version-section" style="margin-bottom: 4rem;">
            <h2 class="version-title" style="color: #f472b6;">
                <i class="fas fa-satellite-dish"></i> Live API Tracker
                <span style="font-size: 0.8rem; text-transform: none; font-weight: 400; color: var(--text-dim); margin-left: 1rem;">
                    Last 10 requests (Real-time monitoring)
                </span>
            </h2>
            <div class="api-card" style="padding: 1rem; background-color: #0b0f1a;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                    <thead>
                        <tr style="text-align: left; color: var(--text-dim); border-bottom: 1px solid var(--border-color);">
                            <th style="padding: 0.8rem;">TIME</th>
                            <th style="padding: 0.8rem;">METHOD</th>
                            <th style="padding: 0.8rem;">ENDPOINT</th>
                            <th style="padding: 0.8rem;">STATUS</th>
                            <th style="padding: 0.8rem;">IP ADDRESS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trackedRequests as $req)
                            <tr style="border-bottom: 1px solid rgba(51, 65, 85, 0.5);">
                                <td style="padding: 0.8rem; color: var(--text-dim);">{{ $req['time'] }}</td>
                                <td style="padding: 0.8rem;"><span class="method-badge method-{{ $req['method'] }}" style="padding: 0.15rem 0.4rem; min-width: 50px; font-size: 0.65rem;">{{ $req['method'] }}</span></td>
                                <td style="padding: 0.8rem;"><code style="color: var(--accent-primary);">/{{ $req['uri'] }}</code></td>
                                <td style="padding: 0.8rem;">
                                    <span style="color: {{ $req['status'] >= 400 ? 'var(--delete-color)' : 'var(--get-color)' }};">
                                        {{ $req['status'] }}
                                    </span>
                                </td>
                                <td style="padding: 0.8rem; color: var(--text-dim); font-size: 0.75rem;">{{ $req['ip'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-dim);">No requests tracked yet. Call some API endpoints to see them here!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <main>
            @foreach($groupedRoutes as $version => $routes)
                <section class="version-section">
                    <h2 class="version-title">Version: {{ strtoupper($version) }}</h2>
                    @foreach($routes as $route)
                        <div class="api-card">
                            <div class="api-header" onclick="this.parentElement.classList.toggle('active')">
                                <span class="method-badge method-{{ $route['methods'][0] ?? 'GET' }}">
                                    {{ $route['methods'][0] ?? 'GET' }}
                                </span>
                                <span class="api-uri">/{{ $route['uri'] }}</span>
                                <span class="api-name">{{ $route['name'] }}</span>
                                <i class="fas fa-chevron-down chevron"></i>
                            </div>
                            <div class="api-body">
                                @if($route['description'])
                                    <div class="description-box">
                                        {{ $route['description'] }}
                                    </div>
                                @endif

                                <div class="info-grid">
                                    <div class="info-item">
                                        <h4>Controller Action</h4>
                                        <code class="action-code">{{ $route['action'] }}</code>
                                    </div>
                                    
                                    <div class="info-item">
                                        <h4>Middleware</h4>
                                        @foreach($route['middleware'] as $mw)
                                            <span class="middleware-tag">{{ $mw }}</span>
                                        @endforeach
                                    </div>

                                    @if(!empty($route['parameters']))
                                        <div class="info-item">
                                            <h4>Path Parameters</h4>
                                            @foreach($route['parameters'] as $param)
                                                <span class="middleware-tag"><i class="fas fa-link"></i> {{ $param }}</span>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if(!empty($route['validation_rules']))
                                        <div class="info-item" style="grid-column: span 2;">
                                            <h4>Expected Input (Validation Rules)</h4>
                                            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 0.5rem; background: rgba(0,0,0,0.2); padding: 0.8rem; border-radius: 6px;">
                                                @foreach($route['validation_rules'] as $field => $rules)
                                                    <span style="color: var(--accent-primary); font-family: monospace;">{{ $field }}</span>
                                                    <span style="color: var(--text-dim); font-size: 0.8rem;">{{ $rules }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @if($route['example_request'])
                                    <div style="margin-top: 1.5rem;">
                                        <h4 style="color: #60a5fa;"><i class="fas fa-sign-in-alt"></i> Example Request Body (JSON)</h4>
                                        <pre style="background: #000; padding: 1rem; border-radius: 6px; margin-top: 0.5rem; color: #a5b4fc; overflow-x: auto; font-size: 0.85rem;"><code>{{ $route['example_request'] }}</code></pre>
                                    </div>

                                    <div style="margin-top: 1.5rem;">
                                        <h4>CURL Example</h4>
                                        <pre style="background: #000; padding: 1rem; border-radius: 6px; margin-top: 0.5rem; color: #f97316; overflow-x: auto; font-size: 0.85rem; border-left: 3px solid #f97316;"><code>curl -X {{ $route['methods'][0] }} {{ $route['full_url'] }} \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{!! str_replace("\n", " ", $route['example_request']) !!}'</code></pre>
                                    </div>
                                @endif

                                @if($route['example_response'])
                                    <div style="margin-top: 1.5rem;">
                                        <h4 style="color: #34d399;"><i class="fas fa-sign-out-alt"></i> Example Success Response (JSON)</h4>
                                        <pre style="background: #000; padding: 1rem; border-radius: 6px; margin-top: 0.5rem; color: #6ee7b7; overflow-x: auto; font-size: 0.85rem; border-left: 3px solid #34d399;"><code>{{ $route['example_response'] }}</code></pre>
                                    </div>
                                @endif
                                
                                <div style="margin-top: 1.5rem;">
                                    <h4>Full Endpoint URL</h4>
                                    <code style="display: block; background: #000; padding: 0.8rem; border-radius: 4px; margin-top: 0.5rem; color: #10b981;">
                                        {{ $route['full_url'] }}
                                    </code>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </section>
            @endforeach
        </main>
    </div>

    <script>
        // Simple search functionality could go here if needed
    </script>
</body>
</html>

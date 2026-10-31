@extends('app')
@section('title', __('profile.editprofile'))

@section('content')
@php
$breadcrumbs = [
    [
        'title' => 'Pilot Center',
        'url' => Auth::check() ? route('frontend.dashboard.index') : route('frontend.home'),
        'icon' => Auth::check() ? 'bi bi-speedometer2' : 'bi bi-house-fill'
    ],
    [
        'title' => __('common.profile'),
        'url' => route('frontend.profile.index'),
        'icon' => 'bi bi-person-circle'
    ],
    [
        'title' => __('profile.editprofile'),
        'icon' => 'bi bi-person-gear'
    ]
];
@endphp
@include('layouts.seven.components.breadcrumb', ['breadcrumbs' => $breadcrumbs])

<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
:root {
    --em: #10b981;
    --em2: rgba(16,185,129,.12);
    --sl: #020617;
    --sl2: #1e293b;
    --sl3: #0f172a;
    --amb: #fbbf24;
    --blu: #3b82f6;
    --tx: #e2e8f0;
    --tx2: #94a3b8;
}

body, .pe-wrap * {
    font-family: 'JetBrains Mono', monospace !important;
    font-size: 16px;
}

.pe-wrap {
    max-width: 1020px;
    margin: 0 auto;
    padding: 1.5rem 1rem 3rem;
}

/* ── Page header ── */
.pe-header {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    margin-bottom: 2rem;
    padding: 1.5rem 1.75rem;
    background: rgba(15,23,42,.85);
    border: 1px solid var(--em2);
    border-radius: 14px;
    backdrop-filter: blur(10px);
}

.pe-header-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background: rgba(16,185,129,.15);
    border: 1px solid var(--em2);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: var(--em);
    font-size: 1.25rem;
}

.pe-header-title {
    font-size: 1.35rem;
    font-weight: 700;
    color: var(--tx);
    margin: 0 0 .2rem;
    letter-spacing: -.5px;
}

.pe-header-sub {
    font-size: .78rem;
    color: var(--tx2);
    margin: 0;
    text-transform: uppercase;
    letter-spacing: .5px;
}

/* ── Card ── */
.pe-card {
    background: rgba(15,23,42,.85);
    border: 1px solid var(--em2);
    border-radius: 14px;
    backdrop-filter: blur(10px);
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.pe-card-header {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--em2);
}

.pe-card-header-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: rgba(16,185,129,.1);
    border: 1px solid var(--em2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--em);
    font-size: .85rem;
    flex-shrink: 0;
}

.pe-card-title {
    font-size: .82rem;
    font-weight: 600;
    color: var(--tx);
    text-transform: uppercase;
    letter-spacing: .6px;
    margin: 0;
}

.pe-card-body {
    padding: 1.5rem;
}

/* ── Form controls (used by fields.blade.php partial) ── */
.form-group {
    margin-bottom: 1.25rem;
}

.form-label {
    display: block;
    font-size: .72rem;
    font-weight: 600;
    color: var(--tx2);
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: .45rem;
}

.form-label i { color: var(--em); margin-right: .4rem; }

.form-control,
.form-select {
    width: 100%;
    background: rgba(15,23,42,.6) !important;
    border: 1px solid rgba(16,185,129,.2) !important;
    border-radius: 8px !important;
    color: var(--tx) !important;
    font-family: 'JetBrains Mono', monospace !important;
    font-size: .88rem !important;
    padding: .65rem .9rem !important;
    transition: border-color .2s, box-shadow .2s;
    box-shadow: none !important;
}

.form-control::placeholder { color: var(--tx2) !important; opacity: .7; }

.form-control:focus,
.form-select:focus {
    border-color: var(--em) !important;
    box-shadow: 0 0 0 3px rgba(16,185,129,.15) !important;
    outline: none !important;
    background: rgba(15,23,42,.9) !important;
}

.form-select option {
    background: #1e293b;
    color: #e2e8f0;
}

.form-control.is-invalid,
.form-select.is-invalid {
    border-color: #ef4444 !important;
}

.invalid-feedback {
    color: #f87171;
    font-size: .78rem;
    margin-top: .3rem;
}

.form-text { margin-top: .4rem; }

.enhanced-help-text {
    background: rgba(16,185,129,.07);
    border: 1px solid rgba(16,185,129,.18);
    border-radius: 6px;
    padding: .5rem .75rem;
    color: #6ee7b7 !important;
    font-size: .78rem;
}

.enhanced-help-text strong { color: #6ee7b7 !important; }
.enhanced-help-text .fas, .enhanced-help-text .text-primary { color: var(--em) !important; }
.text-muted { color: var(--tx2) !important; }
.text-success { color: var(--em) !important; }
.text-danger { color: #f87171 !important; }
.text-warning { color: var(--amb) !important; }

/* Section sub-headers inside fields partial */
h6.text-primary {
    color: var(--em) !important;
    font-size: .78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    margin-top: 1.5rem;
    margin-bottom: .75rem;
}

/* ── Avatar ── */
.current-avatar-preview {
    padding: .75rem;
    background: rgba(15,23,42,.5);
    border: 1px solid var(--em2);
    border-radius: 8px;
    margin-bottom: .75rem;
}

.avatar-preview-container {
    display: flex;
    align-items: center;
    gap: .75rem;
}

.current-avatar-img {
    border: 2px solid var(--em) !important;
    border-radius: 8px !important;
    box-shadow: 0 0 10px rgba(16,185,129,.2);
}

.avatar-placeholder {
    background: rgba(15,23,42,.6) !important;
    border: 1px dashed rgba(148,163,184,.3) !important;
    border-radius: 8px !important;
    color: var(--tx2);
}

/* ── Action buttons grid ── */
.pe-actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
    gap: .875rem;
}

.pe-action-btn {
    display: flex;
    align-items: center;
    gap: .6rem;
    padding: .75rem 1rem;
    border-radius: 9px;
    text-decoration: none !important;
    font-size: .82rem;
    font-weight: 600;
    transition: transform .15s, box-shadow .15s, opacity .15s;
    border: none;
    cursor: pointer;
    font-family: 'JetBrains Mono', monospace !important;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.pe-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(0,0,0,.35);
    opacity: .92;
    text-decoration: none !important;
}

.pe-action-btn.discord  { background: #5865f2; color: #fff; }
.pe-action-btn.ivao     { background: #0d2c99; color: #fff; }
.pe-action-btn.vatsim   { background: #29b473; color: #fff; }
.pe-action-btn.acars    { background: var(--blu); color: #fff; }
.pe-action-btn.apikey   { background: rgba(251,191,36,.15); color: var(--amb); border: 1px solid rgba(251,191,36,.25); }
.pe-action-btn.apikey:hover { background: rgba(251,191,36,.22); }

/* ── Info table ── */
.pe-info-table {
    width: 100%;
    border-collapse: collapse;
}

.pe-info-table tr { border-bottom: 1px solid var(--em2); }
.pe-info-table tr:last-child { border-bottom: none; }

.pe-info-table th {
    padding: .9rem 1rem;
    font-size: .72rem;
    font-weight: 600;
    color: var(--tx2);
    text-transform: uppercase;
    letter-spacing: .5px;
    width: 38%;
    vertical-align: middle;
    background: transparent;
    border: none;
}

.pe-info-table th i { color: var(--em); margin-right: .4rem; }

.pe-info-table td {
    padding: .9rem 1rem;
    color: var(--tx);
    font-size: .88rem;
    font-weight: 500;
    vertical-align: middle;
    border: none;
}

/* badges */
.pe-badge {
    display: inline-block;
    padding: .28rem .65rem;
    border-radius: 6px;
    font-size: .78rem;
    font-weight: 600;
    font-family: 'JetBrains Mono', monospace !important;
}

.pe-badge-em      { background: rgba(16,185,129,.15); color: var(--em); border: 1px solid var(--em2); }
.pe-badge-blu     { background: rgba(59,130,246,.15); color: var(--blu); border: 1px solid rgba(59,130,246,.2); }
.pe-badge-amb     { background: rgba(251,191,36,.12); color: var(--amb); border: 1px solid rgba(251,191,36,.2); }
.pe-badge-discord { background: rgba(88,101,242,.2); color: #a5b4fc; border: 1px solid rgba(88,101,242,.3); }

/* ── API key block ── */
.pe-apikey-box {
    background: rgba(15,23,42,.6);
    border: 1px solid rgba(16,185,129,.2);
    border-radius: 8px;
    padding: .75rem 1rem;
}

.pe-apikey-val {
    font-family: 'JetBrains Mono', monospace !important;
    font-size: .78rem;
    color: var(--em);
    word-break: break-all;
    letter-spacing: .5px;
    display: block;
    margin-bottom: .6rem;
}

.pe-apikey-hidden {
    background: rgba(15,23,42,.4);
    border: 1px dashed rgba(148,163,184,.2);
    border-radius: 8px;
    padding: .75rem 1rem;
    text-align: center;
}

/* ── Action bar ── */
.pe-action-bar {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: .75rem;
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--em2);
}

.pc-btn {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .6rem 1.25rem;
    border-radius: 8px;
    font-family: 'JetBrains Mono', monospace !important;
    font-size: .82rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .4px;
    cursor: pointer;
    transition: all .15s;
    text-decoration: none !important;
    border: none;
}

.pc-btn-cancel {
    background: transparent;
    border: 1px solid rgba(148,163,184,.25);
    color: var(--tx2);
}
.pc-btn-cancel:hover { border-color: rgba(148,163,184,.45); color: var(--tx); }

.pc-btn-save {
    background: var(--em);
    color: #020617;
}
.pc-btn-save:hover { background: #0ea571; box-shadow: 0 4px 14px rgba(16,185,129,.35); }

/* ── Small util buttons (copy/select/hide) ── */
.btn-sm.btn-outline-primary,
.btn-sm.btn-outline-info,
.btn-sm.btn-outline-secondary,
.btn-sm.btn-outline-success,
.btn-sm.btn-success,
.btn-sm.btn-info {
    font-family: 'JetBrains Mono', monospace !important;
    font-size: .72rem !important;
    padding: .25rem .55rem !important;
    border-radius: 5px !important;
}

.btn-sm.btn-outline-primary  { border-color: var(--blu); color: var(--blu); background: transparent; }
.btn-sm.btn-outline-info     { border-color: var(--tx2); color: var(--tx2); background: transparent; }
.btn-sm.btn-outline-secondary{ border-color: rgba(148,163,184,.3); color: var(--tx2); background: transparent; }
.btn-sm.btn-outline-success  { border-color: var(--em); color: var(--em); background: transparent; }
.btn-sm.btn-success          { background: var(--em); color: #020617; border: none; }
.btn-sm.btn-info             { background: var(--blu); color: #fff; border: none; }

@media (max-width: 768px) {
    .pe-actions-grid { grid-template-columns: 1fr; }
    .pe-action-bar { flex-direction: column-reverse; }
    .pc-btn { width: 100%; justify-content: center; }
    .pe-info-table th { width: auto; }
}
</style>

<div class="pe-wrap">

    {{-- Header --}}
    <div class="pe-header">
        <div class="pe-header-icon">
            <i class="fas fa-user-edit"></i>
        </div>
        <div>
            <p class="pe-header-title">@lang('profile.editprofile')</p>
            <p class="pe-header-sub">{{ $user->pilot_id ?? $user->name }} &mdash; @lang('profile.edityourprofile')</p>
        </div>
    </div>

    @include('flash::message')

    {{-- Personal Information --}}
    <form method="post" action="{{ route('frontend.profile.update', $user->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="pe-card">
            <div class="pe-card-header">
                <div class="pe-card-header-icon"><i class="fas fa-user"></i></div>
                <span class="pe-card-title">Personal Information</span>
            </div>
            <div class="pe-card-body">
                @include('profile.fields')
            </div>
            <div class="pe-action-bar">
                <a href="{{ route('frontend.profile.index') }}" class="pc-btn pc-btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="pc-btn pc-btn-save">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </div>
    </form>

    {{-- Account Actions --}}
    <div class="pe-card">
        <div class="pe-card-header">
            <div class="pe-card-header-icon"><i class="fas fa-tools"></i></div>
            <span class="pe-card-title">Account Actions</span>
        </div>
        <div class="pe-card-body">
            <div class="pe-actions-grid">
                @if (isset($acars) && $acars === true)
                    <a href="{{ route('frontend.profile.acars') }}" class="pe-action-btn acars"
                        onclick="alert('Copy or Save to \'My Documents/vmsacars/profiles\'')">
                        <i class="fas fa-download"></i> Download ACARS Config
                    </a>
                @endif

                @if (config('services.discord.enabled') && !$user->discord_id)
                    <a href="{{ route('oauth.redirect', ['provider' => 'discord']) }}" class="pe-action-btn discord">
                        <i class="fab fa-discord"></i> Link Discord
                    </a>
                @elseif(config('services.discord.enabled'))
                    <a href="{{ route('oauth.logout', ['provider' => 'discord']) }}" class="pe-action-btn discord">
                        <i class="fab fa-discord"></i> Unlink Discord
                    </a>
                @endif

                @if (config('services.ivao.enabled') && !$user->ivao_id)
                    <a href="{{ route('oauth.redirect', ['provider' => 'ivao']) }}" class="pe-action-btn ivao">
                        <i class="fas fa-plane"></i> Link IVAO
                    </a>
                @elseif(config('services.ivao.enabled'))
                    <a href="{{ route('oauth.logout', ['provider' => 'ivao']) }}" class="pe-action-btn ivao">
                        <i class="fas fa-plane"></i> Unlink IVAO
                    </a>
                @endif

                @if (config('services.vatsim.enabled') && !$user->vatsim_id)
                    <a href="{{ route('oauth.redirect', ['provider' => 'vatsim']) }}" class="pe-action-btn vatsim">
                        <i class="fas fa-globe"></i> Link VATSIM
                    </a>
                @elseif(config('services.vatsim.enabled'))
                    <a href="{{ route('oauth.logout', ['provider' => 'vatsim']) }}" class="pe-action-btn vatsim">
                        <i class="fas fa-globe"></i> Unlink VATSIM
                    </a>
                @endif

                <a href="{{ route('frontend.profile.regen_apikey') }}" class="pe-action-btn apikey"
                    onclick="return confirm('Are you sure? This will reset your API key!')">
                    <i class="fas fa-key"></i> @lang('profile.newapikey')
                </a>
            </div>
        </div>
    </div>

    {{-- Account Information --}}
    <div class="pe-card">
        <div class="pe-card-header">
            <div class="pe-card-header-icon"><i class="fas fa-id-card"></i></div>
            <span class="pe-card-title">Account Information</span>
        </div>
        <div class="pe-card-body" style="padding: 0;">
            <table class="pe-info-table">
                <tbody>
                    <tr>
                        <th>
                            <i class="fas fa-key"></i>@lang('profile.apikey')
                            <br><small style="color: var(--tx2); font-size:.68rem;">(@lang('profile.dontshare'))</small>
                        </th>
                        <td>
                            <span id="apiKey_show" style="display:none;">
                                <div class="pe-apikey-box">
                                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:.5rem;">
                                        <span style="font-size:.72rem; color:var(--tx2); text-transform:uppercase; letter-spacing:.4px;">Your API Key</span>
                                        <div style="display:flex; gap:.4rem;">
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyApiKey()">
                                                <i class="fas fa-copy me-1"></i>Copy
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-info" onclick="selectApiKey()">
                                                <i class="fas fa-mouse-pointer me-1"></i>Select
                                            </button>
                                        </div>
                                    </div>
                                    <code id="apiKeyText" class="pe-apikey-val">{{ $user->api_key }}</code>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="apiKeyHide()">
                                        <i class="fas fa-eye-slash me-1"></i>Hide API Key
                                    </button>
                                </div>
                            </span>
                            <span id="apiKey_hide">
                                <div class="pe-apikey-hidden">
                                    <span style="color:var(--tx2); font-size:.82rem;">@lang('profile.apikey-show')</span>
                                    <br>
                                    <button type="button" class="btn btn-sm btn-outline-success mt-2" onclick="apiKeyShow()">
                                        <i class="fas fa-eye me-1"></i>Show API Key
                                    </button>
                                </div>
                            </span>
                        </td>
                    </tr>

                    @if($user->discord_id)
                    <tr>
                        <th><i class="fab fa-discord"></i>Discord ID</th>
                        <td><span class="pe-badge pe-badge-discord">{{ $user->discord_id }}</span></td>
                    </tr>
                    @endif

                    @if($user->ivao_id)
                    <tr>
                        <th><i class="fas fa-plane"></i>IVAO ID</th>
                        <td><span class="pe-badge pe-badge-blu">{{ $user->ivao_id }}</span></td>
                    </tr>
                    @endif

                    @if($user->vatsim_id)
                    <tr>
                        <th><i class="fas fa-globe"></i>VATSIM ID</th>
                        <td><span class="pe-badge pe-badge-amb">{{ $user->vatsim_id }}</span></td>
                    </tr>
                    @endif

                    <tr>
                        <th><i class="fas fa-calendar"></i>@lang('common.created')</th>
                        <td><span class="pe-badge pe-badge-em">{{ $user->created_at->format('M d, Y') }}</span></td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-clock"></i>@lang('common.updated')</th>
                        <td><span class="pe-badge pe-badge-blu">{{ $user->updated_at->format('M d, Y H:i') }}</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('scripts')
    @include('scripts.airport_search')

    <script>
        document.querySelectorAll('.form-select').forEach(function(select) {
            select.style.background = 'rgba(15,23,42,.6)';
            select.style.border = '1px solid rgba(16,185,129,.2)';
            select.style.borderRadius = '8px';
            select.style.padding = '.65rem .9rem';
            select.style.fontSize = '.88rem';
            select.style.color = '#e2e8f0';
            select.style.fontFamily = "'JetBrains Mono', monospace";
            select.style.boxShadow = 'none';

            select.addEventListener('focus', function() {
                this.style.borderColor = '#10b981';
                this.style.boxShadow = '0 0 0 3px rgba(16,185,129,.15)';
                this.style.background = 'rgba(15,23,42,.9)';
            });

            select.addEventListener('blur', function() {
                this.style.borderColor = 'rgba(16,185,129,.2)';
                this.style.boxShadow = 'none';
                this.style.background = 'rgba(15,23,42,.6)';
            });
        });

        function apiKeyShow() {
            document.getElementById("apiKey_show").style.display = "block";
            document.getElementById("apiKey_hide").style.display = "none";
        }

        function apiKeyHide() {
            document.getElementById("apiKey_show").style.display = "none";
            document.getElementById("apiKey_hide").style.display = "block";
        }

        function copyApiKey() {
            const apiKeyText = document.getElementById("apiKeyText").textContent;
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(apiKeyText).then(showCopySuccess).catch(function() { fallbackCopyMethod(apiKeyText); });
            } else {
                fallbackCopyMethod(apiKeyText);
            }
        }

        function fallbackCopyMethod(text) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.left = '-999999px';
            document.body.appendChild(textarea);
            try {
                textarea.focus();
                textarea.select();
                document.execCommand('copy') ? showCopySuccess() : showCopyError();
            } catch (err) {
                showCopyError();
            } finally {
                document.body.removeChild(textarea);
            }
        }

        function selectApiKey() {
            const codeElement = document.getElementById("apiKeyText");
            if (window.getSelection && document.createRange) {
                const selection = window.getSelection();
                const range = document.createRange();
                range.selectNodeContents(codeElement);
                selection.removeAllRanges();
                selection.addRange(range);
                const btn = event.target.closest('button');
                const orig = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check me-1"></i>Selected!';
                btn.classList.replace('btn-outline-info', 'btn-info');
                setTimeout(function() { btn.innerHTML = orig; btn.classList.replace('btn-info', 'btn-outline-info'); }, 2000);
            }
        }

        function showCopySuccess() {
            const btn = event.target.closest('button');
            const orig = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check me-1"></i>Copied!';
            btn.classList.replace('btn-outline-primary', 'btn-success');
            setTimeout(function() { btn.innerHTML = orig; btn.classList.replace('btn-success', 'btn-outline-primary'); }, 2000);
        }

        function showCopyError() {
            const apiKeyText = document.getElementById("apiKeyText").textContent;
            const popup = document.createElement('div');
            popup.innerHTML = `
                <div style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:#1e293b;border:1px solid rgba(16,185,129,.2);padding:2rem;border-radius:12px;box-shadow:0 20px 60px rgba(0,0,0,.6);z-index:1000;max-width:90%;width:500px;font-family:'JetBrains Mono',monospace;">
                    <h5 style="color:#10b981;margin-bottom:1rem;font-size:.9rem;text-transform:uppercase;letter-spacing:.5px;"><i class="fas fa-copy me-2"></i>Copy API Key Manually</h5>
                    <p style="margin-bottom:1rem;color:#94a3b8;font-size:.82rem;">Select and copy the key below:</p>
                    <div style="background:rgba(15,23,42,.8);padding:1rem;border-radius:8px;border:1px solid rgba(16,185,129,.2);margin-bottom:1rem;">
                        <code style="font-size:.78rem;word-break:break-all;user-select:all;color:#10b981;">${apiKeyText}</code>
                    </div>
                    <div style="text-align:right;">
                        <button onclick="document.body.removeChild(this.closest('div').parentElement)"
                                style="background:rgba(148,163,184,.15);color:#94a3b8;border:1px solid rgba(148,163,184,.2);padding:.4rem .9rem;border-radius:6px;cursor:pointer;font-family:'JetBrains Mono',monospace;font-size:.78rem;">
                            <i class="fas fa-times me-1"></i>Close
                        </button>
                    </div>
                </div>
                <div style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.6);z-index:999;"
                     onclick="document.body.removeChild(this.parentElement)"></div>
            `;
            document.body.appendChild(popup);
            setTimeout(() => {
                const code = popup.querySelector('code');
                if (window.getSelection && document.createRange) {
                    const sel = window.getSelection();
                    const range = document.createRange();
                    range.selectNodeContents(code);
                    sel.removeAllRanges();
                    sel.addRange(range);
                }
            }, 100);
        }
    </script>
@endsection

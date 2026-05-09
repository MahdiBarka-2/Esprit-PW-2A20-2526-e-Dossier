<?php
require_once '../../CONTROLLER/EvenementCONTROLLER.php';
require_once '../../CONTROLLER/LanguageCONTROLLER.php';
require_once '../../CONTROLLER/ParticipantCONTROLLER.php';

$CONTROLLER    = new EvenementC();
$participantC  = new ParticipantC();
$active_events = $CONTROLLER->findActive();
$tab           = $_GET['tab'] ?? 'all';

$user_id = $_SESSION['user_id'] ?? null;
$joined  = [];

if ($user_id) {
    $my_parts = $participantC->findByUser($user_id);
    foreach ($my_parts as $p) {
        $joined[$p['event_id']] = $p['user_id'];
    }
}

// ── Real participant counts ───────────────────────────────────────────────
$counts_map = $participantC->getAllCounts();

// Build JS-safe event array for map tab
$map_events_js = json_encode(array_map(function ($e) use ($counts_map) {
    return [
        'id'      => (int) $e['id'],
        'titre'   => $e['titre'],
        'lieu'    => $e['lieu'] ?? '',
        'date'    => $e['date_debut'] . ($e['date_fin'] ? ' → ' . $e['date_fin'] : ''),
        'is_paid' => !empty($e['is_paid']),
        'prix'    => (float) ($e['prix'] ?? 0),
        'cnt'     => $counts_map[$e['id']] ?? 0,
        'cap'     => (int) ($e['capacite_max'] ?? 0),
    ];
}, $active_events));
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" <?php echo ($lang === 'ar' ? 'dir="rtl"' : ''); ?>>

<head>
    <title>e_dossier - Events</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicon -->
    <link rel="shortcut icon" href="../../assets/images/favicon.ico">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Poppins:wght@400;500;700&family=Montserrat:wght@400;500;600;700&display=swap">

    <!-- CSS Links -->
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../../assets/vendor/font-awesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css">

    <!-- Page Specific CSS -->
    <link rel="stylesheet" href="assets/vendor/tiny-slider/tiny-slider.css">
    <link rel="stylesheet" href="assets/vendor/glightbox/css/glightbox.css">
    <link rel="stylesheet" href="assets/vendor/flatpickr/css/flatpickr.min.css">
    <link rel="stylesheet" href="assets/vendor/choices/css/choices.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />

    <!-- Dark mode script (must run before body renders) -->
    <script>
        const storedTheme = localStorage.getItem('theme');
        const getPreferredTheme = () => {
            if (storedTheme) return storedTheme;
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }
        const setTheme = function (theme) {
            if (theme === 'auto') {
                document.documentElement.setAttribute('data-bs-theme', window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            } else {
                document.documentElement.setAttribute('data-bs-theme', theme);
            }
        }
        setTheme(getPreferredTheme());
        window.addEventListener('DOMContentLoaded', () => {
            const showActiveTheme = theme => {
                const activeThemeBtn = document.querySelector(`[data-bs-theme-value="${theme}"]`);
                document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
                    element.classList.remove('active');
                });
                if (activeThemeBtn) activeThemeBtn.classList.add('active');
            }
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                if (storedTheme !== 'light' && storedTheme !== 'dark') setTheme(getPreferredTheme());
            });
            showActiveTheme(getPreferredTheme());
            document.querySelectorAll('[data-bs-theme-value]').forEach(toggle => {
                toggle.addEventListener('click', () => {
                    const theme = toggle.getAttribute('data-bs-theme-value');
                    localStorage.setItem('theme', theme);
                    setTheme(theme);
                    showActiveTheme(theme);
                });
            });
        });
    </script>

    <style>
        /* ═══════════════════════════════════════════════════════════════════
           MAIN CONTENT & COMPONENTS
        ═══════════════════════════════════════════════════════════════════ */

        /* Main content area */
        [data-bs-theme='dark'] #view-front,
        [data-bs-theme='dark'] .content { background-color: var(--bs-body-bg) !important; }

        /* Event cards in dark mode */
        [data-bs-theme='dark'] .event-card {
            background-color: var(--bs-tertiary-bg) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        [data-bs-theme='dark'] .event-card h3,
        [data-bs-theme='dark'] .event-card p,
        [data-bs-theme='dark'] .event-card .event-meta,
        [data-bs-theme='dark'] .event-card .event-desc,
        [data-bs-theme='dark'] .event-card .cap-badge { color: #f5f5dc !important; }

        /* Tabs in dark mode */
        [data-bs-theme='dark'] .tabs { border-color: rgba(255, 255, 255, 0.1); }
        [data-bs-theme='dark'] .tab { color: #f5f5dc; }
        [data-bs-theme='dark'] .tab:hover { color: #6C5CE7; }
        [data-bs-theme='dark'] .tab.active { color: #6C5CE7; border-color: #6C5CE7; }

        /* Section head in dark mode */
        [data-bs-theme='dark'] .section-head { border-color: rgba(255, 255, 255, 0.08); }
        [data-bs-theme='dark'] .section-title { color: #f5f5dc !important; }
        [data-bs-theme='dark'] .badge-count {
            background: rgba(108, 92, 231, 0.2);
            color: #a29bfe;
        }

        /* Empty state in dark mode */
        [data-bs-theme='dark'] .empty { color: #f5f5dc !important; }
        [data-bs-theme='dark'] .empty svg { stroke: #f5f5dc; }

        /* Map legend in dark mode */
        [data-bs-theme='dark'] .map-legend { color: #f5f5dc; }

        /* Modal in dark mode */
        [data-bs-theme='dark'] .event-modal {
            background: var(--bs-body-bg) !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        [data-bs-theme='dark'] .modal-header-band {
            border-color: rgba(108, 92, 231, 0.2);
        }
        [data-bs-theme='dark'] .modal-title-group h4 { color: #f5f5dc !important; }
        [data-bs-theme='dark'] .modal-confirm-text   { color: #f5f5dc !important; }
        [data-bs-theme='dark'] .modal-event-info {
            background: rgba(108, 92, 231, 0.1);
            border-color: rgba(108, 92, 231, 0.2);
        }
        [data-bs-theme='dark'] .modal-event-info .event-detail { color: #f5f5dc !important; }
        [data-bs-theme='dark'] .modal-btn.cancel {
            background: rgba(255, 255, 255, 0.08);
            color: #f5f5dc;
        }
        [data-bs-theme='dark'] .pay-method-btn {
            border-color: rgba(108, 92, 231, 0.25);
            background: var(--bs-body-bg);
            color: #f5f5dc;
        }
        [data-bs-theme='dark'] .pay-method-btn.selected {
            background: rgba(108, 92, 231, 0.15);
            color: #a29bfe;
        }
        [data-bs-theme='dark'] .modal-input {
            border-color: rgba(108, 92, 231, 0.3);
            background: rgba(255, 255, 255, 0.05);
            color: #f5f5dc;
        }
        [data-bs-theme='dark'] .modal-input:focus {
            border-color: #6C5CE7;
            background: rgba(108, 92, 231, 0.08);
        }
        [data-bs-theme='dark'] .modal-steps {
            background: rgba(108, 92, 231, 0.05);
            border-color: rgba(108, 92, 231, 0.15);
        }

        /* Map inline modal in dark mode */
        [data-bs-theme='dark'] #map-join-modal {
            background: var(--bs-body-bg);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        [data-bs-theme='dark'] .mjm-input {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(108, 92, 231, 0.3);
            color: #f5f5dc;
        }
        [data-bs-theme='dark'] .mjm-btn.mjm-cancel {
            background: rgba(255, 255, 255, 0.08);
            color: #f5f5dc;
        }
        [data-bs-theme='dark'] .mjm-pay-method {
            border-color: rgba(108, 92, 231, 0.25);
            background: var(--bs-body-bg);
            color: #f5f5dc;
        }
        [data-bs-theme='dark'] .mjm-pay-method.selected {
            background: rgba(108, 92, 231, 0.15);
            color: #a29bfe;
        }

        /* Paid / Free badges */
        .badge-paid {
            display: inline-flex; align-items: center; gap: .25rem;
            background: rgba(234, 179, 8, 0.12); color: #b45309;
            border: 1px solid rgba(234, 179, 8, 0.3);
            font-size: .72rem; font-weight: 600; padding: .2rem .55rem;
            border-radius: 20px; white-space: nowrap; flex-shrink: 0;
        }
        .badge-free {
            display: inline-flex; align-items: center; gap: .25rem;
            background: rgba(16, 185, 129, 0.10); color: #047857;
            border: 1px solid rgba(16, 185, 129, 0.2);
            font-size: .72rem; font-weight: 600; padding: .2rem .55rem;
            border-radius: 20px; white-space: nowrap; flex-shrink: 0;
        }

        /* ═══════════════════════════════════════════════════════════════════
           MODAL OVERLAY
        ═══════════════════════════════════════════════════════════════════ */
        .event-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 9998;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .event-modal-overlay.open {
            display: flex;
            animation: overlayFadeIn 0.22s ease forwards;
        }
        @keyframes overlayFadeIn { from { opacity: 0; } to { opacity: 1; } }

        .event-modal {
            background: var(--bs-body-bg, #fff);
            border-radius: 18px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.22), 0 2px 8px rgba(0,0,0,0.08);
            max-width: 440px;
            width: 100%;
            padding: 0;
            overflow: hidden;
            transform: translateY(30px) scale(0.97);
            opacity: 0;
            animation: modalSlideIn 0.28s cubic-bezier(0.34,1.56,0.64,1) 0.05s forwards;
            position: relative;
        }
        @keyframes modalSlideIn { to { transform: translateY(0) scale(1); opacity: 1; } }

        .modal-header-band {
            padding: 1.5rem 1.75rem 1.25rem;
            border-bottom: 1px solid rgba(108,92,231,0.12);
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }
        .modal-icon-wrap {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .modal-icon-wrap.join  { background: rgba(16,185,129,0.12); }
        .modal-icon-wrap.leave { background: rgba(220,38,38,0.10); }
        .modal-icon-wrap.join  svg { stroke: #10b981; }
        .modal-icon-wrap.leave svg { stroke: #dc2626; }

        .modal-title-group { flex: 1; }
        .modal-title-group h4 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.1rem; font-weight: 600;
            margin: 0 0 .2rem;
            color: var(--bs-body-color);
            line-height: 1.3;
        }
        .modal-title-group .modal-subtitle {
            font-size: .8rem;
            color: var(--bs-secondary-color, #6c757d);
            margin: 0;
        }
        .modal-close-btn {
            position: absolute; top: 1rem; right: 1rem;
            background: none; border: none; cursor: pointer;
            color: var(--bs-secondary-color, #888);
            padding: 4px; border-radius: 6px; line-height: 1;
            transition: background .15s, color .15s;
        }
        .modal-close-btn:hover { background: rgba(0,0,0,0.07); color: var(--bs-body-color); }

        .modal-body-section { padding: 1.25rem 1.75rem; }

        .modal-event-info {
            background: rgba(108,92,231,0.06);
            border: 1px solid rgba(108,92,231,0.13);
            border-radius: 12px; padding: .9rem 1.1rem; margin-bottom: 1.1rem;
        }
        .modal-event-info .event-name {
            font-family: 'Poppins', sans-serif;
            font-weight: 600; font-size: .95rem;
            color: #6C5CE7; margin: 0 0 .35rem;
        }
        .modal-event-info .event-detail {
            display: flex; align-items: center; gap: .4rem;
            font-size: .8rem; color: var(--bs-secondary-color, #6c757d); margin-bottom: .2rem;
        }
        .modal-event-info .event-detail svg { width: 13px; height: 13px; flex-shrink: 0; }

        .modal-confirm-text {
            font-size: .88rem; color: var(--bs-secondary-color, #666);
            margin: 0; line-height: 1.6;
        }

        .modal-footer-section {
            padding: 1rem 1.75rem 1.5rem;
            display: flex; gap: .75rem; justify-content: flex-end;
        }
        .modal-btn {
            font-family: 'Poppins', sans-serif;
            font-size: .85rem; font-weight: 500;
            padding: .55rem 1.4rem; border-radius: 10px;
            border: none; cursor: pointer;
            transition: transform .12s, box-shadow .12s, opacity .12s;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: .4rem;
        }
        .modal-btn:hover { transform: translateY(-1px); opacity: .92; }
        .modal-btn:active { transform: translateY(0); }
        .modal-btn.cancel { background: var(--bs-tertiary-bg, #f1f1f1); color: var(--bs-body-color); }
        .modal-btn.confirm-join {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff; box-shadow: 0 4px 14px rgba(16,185,129,0.3);
        }
        .modal-btn.confirm-leave {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff; box-shadow: 0 4px 14px rgba(220,38,38,0.28);
        }

        /* Payment modal */
        .pay-step { display: none; }
        .pay-step.active { display: block; }

        .pay-summary-box {
            background: linear-gradient(135deg, #6C5CE7 0%, #a29bfe 100%);
            border-radius: 14px; padding: 1.25rem 1.4rem;
            color: #fff; margin-bottom: 1.1rem;
        }
        .pay-summary-box .pay-event-name {
            font-family: 'Poppins', sans-serif; font-weight: 700;
            font-size: 1rem; margin: 0 0 .3rem;
        }
        .pay-summary-box .pay-amount {
            font-size: 1.8rem; font-weight: 800; letter-spacing: -.02em; margin: 0;
        }
        .pay-summary-box .pay-amount span { font-size: .9rem; font-weight: 400; opacity: .8; }

        .pay-method-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: .6rem; margin-bottom: 1rem;
        }
        .pay-method-btn {
            border: 2px solid rgba(108,92,231,0.15); border-radius: 10px;
            padding: .65rem .5rem; text-align: center; cursor: pointer;
            transition: border-color .15s, background .15s;
            background: var(--bs-body-bg, #fff);
            font-size: .78rem; font-weight: 600; color: var(--bs-body-color);
        }
        .pay-method-btn:hover  { border-color: #6C5CE7; background: rgba(108,92,231,0.05); }
        .pay-method-btn.selected { border-color: #6C5CE7; background: rgba(108,92,231,0.08); color: #6C5CE7; }
        .pay-method-btn i { display: block; font-size: 1.3rem; margin-bottom: .3rem; }

        .pay-card-fields { display: none; }
        .pay-card-fields.show { display: block; }
        .card-input-row { display: flex; gap: .6rem; }
        .card-input-row .modal-field-group { flex: 1; }

        .card-preview {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            border-radius: 12px; padding: 1rem 1.2rem; margin-bottom: 1rem;
            color: #fff; font-family: 'DM Sans', monospace;
        }
        .card-preview .card-number-display {
            font-size: 1.1rem; letter-spacing: .18em; margin: .4rem 0 .6rem;
            color: rgba(255,255,255,.9);
        }
        .card-preview .card-meta { display: flex; justify-content: space-between; font-size: .72rem; color: rgba(255,255,255,.5); }
        .card-preview .card-meta strong { color: rgba(255,255,255,.85); font-size: .82rem; display: block; margin-top: .15rem; }

        .event-modal.pay-mode { max-width: 500px; }

        /* Progress steps */
        .modal-steps {
            display: flex; align-items: center; gap: 0;
            padding: .75rem 1.75rem; border-bottom: 1px solid rgba(108,92,231,0.1);
            background: rgba(108,92,231,0.03);
        }
        .modal-step-item {
            display: flex; align-items: center; gap: .4rem;
            font-size: .75rem; font-weight: 600; color: var(--bs-secondary-color, #888);
        }
        .modal-step-item.active { color: #6C5CE7; }
        .modal-step-item.done   { color: #10b981; }
        .modal-step-dot {
            width: 20px; height: 20px; border-radius: 50%; border: 2px solid currentColor;
            display: flex; align-items: center; justify-content: center; font-size: .65rem; flex-shrink: 0;
        }
        .modal-step-item.active .modal-step-dot { background: #6C5CE7; border-color: #6C5CE7; color: #fff; }
        .modal-step-item.done   .modal-step-dot { background: #10b981; border-color: #10b981; color: #fff; }
        .modal-step-sep { flex: 1; height: 1px; background: rgba(108,92,231,0.15); margin: 0 .5rem; }

        .modal-field-group { display: flex; flex-direction: column; gap: .3rem; margin-bottom: .95rem; }
        .modal-field-row { display: flex; gap: .75rem; }
        .modal-field-row .modal-field-group { flex: 1; }

        .modal-label {
            font-size: .78rem; font-weight: 600;
            color: var(--bs-secondary-color, #555);
            letter-spacing: .03em; text-transform: uppercase;
        }
        .modal-input {
            width: 100%; padding: .6rem .85rem;
            border: 1.5px solid rgba(108,92,231,0.2);
            border-radius: 9px; font-size: .9rem;
            font-family: 'DM Sans', sans-serif;
            background: var(--bs-body-bg, #fff);
            color: var(--bs-body-color);
            box-sizing: border-box;
            transition: border-color .15s, box-shadow .15s;
        }
        .modal-input:focus {
            outline: none; border-color: #6C5CE7;
            box-shadow: 0 0 0 3px rgba(108,92,231,0.12);
        }
        .modal-input::placeholder { color: #aaa; }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* ═══════════════════════════════════════════════════════════════════
           MISC LAYOUT
        ═══════════════════════════════════════════════════════════════════ */
        .btn-round {
            border-radius: 50% !important; width: 44px; height: 44px;
            display: flex; align-items: center; justify-content: center; padding: 0;
        }
        .icon-lg { width: 44px; height: 44px; }

        /* ═══════════════════════════════════════════════════════════════════
           MAP TAB
        ═══════════════════════════════════════════════════════════════════ */
        #leaflet-map {
            width: 100%; height: 520px;
            border-radius: 14px;
            border: 1px solid rgba(108,92,231,0.18);
            z-index: 1;
        }
        .map-wrapper { position: relative; }

        #map-join-modal {
            display: none;
            position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            background: var(--bs-body-bg, #fff);
            border-radius: 16px;
            box-shadow: 0 16px 56px rgba(0,0,0,0.28), 0 2px 8px rgba(0,0,0,0.1);
            width: 310px; overflow: hidden;
            animation: mjmIn .22s cubic-bezier(0.34,1.3,0.64,1) forwards;
        }
        #map-join-modal.open { display: block; }
        @keyframes mjmIn {
            from { opacity:0; transform: translate(-50%,-48%) scale(0.96); }
            to   { opacity:1; transform: translate(-50%,-50%) scale(1); }
        }

        .mjm-header { padding: 14px 16px 11px; color: #fff; }
        .mjm-header-free { background: linear-gradient(135deg, #10b981, #059669); }
        .mjm-header-paid { background: linear-gradient(135deg, #6C5CE7, #a29bfe); }
        .mjm-header h4 {
            margin: 0 0 2px; font-family: 'Poppins', sans-serif;
            font-size: 14px; font-weight: 700;
        }
        .mjm-header p { margin: 0; font-size: 11px; opacity: .85; }

        .mjm-close {
            position: absolute; top: 10px; right: 12px;
            background: rgba(255,255,255,0.2); border: none; border-radius: 50%;
            width: 22px; height: 22px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: #fff; font-size: 13px; line-height: 1;
        }
        .mjm-close:hover { background: rgba(255,255,255,0.35); }

        .mjm-body { padding: 12px 16px 6px; }
        .mjm-field { margin-bottom: 9px; }
        .mjm-label {
            font-size: 10px; font-weight: 700;
            color: var(--bs-secondary-color, #888);
            text-transform: uppercase; letter-spacing: .05em;
            display: block; margin-bottom: 3px;
        }
        .mjm-input {
            width: 100%; padding: 6px 10px;
            border: 1.5px solid rgba(108,92,231,0.2);
            border-radius: 8px; font-size: 13px;
            background: var(--bs-body-bg, #fff);
            color: var(--bs-body-color);
            box-sizing: border-box; transition: border-color .15s;
        }
        .mjm-input:focus { outline: none; border-color: #6C5CE7; box-shadow: 0 0 0 2px rgba(108,92,231,0.12); }

        .mjm-row { display: flex; gap: 8px; }
        .mjm-row .mjm-field { flex: 1; }

        .mjm-footer { padding: 8px 16px 14px; display: flex; gap: 8px; justify-content: flex-end; }
        .mjm-btn {
            font-size: 12px; font-weight: 700;
            padding: 7px 16px; border-radius: 9px;
            border: none; cursor: pointer;
            transition: opacity .12s, transform .1s;
        }
        .mjm-btn:hover { opacity: .88; transform: translateY(-1px); }
        .mjm-btn.mjm-cancel { background: var(--bs-tertiary-bg, #f1f1f1); color: var(--bs-body-color); }
        .mjm-btn.mjm-join-free { background: linear-gradient(135deg, #10b981, #059669); color: #fff; }
        .mjm-btn.mjm-join-paid { background: linear-gradient(135deg, #6C5CE7, #a29bfe); color: #fff; }

        .mjm-error { font-size: 11px; color: #dc2626; padding: 0 16px 6px; display: none; }

        .mjm-success-box { text-align: center; padding: 24px 16px; display: none; }
        .mjm-success-box .mjm-check {
            width: 48px; height: 48px; background: rgba(16,185,129,0.12);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 10px;
        }
        .mjm-success-box p { font-family:'Poppins',sans-serif; font-weight:700; font-size:14px; margin:0 0 4px; }
        .mjm-success-box small { font-size:11px; color:var(--bs-secondary-color,#888); }

        .map-legend {
            display: flex; gap: 16px; padding: 7px 4px;
            font-size: 12px; color: var(--bs-secondary-color, #777); margin-bottom: 6px;
        }
        .map-legend span { display: flex; align-items: center; gap: 5px; }
        .leg-dot { width: 11px; height: 11px; border-radius: 50%; display: inline-block; }

        /* Pay method mini-grid inside map modal */
        .mjm-pay-method {
            border: 2px solid rgba(108,92,231,0.15); border-radius: 8px;
            padding: 6px 4px; text-align: center; cursor: pointer;
            transition: border-color .15s, background .15s;
            font-size: 11px; font-weight: 600; color: var(--bs-body-color);
        }
        .mjm-pay-method:hover   { border-color: #6C5CE7; }
        .mjm-pay-method.selected { border-color: #6C5CE7; background: rgba(108,92,231,0.08); color: #6C5CE7; }
        .mjm-pay-method i { display: block; font-size: 1.1rem; margin-bottom: 2px; }

        /* Leaflet popup tweaks */
        .leaflet-popup-content-wrapper {
            border-radius: 12px !important;
            box-shadow: 0 8px 28px rgba(0,0,0,0.18) !important;
            padding: 0 !important; overflow: hidden;
        }
        .leaflet-popup-content { margin: 0 !important; width: auto !important; }
        .leaflet-popup-tip-container { margin-top: -1px; }
        .leaflet-marker-icon { transition: filter .15s; }
        .leaflet-marker-icon:hover { filter: brightness(1.15) drop-shadow(0 2px 6px rgba(0,0,0,0.3)); }

    </style>
</head>

<body>

<!-- ===================== HEADER ===================== -->
 <header class="navbar-light py-3 border-bottom shadow-sm" style="background-color: #fff;">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="../../assets/images/e_dossier.png" alt="logo" style="height: 60px;">
                <span class="ms-2 fw-bold text-primary brand-text" style="font-size: 1.5rem;">E-Dossier</span>
            </a>
            <div class="d-flex align-items-center">
                <nav class="navbar-expand-lg">
                    <ul class="nav">
                        <li class="nav-item"><a class="nav-link nav-link-custom" href="index.php"><?php echo __('home'); ?></a></li>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'administrator'): ?>
                            <li class="nav-item"><a class="nav-link nav-link-custom" href="../Boffice/index.php"><?php echo __('dashboard'); ?></a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link fw-bold nav-link-custom" href="Events.php"><?php echo __('Events'); ?></a></li>
                        <li class="nav-item"><a class="nav-link nav-link-custom" href="demandes.php"><?php echo __('demand'); ?></a></li>
                    </ul>
                </nav>
                <style>
                    /* Custom styles for navbar links to support beige in dark mode */
                    .nav-link-custom {
                        color: var(--bs-body-color);
                        transition: color 0.3s ease;
                    }
                    [data-bs-theme='light'] .nav-link-custom {
                        color: #0b0a12 !important; /* Dark Navy in light mode */
                    }
                    [data-bs-theme='dark'] .nav-link-custom,
                    [data-bs-theme='dark'] h1, 
                    [data-bs-theme='dark'] h2, 
                    [data-bs-theme='dark'] h3, 
                    [data-bs-theme='dark'] h4, 
                    [data-bs-theme='dark'] h5, 
                    [data-bs-theme='dark'] h6,
                    [data-bs-theme='dark'] p,
                    [data-bs-theme='dark'] .lead,
                    [data-bs-theme='dark'] label {
                        color: #f5f5dc !important; /* Beige in dark mode to match Backoffice */
                    }
                    .nav-link-custom:hover {
                        color: var(--bs-primary) !important;
                    }
                    /* Brand color sync with Backoffice */
                    [data-bs-theme='light'] .brand-text {
                        color: #0b0a12 !important; /* Dark Navy to match Boffice */
                    }
                    [data-bs-theme='dark'] .brand-text {
                        color: #f5f5dc !important; /* Beige to match Boffice links */
                    }
                    /* Highlight fix for Light Mode */
                    [data-bs-theme='light'] .highlight-brand {
                        background-color: var(--bs-primary) !important;
                        color: white !important;
                        padding: 0 4px;
                        border-radius: 4px;
                    }
                    /* Theme-aware Hero background */
                    .hero-section {
                        background-color: var(--bs-cream);
                    }
                    [data-bs-theme='dark'] header,
                    [data-bs-theme='dark'] .hero-section {
                        background-color: var(--bs-body-bg) !important;
                    }
                    /* Floating search button */
                    .btn-position-md-middle {
                        position: absolute;
                        bottom: -20px;
                        left: 50%;
                        transform: translateX(-50%);
                    }
                    @media (min-width: 768px) {
                        .btn-position-md-middle {
                            top: 50%;
                            bottom: auto;
                            right: -20px;
                            left: auto;
                            transform: translateY(-50%);
                        }
                    }
                    .btn-round {
                        border-radius: 50% !important;
                        width: 44px;
                        height: 44px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        padding: 0;
                    }
                    .icon-lg {
                        width: 44px;
                        height: 44px;
                    }
                    .nav-link-custom {
                        font-family: 'Montserrat', sans-serif !important;
                    }
                    /* Dark mode: fully transparent backgrounds for links/buttons to avoid white flashes */
                    [data-bs-theme='dark'] .nav-link:hover,
                    [data-bs-theme='dark'] .nav-link:focus,
                    [data-bs-theme='dark'] .navbar .btn-light:hover, 
                    [data-bs-theme='dark'] .navbar .btn-light:focus {
                        background-color: transparent !important;
                        border-color: transparent !important;
                        box-shadow: none !important;
                    }
                </style>

                <!-- Language Switcher -->
                <div class="dropdown ms-3">
                    <button class="btn btn-light btn-sm mb-0 px-2" id="languageDropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-globe me-1"></i> <?php echo strtoupper($lang); ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end min-w-auto shadow" aria-labelledby="languageDropdown">
                        <li><a class="dropdown-item <?php echo $lang === 'en' ? 'active' : ''; ?>" href="?lang=en">EN English</a></li>
                        <li><a class="dropdown-item <?php echo $lang === 'fr' ? 'active' : ''; ?>" href="?lang=fr">FR French</a></li>
                        <li><a class="dropdown-item <?php echo $lang === 'ar' ? 'active' : ''; ?>" href="?lang=ar">AR Arabic</a></li>
                    </ul>
                </div>

                <!-- Theme Switcher -->
                <div class="dropdown ms-3">
                    <button class="btn btn-light btn-sm lh-0 mb-0" id="bd-theme" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-circle-half theme-icon-active"></i>
                    </button>
                    <ul class="dropdown-menu min-w-auto dropdown-menu-end shadow" aria-labelledby="bd-theme">
                        <li class="mb-1"><button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light"><i class="bi bi-brightness-high-fill me-2"></i>Light</button></li>
                        <li class="mb-1"><button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark"><i class="bi bi-moon-stars-fill me-2"></i>Dark</button></li>
                        <li><button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto"><i class="bi bi-circle-half me-2"></i>Auto</button></li>
                    </ul>
                </div>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Profile dropdown START -->
                    <div class="dropdown ms-3">
                        <a class="avatar avatar-sm p-0" href="#" id="profileDropdown" role="button" data-bs-auto-close="outside" data-bs-display="static" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php 
                            $profile_img = (isset($_SESSION['profile_image_url']) && !empty($_SESSION['profile_image_url'])) 
                                            ? $_SESSION['profile_image_url'] 
                                            : '../../assets/images/avatar/01.jpg';
                            ?>
                            <img class="avatar-img rounded-circle" src="<?php echo $profile_img; ?>" alt="avatar" style="width: 35px; height: 35px; object-fit: cover; border: 2px solid var(--bs-primary);">
                        </a>
                        <ul class="dropdown-menu dropdown-animation dropdown-menu-end shadow pt-3" aria-labelledby="profileDropdown">
                            <li class="px-3 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <img class="avatar-img rounded-circle shadow" src="<?php echo $profile_img; ?>" alt="avatar" style="width: 40px; height: 40px; object-fit: cover;">
                                    </div>
                                    <div>
                                        <a class="h6 mt-2 mt-sm-0" href="#"><?php echo $_SESSION['name'] ?? 'User'; ?></a>
                                        <p class="small m-0 text-truncate" style="max-width: 150px;"><?php echo $_SESSION['email'] ?? ''; ?></p>
                                    </div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <?php 
                            $profile_link = (isset($_SESSION['role']) && $_SESSION['role'] === 'client') ? 'profile.php' : '../Boffice/account-settings.php';
                            ?>
                            <li><a class="dropdown-item" href="<?php echo $profile_link; ?>"><i class="bi bi-person fa-fw me-2"></i>My Profile</a></li>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'administrator'): ?>
                                <li><a class="dropdown-item" href="../Boffice/settings.php"><i class="bi bi-gear fa-fw me-2"></i>Settings</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item bg-danger-soft-hover" href="../Boffice/logout.php"><i class="bi bi-power fa-fw me-2"></i>Sign Out</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <div class="ms-3">
                        <a href="../Boffice/sign-in.php" class="btn btn-primary btn-sm mb-0 px-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%;" title="<?php echo __('sign_in'); ?>">
                            <i class="fa-solid fa-user fs-5 text-white"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>
<!-- ===================== HEADER END ===================== -->

<main>
    <!-- Hero Section -->
    <section class="pt-3 pt-lg-5 position-relative overflow-hidden hero-section">
        <div class="container pb-4">
            <h1 class="display-5 fw-bold text-primary"><?php echo __('Events'); ?></h1>
            <p class="lead"><?php echo __('hero_info'); ?></p>
        </div>
    </section>

    <!-- ===================== FULL-PAGE OVERLAY MODAL ===================== -->
    <div class="event-modal-overlay" id="eventModalOverlay" role="dialog" aria-modal="true">
        <div class="event-modal" id="eventModal">

            <button class="modal-close-btn" id="modalCloseBtn" aria-label="Fermer">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>

            <div class="modal-header-band">
                <div class="modal-icon-wrap" id="modalIconWrap"></div>
                <div class="modal-title-group">
                    <h4 id="modalHeading"></h4>
                    <p class="modal-subtitle" id="modalEventName"></p>
                </div>
            </div>

            <div class="modal-steps" id="modalSteps" style="display:none;">
                <div class="modal-step-item active" id="step1Indicator">
                    <div class="modal-step-dot">1</div> Inscription
                </div>
                <div class="modal-step-sep"></div>
                <div class="modal-step-item" id="step2Indicator">
                    <div class="modal-step-dot">2</div> Paiement
                </div>
                <div class="modal-step-sep"></div>
                <div class="modal-step-item" id="step3Indicator">
                    <div class="modal-step-dot">✓</div> Confirmé
                </div>
            </div>

            <div id="modalSuccess" style="display:none; padding:2rem 1.75rem; text-align:center;">
                <div style="width:64px;height:64px;border-radius:50%;background:rgba(16,185,129,0.12);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </div>
                <p id="modalSuccessMsg" style="font-family:'Poppins',sans-serif;font-weight:700;font-size:1.05rem;margin:0 0 .4rem;"></p>
                <p id="modalSuccessSub" style="font-size:.82rem;color:var(--bs-secondary-color,#666);margin:0 0 1rem;"></p>
                <p style="font-size:.78rem;color:var(--bs-secondary-color,#aaa);margin:0;">La page se rafraîchit…</p>
            </div>

            <div id="modalError" style="display:none;margin:.75rem 1.75rem 0;padding:.65rem 1rem;background:rgba(220,38,38,.08);border:1px solid rgba(220,38,38,.2);border-radius:10px;font-size:.83rem;color:#dc2626;"></div>

            <!-- STEP 1: Identity form -->
            <div id="modalFormBody">
                <div class="modal-body-section">
                    <div id="joinFields">
                        <div class="modal-field-group">
                            <label class="modal-label" for="f_user_id">CIN / Identifiant</label>
                            <input class="modal-input" type="text" id="f_user_id" placeholder="Ex : 12345678" autocomplete="off">
                        </div>
                        <div class="modal-field-row">
                            <div class="modal-field-group">
                                <label class="modal-label" for="f_nom">Nom</label>
                                <input class="modal-input" type="text" id="f_nom" placeholder="Dupont">
                            </div>
                            <div class="modal-field-group">
                                <label class="modal-label" for="f_prenom">Prénom</label>
                                <input class="modal-input" type="text" id="f_prenom" placeholder="Jean">
                            </div>
                        </div>
                        <div class="modal-field-group" style="max-width:140px;">
                            <label class="modal-label" for="f_age">Âge</label>
                            <input class="modal-input" type="number" id="f_age" placeholder="25" min="1" max="120">
                        </div>
                    </div>

                    <div id="leaveFields" style="display:none;">
                        <p class="modal-confirm-text">Entrez votre CIN pour confirmer que vous souhaitez quitter cet événement.</p>
                        <div class="modal-field-group">
                            <label class="modal-label" for="f_leave_user_id">CIN / Identifiant</label>
                            <input class="modal-input" type="text" id="f_leave_user_id" placeholder="Ex : 12345678" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="modal-footer-section">
                    <button class="modal-btn cancel" id="modalCancelBtn" type="button">Annuler</button>
                    <button class="modal-btn confirm-join" id="modalSubmitBtn" type="button">
                        <span id="modalSubmitLabel">Confirmer</span>
                        <svg id="modalSpinner" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="display:none;animation:spin .7s linear infinite;">
                            <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- STEP 2: Payment form -->
            <div id="paymentFormBody" style="display:none;">
                <div class="modal-body-section">
                    <div class="pay-summary-box">
                        <p class="pay-event-name" id="payEventName"></p>
                        <p class="pay-amount"><span>Total </span><strong id="payAmount"></strong></p>
                    </div>

                    <p class="modal-label" style="margin-bottom:.5rem;">Mode de paiement</p>
                    <div class="pay-method-grid">
                        <div class="pay-method-btn selected" data-method="card" onclick="selectPayMethod(this)">
                            <i class="bi bi-credit-card-2-front"></i> Carte
                        </div>
                        <div class="pay-method-btn" data-method="virement" onclick="selectPayMethod(this)">
                            <i class="bi bi-bank"></i> Virement
                        </div>
                        <div class="pay-method-btn" data-method="especes" onclick="selectPayMethod(this)">
                            <i class="bi bi-cash-coin"></i> Espèces
                        </div>
                    </div>

                    <div class="pay-card-fields show" id="payCardFields">
                        <div class="card-preview">
                            <div style="font-size:.65rem;opacity:.5;text-transform:uppercase;letter-spacing:.1em;">Numéro de carte</div>
                            <div class="card-number-display" id="cardNumberDisplay">•••• •••• •••• ••••</div>
                            <div class="card-meta">
                                <div>Titulaire<strong id="cardHolderDisplay">VOTRE NOM</strong></div>
                                <div>Expire<strong id="cardExpDisplay">MM/AA</strong></div>
                            </div>
                        </div>
                        <div class="modal-field-group">
                            <label class="modal-label" for="pay_card_num">Numéro de carte</label>
                            <input class="modal-input" type="text" id="pay_card_num" placeholder="1234 5678 9012 3456" maxlength="19" autocomplete="cc-number">
                        </div>
                        <div class="modal-field-group">
                            <label class="modal-label" for="pay_holder">Nom sur la carte</label>
                            <input class="modal-input" type="text" id="pay_holder" placeholder="JEAN DUPONT" autocomplete="cc-name">
                        </div>
                        <div class="card-input-row">
                            <div class="modal-field-group">
                                <label class="modal-label" for="pay_exp">Date d'expiration</label>
                                <input class="modal-input" type="text" id="pay_exp" placeholder="MM/AA" maxlength="5" autocomplete="cc-exp">
                            </div>
                            <div class="modal-field-group">
                                <label class="modal-label" for="pay_cvv">CVV</label>
                                <input class="modal-input" type="text" id="pay_cvv" placeholder="•••" maxlength="4" autocomplete="cc-csc">
                            </div>
                        </div>
                    </div>

                    <div id="payVirementInfo" style="display:none;background:rgba(108,92,231,0.06);border:1px solid rgba(108,92,231,0.15);border-radius:12px;padding:1rem 1.1rem;">
                        <p style="margin:0 0 .5rem;font-weight:600;font-size:.88rem;">Coordonnées bancaires</p>
                        <p style="margin:0;font-size:.82rem;color:var(--bs-secondary-color,#555);line-height:1.8;">
                            IBAN : <strong>TN59 1234 5678 9012 3456 7890</strong><br>
                            BIC : <strong>BIATTNTT</strong><br>
                            Réf. : <strong id="virementRef"></strong>
                        </p>
                    </div>

                    <div id="payEspecesInfo" style="display:none;background:rgba(16,185,129,0.06);border:1px solid rgba(16,185,129,0.15);border-radius:12px;padding:1rem 1.1rem;">
                        <p style="margin:0 0 .35rem;font-weight:600;font-size:.88rem;">Paiement sur place</p>
                        <p style="margin:0;font-size:.82rem;color:var(--bs-secondary-color,#555);line-height:1.7;">
                            Présentez-vous à l'accueil le jour de l'événement avec le montant exact.<br>
                            Votre inscription sera confirmée à réception du paiement.
                        </p>
                    </div>
                </div>

                <div class="modal-footer-section">
                    <button class="modal-btn cancel" id="payBackBtn" type="button">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="15 18 9 12 15 6"/></svg>
                        Retour
                    </button>
                    <button class="modal-btn confirm-join" id="payConfirmBtn" type="button">
                        <span id="payConfirmLabel">Payer et confirmer</span>
                        <svg id="paySpinner" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="display:none;animation:spin .7s linear infinite;">
                            <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                        </svg>
                    </button>
                </div>
            </div>

        </div>
    </div>
    <!-- ===================== MODAL END ===================== -->


    <!-- ===================== MAIN CONTENT ===================== -->
    <div id="view-front">
        <div class="content">

            <div class="section-head">
                <div class="section-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6C5CE7" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    Événements actifs
                </div>
                <span class="badge-count"><?= count($active_events) ?> événement(s)</span>
            </div>

            <!-- TABS -->
            <div class="tabs">
                <a href="?tab=all"  class="tab <?= $tab === 'all'  ? 'active' : '' ?>">Tous</a>
                <a href="?tab=mine" class="tab <?= $tab === 'mine' ? 'active' : '' ?>">Mes participations</a>
                <a href="?tab=map"  class="tab <?= $tab === 'map'  ? 'active' : '' ?>">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="vertical-align:-1px;margin-right:4px;">
                        <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                    </svg>
                    Carte
                </a>
            </div>


            <!-- ════ TAB: ALL EVENTS ════ -->
            <?php if ($tab === 'all'): ?>

                <?php if (empty($active_events)): ?>
                    <div class="empty">
                        <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <p>Aucun événement actif pour le moment.</p>
                    </div>

                <?php else: ?>
                    <div class="events-grid">
                        <?php foreach ($active_events as $e):
                            $cnt     = $counts_map[$e['id']] ?? 0;
                            $cap     = $e['capacite_max'];
                            $is_full = $cap !== null && $cnt >= (int)$cap;
                            $is_paid = !empty($e['is_paid']);
                            $prix    = $e['prix'] ?? 0;
                            $date_str = $e['date_debut'] . ($e['date_fin'] ? ' → ' . $e['date_fin'] : '');
                        ?>
                        <div class="event-card">
                            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:.5rem;margin-bottom:.4rem;">
                                <h3 style="margin:0;"><?= htmlspecialchars($e['titre']) ?></h3>
                                <?php if ($is_paid): ?>
                                    <span class="badge-paid"><i class="bi bi-currency-dollar"></i> Payant<?= $prix ? ' – ' . number_format($prix, 2) . ' TND' : '' ?></span>
                                <?php else: ?>
                                    <span class="badge-free"><i class="bi bi-check-circle"></i> Gratuit</span>
                                <?php endif; ?>
                            </div>

                            <?php if ($e['lieu']): ?>
                            <div class="event-meta">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                                <?= htmlspecialchars($e['lieu']) ?>
                            </div>
                            <?php endif; ?>

                            <div class="event-meta">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                <?= htmlspecialchars($e['date_debut']) ?>
                                <?= $e['date_fin'] ? ' → ' . htmlspecialchars($e['date_fin']) : '' ?>
                            </div>

                            <?php if ($e['description']): ?>
                            <p class="event-desc"><?= htmlspecialchars($e['description']) ?></p>
                            <?php endif; ?>

                            <div class="card-footer">
                                <span class="cap-badge">
                                    <?php if ($cap): ?>
                                        <?= $cnt ?> / <?= $cap ?> pers.
                                        <?php if ($is_full): ?><span style="color:#dc2626;font-weight:600;"> · Complet</span><?php endif; ?>
                                    <?php else: ?>
                                        <?= $cnt ?> participant(s)
                                    <?php endif; ?>
                                </span>

                                <?php if (isset($joined[$e['id']])): ?>
                                    <button class="btn-leave js-event-btn"
                                        data-action="leave"
                                        data-id="<?= $e['id'] ?>"
                                        data-titre="<?= htmlspecialchars($e['titre'], ENT_QUOTES) ?>"
                                        data-lieu="<?= htmlspecialchars($e['lieu'] ?? '', ENT_QUOTES) ?>"
                                        data-date="<?= htmlspecialchars($date_str, ENT_QUOTES) ?>"
                                        data-cnt="<?= $cnt ?>"
                                        data-cap="<?= (int)($cap ?? 0) ?>"
                                        data-is-paid="0"
                                        data-prix="0">
                                        Quitter
                                    </button>

                                <?php elseif ($is_full): ?>
                                    <span class="btn-join" style="opacity:0.4;cursor:not-allowed;pointer-events:none;">Complet</span>

                                <?php else: ?>
                                    <button class="btn-join js-event-btn"
                                        data-action="join"
                                        data-id="<?= $e['id'] ?>"
                                        data-titre="<?= htmlspecialchars($e['titre'], ENT_QUOTES) ?>"
                                        data-lieu="<?= htmlspecialchars($e['lieu'] ?? '', ENT_QUOTES) ?>"
                                        data-date="<?= htmlspecialchars($date_str, ENT_QUOTES) ?>"
                                        data-cnt="<?= $cnt ?>"
                                        data-cap="<?= (int)($cap ?? 0) ?>"
                                        data-is-paid="<?= $is_paid ? '1' : '0' ?>"
                                        data-prix="<?= (float)$prix ?>">
                                        <?= $is_paid ? '<i class="bi bi-credit-card me-1"></i>Réserver' : 'Rejoindre' ?>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>


            <!-- ════ TAB: MY EVENTS ════ -->
            <?php elseif ($tab === 'mine'): ?>
                <?php $mine_events = array_filter($active_events, fn($e) => isset($joined[$e['id']])); ?>

                <?php if (empty($mine_events)): ?>
                    <div class="empty">
                        <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <p>Vous ne participez à aucun événement.</p>
                    </div>
                <?php else: ?>
                    <div class="events-grid">
                        <?php foreach ($mine_events as $e): ?>
                        <div class="event-card">
                            <h3><?= htmlspecialchars($e['titre']) ?></h3>
                            <?php if ($e['lieu']): ?>
                            <div class="event-meta">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                                <?= htmlspecialchars($e['lieu']) ?>
                            </div>
                            <?php endif; ?>
                            <div class="event-meta">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                <?= htmlspecialchars($e['date_debut']) ?>
                                <?= $e['date_fin'] ? ' → ' . htmlspecialchars($e['date_fin']) : '' ?>
                            </div>
                            <?php if ($e['description']): ?>
                            <p class="event-desc"><?= htmlspecialchars($e['description']) ?></p>
                            <?php endif; ?>
                            <div class="card-footer">
                                <span class="cap-badge" style="font-size:11px;color:var(--gray-400);">
                                    Inscrit en tant que : <strong><?= htmlspecialchars($joined[$e['id']]) ?></strong>
                                </span>
                                <button class="btn-leave js-event-btn"
                                    data-action="leave"
                                    data-id="<?= $e['id'] ?>"
                                    data-titre="<?= htmlspecialchars($e['titre'], ENT_QUOTES) ?>"
                                    data-lieu="<?= htmlspecialchars($e['lieu'] ?? '', ENT_QUOTES) ?>"
                                    data-date="<?= htmlspecialchars($e['date_debut'] . ($e['date_fin'] ? ' → ' . $e['date_fin'] : ''), ENT_QUOTES) ?>"
                                    data-cnt="0"
                                    data-cap="0">
                                    Quitter
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>


            <!-- ════ TAB: MAP ════ -->
            <?php elseif ($tab === 'map'): ?>

                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;margin-bottom:10px;">
                    <div style="display:inline-flex;align-items:center;gap:6px;background:rgba(108,92,231,.07);border:1px solid rgba(108,92,231,.15);color:#6C5CE7;font-size:12px;font-weight:700;padding:4px 12px;border-radius:20px;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#6C5CE7" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <?= count($active_events) ?> événement(s) sur la carte
                    </div>
                    <div class="map-legend">
                        <span><span class="leg-dot" style="background:#10b981;"></span>Gratuit</span>
                        <span><span class="leg-dot" style="background:#f59e0b;"></span>Payant</span>
                        <span><span class="leg-dot" style="background:rgba(108,92,231,0.4);"></span>Complet</span>
                    </div>
                </div>

                <div class="map-wrapper">
                    <div id="leaflet-map"></div>

                    <!-- Inline join modal floating over the map -->
                    <div id="map-join-modal">
                        <div id="mjm-header" class="mjm-header mjm-header-free">
                            <h4 id="mjm-title">Rejoindre l'événement</h4>
                            <p id="mjm-subtitle"></p>
                        </div>
                        <button class="mjm-close" id="mjm-close-btn" title="Fermer">✕</button>

                        <!-- STEP 1: Identity form -->
                        <div id="mjm-form-body">
                            <div class="mjm-body">
                                <div class="mjm-field">
                                    <label class="mjm-label">CIN / Identifiant</label>
                                    <input class="mjm-input" type="text" id="mjm_uid" placeholder="Ex: 12345678" autocomplete="off">
                                </div>
                                <div class="mjm-row">
                                    <div class="mjm-field">
                                        <label class="mjm-label">Nom</label>
                                        <input class="mjm-input" type="text" id="mjm_nom" placeholder="Dupont">
                                    </div>
                                    <div class="mjm-field">
                                        <label class="mjm-label">Prénom</label>
                                        <input class="mjm-input" type="text" id="mjm_prenom" placeholder="Jean">
                                    </div>
                                </div>
                                <div class="mjm-field" style="max-width:110px;">
                                    <label class="mjm-label">Âge</label>
                                    <input class="mjm-input" type="number" id="mjm_age" placeholder="25" min="1" max="120">
                                </div>
                            </div>
                            <div id="mjm-error" class="mjm-error"></div>
                            <div class="mjm-footer">
                                <button class="mjm-btn mjm-cancel" id="mjm-cancel-btn">Annuler</button>
                                <button class="mjm-btn mjm-join-free" id="mjm-submit-btn">
                                    <span id="mjm-submit-label">Confirmer ✓</span>
                                    <svg id="mjm-spinner" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="display:none;animation:spin .7s linear infinite;">
                                        <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- STEP 2: Payment form -->
                        <div id="mjm-pay-body" style="display:none;">
                            <div class="mjm-body" style="padding-bottom:0;">
                                <div style="background:linear-gradient(135deg,#6C5CE7,#a29bfe);border-radius:11px;padding:12px 14px;margin-bottom:10px;color:#fff;">
                                    <p id="mjm-pay-event-name" style="font-family:'Poppins',sans-serif;font-weight:700;font-size:12px;margin:0 0 2px;"></p>
                                    <p id="mjm-pay-amount" style="font-size:1.5rem;font-weight:800;margin:0;letter-spacing:-.02em;"></p>
                                </div>

                                <p class="mjm-label" style="margin-bottom:5px;">Mode de paiement</p>
                                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:5px;margin-bottom:10px;">
                                    <div class="mjm-pay-method selected" data-m="card"     onclick="mjmSelectMethod(this)"><i class="bi bi-credit-card-2-front"></i><br>Carte</div>
                                    <div class="mjm-pay-method"          data-m="virement" onclick="mjmSelectMethod(this)"><i class="bi bi-bank"></i><br>Virement</div>
                                    <div class="mjm-pay-method"          data-m="especes"  onclick="mjmSelectMethod(this)"><i class="bi bi-cash-coin"></i><br>Espèces</div>
                                </div>

                                <div id="mjm-card-fields">
                                    <div style="background:linear-gradient(135deg,#1a1a2e,#16213e);border-radius:10px;padding:10px 12px;margin-bottom:8px;color:#fff;font-family:'DM Sans',monospace;">
                                        <div style="font-size:.6rem;opacity:.5;text-transform:uppercase;letter-spacing:.1em;">Numéro de carte</div>
                                        <div id="mjm-card-num-disp" style="font-size:.95rem;letter-spacing:.18em;margin:3px 0 5px;color:rgba(255,255,255,.9);">•••• •••• •••• ••••</div>
                                        <div style="display:flex;justify-content:space-between;font-size:.65rem;color:rgba(255,255,255,.5);">
                                            <div>Titulaire<strong id="mjm-card-holder-disp" style="display:block;font-size:.75rem;color:rgba(255,255,255,.85);">VOTRE NOM</strong></div>
                                            <div>Expire<strong id="mjm-card-exp-disp" style="display:block;font-size:.75rem;color:rgba(255,255,255,.85);">MM/AA</strong></div>
                                        </div>
                                    </div>
                                    <div class="mjm-field">
                                        <label class="mjm-label">Numéro de carte</label>
                                        <input class="mjm-input" type="text" id="mjm_card_num" placeholder="1234 5678 9012 3456" maxlength="19" autocomplete="cc-number">
                                    </div>
                                    <div class="mjm-field">
                                        <label class="mjm-label">Nom sur la carte</label>
                                        <input class="mjm-input" type="text" id="mjm_card_holder" placeholder="JEAN DUPONT" autocomplete="cc-name">
                                    </div>
                                    <div class="mjm-row">
                                        <div class="mjm-field">
                                            <label class="mjm-label">Expiration</label>
                                            <input class="mjm-input" type="text" id="mjm_card_exp" placeholder="MM/AA" maxlength="5" autocomplete="cc-exp">
                                        </div>
                                        <div class="mjm-field">
                                            <label class="mjm-label">CVV</label>
                                            <input class="mjm-input" type="text" id="mjm_card_cvv" placeholder="•••" maxlength="4" autocomplete="cc-csc">
                                        </div>
                                    </div>
                                </div>

                                <div id="mjm-virement-info" style="display:none;background:rgba(108,92,231,0.06);border:1px solid rgba(108,92,231,0.15);border-radius:10px;padding:10px 12px;">
                                    <p style="margin:0 0 4px;font-weight:700;font-size:12px;">Coordonnées bancaires</p>
                                    <p style="margin:0;font-size:11px;color:var(--bs-secondary-color,#666);line-height:1.8;">
                                        IBAN : <strong>TN59 1234 5678 9012 3456 7890</strong><br>
                                        BIC : <strong>BIATTNTT</strong><br>
                                        Réf. : <strong id="mjm-virement-ref"></strong>
                                    </p>
                                </div>

                                <div id="mjm-especes-info" style="display:none;background:rgba(16,185,129,0.06);border:1px solid rgba(16,185,129,0.15);border-radius:10px;padding:10px 12px;">
                                    <p style="margin:0 0 3px;font-weight:700;font-size:12px;">Paiement sur place</p>
                                    <p style="margin:0;font-size:11px;color:var(--bs-secondary-color,#666);line-height:1.7;">
                                        Présentez-vous à l'accueil le jour J avec le montant exact.<br>
                                        Votre inscription sera confirmée à réception.
                                    </p>
                                </div>
                            </div>

                            <div id="mjm-pay-error" class="mjm-error"></div>
                            <div class="mjm-footer">
                                <button class="mjm-btn mjm-cancel" id="mjm-pay-back-btn">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="15 18 9 12 15 6"/></svg>
                                    Retour
                                </button>
                                <button class="mjm-btn mjm-join-paid" id="mjm-pay-confirm-btn">
                                    <span id="mjm-pay-confirm-label">Payer et confirmer</span>
                                    <svg id="mjm-pay-spinner" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="display:none;animation:spin .7s linear infinite;">
                                        <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Success screen -->
                        <div class="mjm-success-box" id="mjm-success-box">
                            <div class="mjm-check">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <p id="mjm-success-msg">Inscription confirmée !</p>
                            <small id="mjm-success-sub">La page va se rafraîchir…</small>
                        </div>

                    </div><!-- /#map-join-modal -->
                </div><!-- /.map-wrapper -->

            <?php endif; ?>

        </div><!-- /.content -->
    </div><!-- /#view-front -->
    <!-- ===================== MAIN CONTENT END ===================== -->


    <!-- ===================== SCRIPTS ===================== -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

    <!-- Full-page overlay modal (Join / Leave / Payment) -->
    <script>
    (function () {
        var overlay         = document.getElementById('eventModalOverlay');
        var eventModal      = document.getElementById('eventModal');
        var iconWrap        = document.getElementById('modalIconWrap');
        var heading         = document.getElementById('modalHeading');
        var eventNameEl     = document.getElementById('modalEventName');
        var joinFields      = document.getElementById('joinFields');
        var leaveFields     = document.getElementById('leaveFields');
        var formBody        = document.getElementById('modalFormBody');
        var paymentFormBody = document.getElementById('paymentFormBody');
        var successBox      = document.getElementById('modalSuccess');
        var successMsg      = document.getElementById('modalSuccessMsg');
        var successSub      = document.getElementById('modalSuccessSub');
        var errorBox        = document.getElementById('modalError');
        var submitBtn       = document.getElementById('modalSubmitBtn');
        var submitLabel     = document.getElementById('modalSubmitLabel');
        var spinner         = document.getElementById('modalSpinner');
        var cancelBtn       = document.getElementById('modalCancelBtn');
        var closeBtn        = document.getElementById('modalCloseBtn');
        var modalSteps      = document.getElementById('modalSteps');
        var step1Ind        = document.getElementById('step1Indicator');
        var step2Ind        = document.getElementById('step2Indicator');
        var step3Ind        = document.getElementById('step3Indicator');
        var payBackBtn      = document.getElementById('payBackBtn');
        var payConfirmBtn   = document.getElementById('payConfirmBtn');
        var payConfirmLabel = document.getElementById('payConfirmLabel');
        var paySpinner      = document.getElementById('paySpinner');
        var payEventName    = document.getElementById('payEventName');
        var payAmountEl     = document.getElementById('payAmount');
        var virementRef     = document.getElementById('virementRef');

        var currentAction  = 'join';
        var currentEventId = 0;
        var currentIsPaid  = false;
        var currentPrix    = 0;
        var currentMethod  = 'card';
        var savedUserId    = <?= json_encode($_SESSION['user_id'] ?? '') ?>;
        var savedNom       = <?= json_encode($_SESSION['name'] ?? '') ?>;
        var savedPrenom    = ''; 
        var savedAge       = <?= json_encode($_SESSION['age'] ?? '') ?>;
        var isLoggedIn     = <?= json_encode(isset($_SESSION['user_id'])) ?>;

        var JOIN_ICON  = '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>';
        var PAY_ICON   = '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>';
        var LEAVE_ICON = '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="23" y1="11" x2="17" y2="11"/></svg>';

        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.js-event-btn');
            if (!btn) return;

            currentAction  = btn.getAttribute('data-action');
            currentEventId = btn.getAttribute('data-id');
            currentIsPaid  = btn.getAttribute('data-is-paid') === '1';
            currentPrix    = parseFloat(btn.getAttribute('data-prix')) || 0;
            var titre      = btn.getAttribute('data-titre');
            var isJoin     = currentAction === 'join';

            resetModal();

            iconWrap.className      = 'modal-icon-wrap ' + (isJoin ? 'join' : 'leave');
            iconWrap.innerHTML      = isJoin ? JOIN_ICON : LEAVE_ICON;
            heading.textContent     = isJoin
                ? (currentIsPaid ? 'Réserver cet événement' : 'Rejoindre l\'événement')
                : 'Quitter l\'événement';
            eventNameEl.textContent = titre;

            modalSteps.style.display = (isJoin && currentIsPaid) ? 'flex' : 'none';
            setStep(1);

            joinFields.style.display  = (isJoin && !isLoggedIn) ? 'block' : 'none';
            leaveFields.style.display = (!isJoin && !isLoggedIn) ? 'block' : 'none';

            if (isLoggedIn) {
                if (isJoin) {
                    heading.textContent = currentIsPaid ? 'Confirmer la réservation' : 'Confirmer l\'inscription';
                }
            }

            submitBtn.className     = 'modal-btn ' + (isJoin ? 'confirm-join' : 'confirm-leave');
            submitLabel.textContent = isJoin
                ? (currentIsPaid ? 'Suivant – Paiement' : 'Confirmer l\'inscription')
                : 'Quitter l\'événement';

            eventModal.className = 'event-modal' + (isJoin && currentIsPaid ? ' pay-mode' : '');

            overlay.classList.add('open');
            document.body.style.overflow = 'hidden';

            setTimeout(function () {
                var first = isJoin
                    ? document.getElementById('f_user_id')
                    : document.getElementById('f_leave_user_id');
                if (first) first.focus();
            }, 120);
        });

        submitBtn.addEventListener('click', function () {
            var isJoin = currentAction === 'join';
            if (isJoin) {
                if (!isLoggedIn) {
                    savedUserId = document.getElementById('f_user_id').value.trim();
                    savedNom    = document.getElementById('f_nom').value.trim();
                    savedPrenom = document.getElementById('f_prenom').value.trim();
                    savedAge    = document.getElementById('f_age').value.trim();
                    if (!savedUserId || !savedNom || !savedPrenom || !savedAge) {
                        showError('Veuillez remplir tous les champs.'); return;
                    }
                }
                if (currentIsPaid) { showPaymentStep(); return; }
            } else {
                if (!isLoggedIn) {
                    savedUserId = document.getElementById('f_leave_user_id').value.trim();
                    if (!savedUserId) { showError('Veuillez entrer votre identifiant.'); return; }
                }
            }
            hideError();
            submitToServer(isJoin);
        });

        function showPaymentStep() {
            hideError();
            formBody.style.display        = 'none';
            paymentFormBody.style.display = 'block';
            iconWrap.className  = 'modal-icon-wrap join';
            iconWrap.innerHTML  = PAY_ICON;
            heading.textContent = 'Paiement';
            payEventName.textContent = document.getElementById('modalEventName').textContent;
            payAmountEl.textContent  = currentPrix.toFixed(2) + ' TND';
            virementRef.textContent  = 'EVT-' + currentEventId + '-' + savedUserId.toUpperCase();
            selectPayMethodByName('card');
            setStep(2);
        }

        payBackBtn.addEventListener('click', function () {
            paymentFormBody.style.display = 'none';
            formBody.style.display        = 'block';
            iconWrap.className  = 'modal-icon-wrap join';
            iconWrap.innerHTML  = JOIN_ICON;
            heading.textContent = 'Réserver cet événement';
            setStep(1);
        });

        payConfirmBtn.addEventListener('click', function () {
            hideError();
            if (currentMethod === 'card') {
                var num    = document.getElementById('pay_card_num').value.replace(/\s/g,'');
                var holder = document.getElementById('pay_holder').value.trim();
                var exp    = document.getElementById('pay_exp').value.trim();
                var cvv    = document.getElementById('pay_cvv').value.trim();
                if (num.length < 16 || !holder || exp.length < 5 || cvv.length < 3) {
                    showError('Veuillez remplir correctement les informations de carte.'); return;
                }
            }
            setPayLoading(true);
            var body = new URLSearchParams();
            body.append('action',         'join');
            body.append('event_id',       currentEventId);
            body.append('user_id',        savedUserId);
            body.append('nom',            savedNom);
            body.append('prenom',         savedPrenom);
            body.append('age',            savedAge);
            body.append('payment_method', currentMethod);
            body.append('prix',           currentPrix);

            fetch('join_event.php', {
                method:  'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body:    body.toString()
            }).then(function (res) {
                if (res.ok || res.redirected) {
                    setPayLoading(false);
                    paymentFormBody.style.display = 'none';
                    setStep(3);
                    showSuccess(
                        'Réservation confirmée !',
                        currentMethod === 'card'     ? 'Paiement traité avec succès.'
                        : currentMethod === 'virement' ? 'En attente de réception du virement.'
                        : 'À régler sur place le jour J.'
                    );
                } else { throw new Error('Erreur ' + res.status); }
            }).catch(function () {
                setPayLoading(false);
                showError('Une erreur est survenue. Veuillez réessayer.');
            });
        });

        function submitToServer(isJoin) {
            setLoading(true);
            var body = new URLSearchParams();
            body.append('action',   currentAction);
            body.append('event_id', currentEventId);
            body.append('user_id',  savedUserId);
            if (isJoin) {
                body.append('nom',    savedNom);
                body.append('prenom', savedPrenom);
                body.append('age',    savedAge);
            }
            fetch('join_event.php', {
                method:  'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body:    body.toString()
            }).then(function (res) {
                if (res.ok || res.redirected) {
                    setLoading(false);
                    formBody.style.display = 'none';
                    showSuccess(
                        isJoin ? 'Inscription confirmée !' : 'Vous avez quitté l\'événement.',
                        isJoin ? 'Bienvenue parmi les participants !' : 'Votre participation a été annulée.'
                    );
                } else { throw new Error('Erreur ' + res.status); }
            }).catch(function () {
                setLoading(false);
                showError('Une erreur est survenue. Veuillez réessayer.');
            });
        }

        function showSuccess(msg, sub) {
            successBox.style.display = 'block';
            successMsg.textContent   = msg;
            successSub.textContent   = sub || '';
            setTimeout(function () { window.location.reload(); }, 2200);
        }

        function setStep(n) {
            [step1Ind, step2Ind, step3Ind].forEach(function (el, i) {
                el.classList.remove('active', 'done');
                if (i + 1 < n)  el.classList.add('done');
                if (i + 1 === n) el.classList.add('active');
            });
        }

        window.selectPayMethod = function (el) {
            document.querySelectorAll('.pay-method-btn').forEach(function (b) { b.classList.remove('selected'); });
            el.classList.add('selected');
            currentMethod = el.getAttribute('data-method');
            updatePayFields();
        };

        function selectPayMethodByName(name) {
            document.querySelectorAll('.pay-method-btn').forEach(function (b) {
                b.classList.toggle('selected', b.getAttribute('data-method') === name);
            });
            currentMethod = name;
            updatePayFields();
        }

        function updatePayFields() {
            document.getElementById('payCardFields').style.display   = currentMethod === 'card'     ? 'block' : 'none';
            document.getElementById('payVirementInfo').style.display = currentMethod === 'virement' ? 'block' : 'none';
            document.getElementById('payEspecesInfo').style.display  = currentMethod === 'especes'  ? 'block' : 'none';
            payConfirmLabel.textContent = currentMethod === 'card'
                ? 'Payer ' + currentPrix.toFixed(2) + ' TND'
                : currentMethod === 'virement' ? 'Confirmer la réservation'
                : 'Réserver – payer sur place';
        }

        var cardNumInput    = document.getElementById('pay_card_num');
        var cardHolderInput = document.getElementById('pay_holder');
        var cardExpInput    = document.getElementById('pay_exp');
        var cardNumDisplay  = document.getElementById('cardNumberDisplay');
        var cardHolderDisp  = document.getElementById('cardHolderDisplay');
        var cardExpDisp     = document.getElementById('cardExpDisplay');

        cardNumInput.addEventListener('input', function () {
            var v = this.value.replace(/\D/g, '').substring(0, 16);
            this.value = v.replace(/(.{4})/g, '$1 ').trim();
            var padded = (v + '????????????????').substring(0, 16);
            cardNumDisplay.textContent = padded.replace(/(.{4})/g, '$1 ').trim();
        });
        cardHolderInput.addEventListener('input', function () {
            cardHolderDisp.textContent = this.value.toUpperCase() || 'VOTRE NOM';
        });
        cardExpInput.addEventListener('input', function () {
            var v = this.value.replace(/\D/g, '').substring(0, 4);
            if (v.length >= 3) v = v.substring(0, 2) + '/' + v.substring(2);
            this.value = v;
            cardExpDisp.textContent = this.value || 'MM/AA';
        });

        function resetModal() {
            errorBox.style.display        = 'none';
            errorBox.textContent          = '';
            successBox.style.display      = 'none';
            formBody.style.display        = 'block';
            paymentFormBody.style.display = 'none';
            setLoading(false); setPayLoading(false);
            document.getElementById('f_user_id').value       = '';
            document.getElementById('f_nom').value           = '';
            document.getElementById('f_prenom').value        = '';
            document.getElementById('f_age').value           = '';
            document.getElementById('f_leave_user_id').value = '';
            document.getElementById('pay_card_num').value    = '';
            document.getElementById('pay_holder').value      = '';
            document.getElementById('pay_exp').value         = '';
            document.getElementById('pay_cvv').value         = '';
            if (cardNumDisplay) cardNumDisplay.textContent = '•••• •••• •••• ••••';
            if (cardHolderDisp) cardHolderDisp.textContent = 'VOTRE NOM';
            if (cardExpDisp)    cardExpDisp.textContent    = 'MM/AA';
        }

        function setLoading(on) {
            submitBtn.disabled        = on;
            spinner.style.display     = on ? 'inline-block' : 'none';
            submitLabel.style.display = on ? 'none' : 'inline';
        }
        function setPayLoading(on) {
            payConfirmBtn.disabled        = on;
            paySpinner.style.display      = on ? 'inline-block' : 'none';
            payConfirmLabel.style.display = on ? 'none' : 'inline';
        }
        function showError(msg) { errorBox.textContent = msg; errorBox.style.display = 'block'; }
        function hideError()    { errorBox.style.display = 'none'; errorBox.textContent = ''; }
        function closeModal()   { overlay.classList.remove('open'); document.body.style.overflow = ''; }

        cancelBtn.addEventListener('click', closeModal);
        closeBtn.addEventListener('click',  closeModal);
        overlay.addEventListener('click',   function (e) { if (e.target === overlay) closeModal(); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });
    })();
    </script>


    <!-- Map Tab Script -->
    <?php if ($tab === 'map'): ?>
    <script>
    (function () {
        var EVENTS = <?= $map_events_js ?>;

        var mjModal      = document.getElementById('map-join-modal');
        var mjHeader     = document.getElementById('mjm-header');
        var mjTitle      = document.getElementById('mjm-title');
        var mjSubtitle   = document.getElementById('mjm-subtitle');
        var mjFormBody   = document.getElementById('mjm-form-body');
        var mjPayBody    = document.getElementById('mjm-pay-body');
        var mjSuccessBox = document.getElementById('mjm-success-box');
        var mjError      = document.getElementById('mjm-error');
        var mjPayError   = document.getElementById('mjm-pay-error');
        var mjSubmitBtn  = document.getElementById('mjm-submit-btn');
        var mjSubmitLbl  = document.getElementById('mjm-submit-label');
        var mjSpinner    = document.getElementById('mjm-spinner');
        var mjCloseBtn   = document.getElementById('mjm-close-btn');
        var mjCancelBtn  = document.getElementById('mjm-cancel-btn');
        var mjPayBackBtn = document.getElementById('mjm-pay-back-btn');
        var mjPayConfBtn = document.getElementById('mjm-pay-confirm-btn');
        var mjPayConfLbl = document.getElementById('mjm-pay-confirm-label');
        var mjPaySpinner = document.getElementById('mjm-pay-spinner');

        var activeEventId  = null;
        var activeIsPaid   = false;
        var activePrix     = 0;
        var activeMethod   = 'card';
        var savedUid, savedNom, savedPrenom, savedAge;

        function geocode(lieu, callback) {
            if (!lieu) { callback(null); return; }
            var url = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(lieu);
            fetch(url, { headers: { 'Accept-Language': 'fr' } })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data && data.length > 0) {
                        callback({ lat: parseFloat(data[0].lat), lng: parseFloat(data[0].lon) });
                    } else { callback(null); }
                })
                .catch(function () { callback(null); });
        }

        function makePin(color, labelChar) {
            var svg =
                '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="48" viewBox="0 0 36 48">' +
                    '<filter id="ds" x="-30%" y="-10%" width="160%" height="140%">' +
                        '<feDropShadow dx="0" dy="2" stdDeviation="2" flood-color="rgba(0,0,0,0.25)"/>' +
                    '</filter>' +
                    '<path d="M18 2C9.16 2 2 9.16 2 18c0 11.31 16 28 16 28S34 29.31 34 18C34 9.16 26.84 2 18 2z"' +
                         ' fill="' + color + '" filter="url(#ds)"/>' +
                    '<circle cx="18" cy="18" r="8" fill="rgba(255,255,255,0.92)"/>' +
                    '<text x="18" y="22" text-anchor="middle" font-size="10" font-weight="700"' +
                          ' font-family="Poppins,sans-serif" fill="' + color + '">' + labelChar + '</text>' +
                '</svg>';
            return L.divIcon({ html: svg, iconSize: [36,48], iconAnchor: [18,48], popupAnchor: [0,-50], className: '' });
        }

        var map = L.map('leaflet-map', { center: [34.0, 9.5], zoom: 6, zoomControl: true });
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        var bounds = [];
        var pending = EVENTS.length;
        if (pending === 0) return;

        EVENTS.forEach(function (ev) {
            geocode(ev.lieu, function (coords) {
                pending--;
                if (coords) {
                    bounds.push([coords.lat, coords.lng]);
                    var full     = ev.cap > 0 && ev.cnt >= ev.cap;
                    var pinColor = full ? 'rgba(108,92,231,0.45)' : (ev.is_paid ? '#f59e0b' : '#10b981');
                    var label    = full ? '⊘' : (ev.is_paid ? '€' : '+');
                    var marker   = L.marker([coords.lat, coords.lng], { icon: makePin(pinColor, label) });

                    var paidBadge = ev.is_paid
                        ? '<span style="background:rgba(234,179,8,.14);color:#92400e;padding:2px 8px;border-radius:20px;font-size:10px;font-weight:700;">' + ev.prix.toFixed(2) + ' TND</span>'
                        : '<span style="background:rgba(16,185,129,.12);color:#065f46;padding:2px 8px;border-radius:20px;font-size:10px;font-weight:700;">Gratuit</span>';

                    var capText = ev.cap > 0 ? ev.cnt + ' / ' + ev.cap + ' pers.' : ev.cnt + ' participant(s)';
                    var fullTag = full ? '<span style="color:#dc2626;font-weight:700;"> · Complet</span>' : '';

                    var actionBtn = full
                        ? '<button disabled style="width:100%;margin-top:8px;padding:7px;border-radius:9px;border:none;background:#e5e7eb;color:#9ca3af;font-size:12px;font-weight:700;cursor:not-allowed;">Complet</button>'
                        : '<button onclick="openMapJoin(' + ev.id + ')" style="width:100%;margin-top:8px;padding:7px;border-radius:9px;border:none;cursor:pointer;font-size:12px;font-weight:700;color:#fff;background:' + (ev.is_paid ? 'linear-gradient(135deg,#6C5CE7,#a29bfe)' : 'linear-gradient(135deg,#10b981,#059669)') + ';">'
                          + (ev.is_paid ? '💳 Réserver' : '✚ Rejoindre') + '</button>';

                    var popupHtml =
                        '<div style="font-family:\'Poppins\',sans-serif;padding:10px 12px 8px;min-width:190px;">' +
                            '<p style="font-weight:700;font-size:13px;margin:0 0 4px;color:#1a1a2e;">' + escHtml(ev.titre) + '</p>' +
                            '<p style="font-size:11px;color:#888;margin:0 0 7px;">📍 ' + escHtml(ev.lieu) + '</p>' +
                            (ev.date ? '<p style="font-size:11px;color:#888;margin:0 0 7px;">📅 ' + escHtml(ev.date) + '</p>' : '') +
                            '<div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">' +
                                paidBadge +
                                '<span style="font-size:11px;color:#888;">' + capText + fullTag + '</span>' +
                            '</div>' + actionBtn +
                        '</div>';

                    marker.bindPopup(L.popup({ maxWidth: 240, minWidth: 200 }).setContent(popupHtml));
                    marker.addTo(map);
                }
                if (pending === 0 && bounds.length > 0) {
                    if (bounds.length === 1) { map.setView(bounds[0], 12); }
                    else { map.fitBounds(bounds, { padding: [50, 50] }); }
                }
            });
        });

        function escHtml(s) {
            if (!s) return '';
            return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        window.openMapJoin = function (id) {
            var ev = EVENTS.find(function (e) { return e.id === id; });
            if (!ev) return;

            activeEventId = id;
            activeIsPaid  = ev.is_paid;
            activePrix    = ev.prix;
            activeMethod  = 'card';

            document.getElementById('mjm_uid').value    = '';
            document.getElementById('mjm_nom').value    = '';
            document.getElementById('mjm_prenom').value = '';
            document.getElementById('mjm_age').value    = '';
            mjError.style.display      = 'none';
            mjFormBody.style.display   = 'block';
            mjPayBody.style.display    = 'none';
            mjSuccessBox.style.display = 'none';
            mjSubmitBtn.disabled       = false;
            mjSubmitLbl.textContent    = ev.is_paid ? 'Suivant – Paiement' : 'Confirmer ✓';
            mjSpinner.style.display    = 'none';

            mjHeader.className     = 'mjm-header ' + (ev.is_paid ? 'mjm-header-paid' : 'mjm-header-free');
            mjTitle.textContent    = ev.is_paid ? 'Réserver – ' + ev.prix.toFixed(2) + ' TND' : 'Rejoindre l\'événement';
            mjSubtitle.textContent = ev.titre;
            mjSubmitBtn.className  = 'mjm-btn ' + (ev.is_paid ? 'mjm-join-paid' : 'mjm-join-free');

            mjModal.classList.add('open');
            map.closePopup();
            setTimeout(function () { document.getElementById('mjm_uid').focus(); }, 80);
        };

        window.mjmSelectMethod = function (el) {
            document.querySelectorAll('.mjm-pay-method').forEach(function (b) { b.classList.remove('selected'); });
            el.classList.add('selected');
            activeMethod = el.getAttribute('data-m');
            document.getElementById('mjm-card-fields').style.display    = activeMethod === 'card'     ? 'block' : 'none';
            document.getElementById('mjm-virement-info').style.display  = activeMethod === 'virement' ? 'block' : 'none';
            document.getElementById('mjm-especes-info').style.display   = activeMethod === 'especes'  ? 'block' : 'none';
            mjPayConfLbl.textContent = activeMethod === 'card'
                ? 'Payer ' + activePrix.toFixed(2) + ' TND'
                : activeMethod === 'virement' ? 'Confirmer la réservation' : 'Réserver – payer sur place';
        };

        mjSubmitBtn.addEventListener('click', function () {
            savedUid    = document.getElementById('mjm_uid').value.trim();
            savedNom    = document.getElementById('mjm_nom').value.trim();
            savedPrenom = document.getElementById('mjm_prenom').value.trim();
            savedAge    = document.getElementById('mjm_age').value.trim();

            if (!savedUid || !savedNom || !savedPrenom || !savedAge) {
                mjError.textContent = 'Veuillez remplir tous les champs.';
                mjError.style.display = 'block'; return;
            }
            mjError.style.display = 'none';

            if (activeIsPaid) {
                mjFormBody.style.display = 'none';
                mjPayBody.style.display  = 'block';
                document.getElementById('mjm-pay-event-name').textContent = EVENTS.find(function(e){return e.id===activeEventId;}).titre;
                document.getElementById('mjm-pay-amount').textContent     = activePrix.toFixed(2) + ' TND';
                document.getElementById('mjm-virement-ref').textContent   = 'EVT-' + activeEventId + '-' + savedUid.toUpperCase();
                mjmSelectMethod(document.querySelector('.mjm-pay-method.selected') || document.querySelector('.mjm-pay-method'));
                return;
            }

            doMapSubmit('especes');
        });

        mjPayBackBtn.addEventListener('click', function () {
            mjPayBody.style.display  = 'none';
            mjFormBody.style.display = 'block';
        });

        mjPayConfBtn.addEventListener('click', function () {
            if (activeMethod === 'card') {
                var num = document.getElementById('mjm_card_num').value.replace(/\s/g,'');
                var hld = document.getElementById('mjm_card_holder').value.trim();
                var exp = document.getElementById('mjm_card_exp').value.trim();
                var cvv = document.getElementById('mjm_card_cvv').value.trim();
                if (num.length < 16 || !hld || exp.length < 5 || cvv.length < 3) {
                    mjPayError.textContent    = 'Veuillez remplir correctement les infos de carte.';
                    mjPayError.style.display  = 'block'; return;
                }
            }
            mjPayError.style.display = 'none';
            doMapSubmit(activeMethod);
        });

        function doMapSubmit(method) {
            mjSubmitBtn.disabled      = true;
            mjPayConfBtn.disabled     = true;
            mjSubmitLbl.style.display = 'none';
            mjSpinner.style.display   = 'inline-block';
            mjPayConfLbl.style.display = 'none';
            mjPaySpinner.style.display = 'inline-block';

            var body = new URLSearchParams();
            body.append('action',   'join');
            body.append('event_id', activeEventId);
            body.append('user_id',  savedUid);
            body.append('nom',      savedNom);
            body.append('prenom',   savedPrenom);
            body.append('age',      savedAge);
            if (activeIsPaid) {
                body.append('payment_method', method);
                body.append('prix',           activePrix);
            }

            fetch('join_event.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: body.toString()
            }).then(function (res) {
                if (res.ok || res.redirected) {
                    mjFormBody.style.display   = 'none';
                    mjPayBody.style.display    = 'none';
                    mjSuccessBox.style.display = 'block';
                    document.getElementById('mjm-success-msg').textContent = activeIsPaid ? 'Réservation confirmée !' : 'Inscription confirmée !';
                    document.getElementById('mjm-success-sub').textContent = 'La page va se rafraîchir…';
                    setTimeout(function () { window.location.reload(); }, 2200);
                } else { throw new Error('HTTP ' + res.status); }
            }).catch(function () {
                mjSubmitBtn.disabled       = false;
                mjPayConfBtn.disabled      = false;
                mjSubmitLbl.style.display  = 'inline';
                mjSpinner.style.display    = 'none';
                mjPayConfLbl.style.display = 'inline';
                mjPaySpinner.style.display = 'none';
                mjError.textContent        = 'Erreur réseau. Réessayez.';
                mjError.style.display      = 'block';
            });
        }

        /* Card preview live update inside map modal */
        var mjCardNum    = document.getElementById('mjm_card_num');
        var mjCardHolder = document.getElementById('mjm_card_holder');
        var mjCardExp    = document.getElementById('mjm_card_exp');
        if (mjCardNum) {
            mjCardNum.addEventListener('input', function () {
                var v = this.value.replace(/\D/g,'').substring(0,16);
                this.value = v.replace(/(.{4})/g,'$1 ').trim();
                var padded = (v+'????????????????').substring(0,16);
                document.getElementById('mjm-card-num-disp').textContent = padded.replace(/(.{4})/g,'$1 ').trim();
            });
        }
        if (mjCardHolder) {
            mjCardHolder.addEventListener('input', function () {
                document.getElementById('mjm-card-holder-disp').textContent = this.value.toUpperCase() || 'VOTRE NOM';
            });
        }
        if (mjCardExp) {
            mjCardExp.addEventListener('input', function () {
                var v = this.value.replace(/\D/g,'').substring(0,4);
                if (v.length >= 3) v = v.substring(0,2) + '/' + v.substring(2);
                this.value = v;
                document.getElementById('mjm-card-exp-disp').textContent = this.value || 'MM/AA';
            });
        }

        function closeMapModal() { mjModal.classList.remove('open'); activeEventId = null; }
        mjCloseBtn.addEventListener('click',  closeMapModal);
        mjCancelBtn.addEventListener('click', closeMapModal);
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeMapModal(); });
    })();
    </script>
    <?php endif; ?>

</main>

<!-- ===================== FOOTER ===================== -->
<footer class="py-5" style="background-color: #1d3b53; color: white;">
    <div class="container">
        <div class="row g-4 justify-content-between">
            <div class="col-lg-4">
                <div class="d-flex align-items-center mb-3">
                    <img src="../../assets/images/e_dossier.png" alt="logo" style="height: 40px; filter: brightness(0) invert(1);">
                    <span class="ms-2 fw-bold text-white fs-4">E-Dossier</span>
                </div>
                <p class="small opacity-75">Providing modern solutions for digital dossier management since 2026.</p>
            </div>
            <div class="col-lg-6 text-lg-end">
                <ul class="nav justify-content-lg-end mb-3">
                    <li class="nav-item"><a href="#" class="nav-link text-white small px-2">Privacy Policy</a></li>
                    <li class="nav-item"><a href="#" class="nav-link text-white small px-2">Terms of Use</a></li>
                </ul>
                <p class="mb-0 small opacity-50">&copy; 2026 e_dossier. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<!-- Vocal & Chat Assistants -->
<?php
require_once '../../CONTROLLER/VoiceCONTROLLER.php';
require_once '../../CONTROLLER/ChatCONTROLLER.php';
require_once '../../CONTROLLER/MessengerWidget.php';
echo renderVocalAssistant($lang ?? 'en');
echo renderChatAssistant();
echo renderMessengerWidget();
?>

<script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

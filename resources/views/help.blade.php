<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('help.title') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Manrope:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap&subset=cyrillic,latin"
        rel="stylesheet">

    <style>
        :root {
            --bg: #0a0c11;
            --bg-soft: #11141c;
            --panel: #14171f;
            --panel-edge: #232838;
            --ink: #e8ebf2;
            --ink-dim: #8b93a7;
            --ink-faint: #5b6376;
            --gold: #f5b942;
            --gold-soft: #ffd476;

            --c-search: #38bdf8;
            --c-monitoring: #f87171;
            --c-kingdom: #a78bfa;
            --c-migration: #34d399;
            --c-settings: #94a3b8;
            --c-badges: #fbbf24;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background-color: var(--bg);
            background-image:
                radial-gradient(900px 600px at 78% -8%, rgba(245, 185, 66, 0.10), transparent 60%),
                radial-gradient(700px 500px at 0% 100%, rgba(56, 189, 248, 0.07), transparent 55%),
                linear-gradient(rgba(255, 255, 255, 0.022) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.022) 1px, transparent 1px);
            background-size: auto, auto, 44px 44px, 44px 44px;
            color: var(--ink);
            font-family: 'Manrope', system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
            line-height: 1.55;
        }

        .wrap {
            max-width: 1080px;
            margin: 0 auto;
            padding: 0 22px 96px;
        }

        /* ---------- Header ---------- */
        header.hero {
            padding: 64px 0 36px;
            position: relative;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            letter-spacing: 0.32em;
            text-transform: uppercase;
            color: var(--gold);
            border: 1px solid rgba(245, 185, 66, 0.32);
            border-radius: 100px;
            padding: 6px 14px;
            background: rgba(245, 185, 66, 0.05);
        }

        .eyebrow .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--gold);
            box-shadow: 0 0 10px 2px rgba(245, 185, 66, 0.7);
            animation: pulse 2.4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.7); }
        }

        h1.title {
            font-family: 'Oswald', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            font-size: clamp(42px, 8vw, 86px);
            line-height: 0.94;
            letter-spacing: 0.01em;
            margin: 22px 0 0;
        }

        h1.title .accent {
            color: var(--gold);
            position: relative;
        }

        .subtitle {
            margin: 18px 0 0;
            max-width: 560px;
            color: var(--ink-dim);
            font-size: 16px;
        }

        .legend {
            margin-top: 26px;
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: var(--ink-faint);
        }

        .legend code {
            color: var(--gold-soft);
            background: rgba(245, 185, 66, 0.08);
            padding: 2px 7px;
            border-radius: 5px;
        }

        /* ---------- Search / filter bar ---------- */
        .toolbar {
            position: sticky;
            top: 0;
            z-index: 20;
            margin: 8px 0 40px;
            padding: 16px 0;
            background: linear-gradient(var(--bg) 72%, rgba(10, 12, 17, 0));
        }

        .search {
            position: relative;
        }

        .search svg {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            stroke: var(--ink-faint);
        }

        .search input {
            width: 100%;
            background: var(--panel);
            border: 1px solid var(--panel-edge);
            border-radius: 14px;
            padding: 16px 18px 16px 50px;
            color: var(--ink);
            font-family: 'JetBrains Mono', monospace;
            font-size: 15px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .search input::placeholder {
            color: var(--ink-faint);
        }

        .search input:focus {
            border-color: rgba(245, 185, 66, 0.55);
            box-shadow: 0 0 0 4px rgba(245, 185, 66, 0.10);
        }

        .empty {
            display: none;
            text-align: center;
            color: var(--ink-faint);
            font-family: 'JetBrains Mono', monospace;
            padding: 40px 0;
        }

        /* ---------- Sections ---------- */
        .section {
            --c: var(--gold);
            margin-bottom: 34px;
            opacity: 0;
            transform: translateY(16px);
            animation: rise 0.6s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }

        .section[data-cat="search"] { --c: var(--c-search); }
        .section[data-cat="monitoring"] { --c: var(--c-monitoring); }
        .section[data-cat="kingdom"] { --c: var(--c-kingdom); }
        .section[data-cat="migration"] { --c: var(--c-migration); }
        .section[data-cat="settings"] { --c: var(--c-settings); }
        .section[data-cat="badges"] { --c: var(--c-badges); }
        .section[data-cat="notify"] { --c: #fb923c; }

        @keyframes rise {
            to { opacity: 1; transform: translateY(0); }
        }

        .sec-head {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 16px;
        }

        .sec-badge {
            flex: none;
            width: 46px;
            height: 46px;
            display: grid;
            place-items: center;
            font-size: 22px;
            border-radius: 12px;
            background: color-mix(in srgb, var(--c) 14%, transparent);
            border: 1px solid color-mix(in srgb, var(--c) 38%, transparent);
            box-shadow: 0 0 22px -6px color-mix(in srgb, var(--c) 60%, transparent);
        }

        .sec-title {
            font-family: 'Oswald', sans-serif;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-size: 24px;
            margin: 0;
        }

        .sec-count {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: var(--ink-faint);
            margin-left: auto;
        }

        .panel {
            border: 1px solid var(--panel-edge);
            border-radius: 16px;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.015), transparent 30%),
                var(--panel);
            overflow: hidden;
        }

        /* ---------- Command rows ---------- */
        .cmd {
            display: flex;
            align-items: baseline;
            gap: 18px;
            padding: 15px 20px;
            border-left: 2px solid transparent;
            border-top: 1px solid rgba(255, 255, 255, 0.04);
            transition: background 0.16s, border-color 0.16s;
        }

        .cmd:first-child {
            border-top: none;
        }

        .cmd:hover {
            background: color-mix(in srgb, var(--c) 7%, transparent);
            border-left-color: var(--c);
        }

        .cmd-sig {
            flex: none;
            min-width: 220px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 14.5px;
            white-space: nowrap;
        }

        .cmd-name {
            color: var(--ink);
            font-weight: 700;
            background: rgba(255, 255, 255, 0.04);
            padding: 3px 9px;
            border-radius: 7px;
            border: 1px solid rgba(255, 255, 255, 0.07);
        }

        .cmd:hover .cmd-name {
            color: var(--c);
            border-color: color-mix(in srgb, var(--c) 45%, transparent);
        }

        .cmd-args {
            color: var(--ink-faint);
            margin-left: 8px;
        }

        .cmd-body {
            color: var(--ink-dim);
            font-size: 14.5px;
        }

        .cmd-ex {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 7px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12.5px;
            color: var(--gold-soft);
            background: rgba(245, 185, 66, 0.07);
            border: 1px solid rgba(245, 185, 66, 0.18);
            border-radius: 7px;
            padding: 3px 9px;
        }

        .cmd-ex::before {
            content: var(--ex-label, 'example');
            font-size: 9.5px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--ink-faint);
        }

        .cmd-body {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .cmd-body .desc-line {
            display: block;
        }

        .cmd-body .flag {
            display: inline-block;
            margin-left: 9px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            letter-spacing: 0.04em;
            color: var(--c);
            background: color-mix(in srgb, var(--c) 12%, transparent);
            border: 1px solid color-mix(in srgb, var(--c) 30%, transparent);
            border-radius: 6px;
            padding: 2px 8px;
            vertical-align: middle;
            white-space: nowrap;
        }

        /* ---------- Sub-commands ---------- */
        .subcmds {
            margin: 2px 20px 14px 20px;
            border-left: 2px solid color-mix(in srgb, var(--c) 45%, transparent);
            border-radius: 0 10px 10px 0;
            background: rgba(255, 255, 255, 0.018);
            overflow: hidden;
        }

        .subcmds-label {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--c);
        }

        .subcmds-label::before {
            content: '↳';
            font-size: 13px;
            opacity: 0.8;
        }

        .subcmd {
            display: flex;
            align-items: baseline;
            gap: 16px;
            padding: 9px 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.035);
            transition: background 0.16s;
        }

        .subcmd:hover {
            background: color-mix(in srgb, var(--c) 6%, transparent);
        }

        .subcmd .cmd-sig {
            min-width: 180px;
            font-size: 13.5px;
        }

        .subcmd .cmd-name {
            font-weight: 500;
            background: rgba(255, 255, 255, 0.03);
        }

        .subcmd .cmd-body {
            font-size: 13.5px;
        }

        /* ---------- Chat presets ---------- */
        .preset {
            flex-direction: column;
            align-items: stretch;
            gap: 9px;
            padding: 13px 16px;
        }

        .preset-head {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pnum {
            flex: none;
            width: 22px;
            height: 22px;
            display: grid;
            place-items: center;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            font-weight: 700;
            color: var(--bg);
            background: var(--c);
            border-radius: 6px;
        }

        .pname {
            font-weight: 700;
            color: var(--ink);
            font-size: 14.5px;
        }

        .pdesc {
            color: var(--ink-dim);
            font-size: 13.5px;
        }

        .preset-ex {
            margin: 0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12.5px;
            line-height: 1.7;
            color: var(--ink-dim);
            background: rgba(0, 0, 0, 0.32);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 9px;
            padding: 11px 13px;
            white-space: pre-wrap;
            word-break: break-word;
        }

        /* ---------- Notification reference ---------- */
        .notify-intro {
            margin: 0;
            padding: 18px 20px 4px;
            color: var(--ink-dim);
            font-size: 14.5px;
        }

        .bubble-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
            padding: 16px 20px 8px;
            align-items: start;
        }

        .bubble-fig {
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 9px;
        }

        .bubble-cap {
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--c);
        }

        .bubble-cap .kind {
            color: var(--ink-faint);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            padding: 1px 6px;
            font-size: 10px;
        }

        .notify-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 18px 24px;
            padding: 8px 20px 22px;
            align-items: start;
        }

        .bubble {
            --c: #fb923c;
            background: #1a1f2b;
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 14px 14px 14px 4px;
            padding: 14px 16px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            line-height: 1.85;
            color: var(--ink);
            box-shadow: 0 14px 40px -18px rgba(0, 0, 0, 0.8);
        }

        .bubble .b-head {
            font-weight: 700;
            color: var(--c);
            display: block;
            margin-bottom: 2px;
        }

        .bubble .b-line {
            display: block;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .notify-legend-title {
            padding: 14px 20px 4px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--ink-faint);
        }

        .notify-legend {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2px 10px;
            padding: 4px 14px 6px;
        }

        .leg {
            display: flex;
            gap: 12px;
            padding: 8px 10px;
            border-radius: 9px;
            transition: background 0.16s;
        }

        .leg:hover {
            background: color-mix(in srgb, var(--c) 8%, transparent);
        }

        .leg .ico {
            flex: none;
            width: 24px;
            text-align: center;
            font-size: 16px;
            line-height: 1.5;
        }

        .leg .meaning {
            font-size: 13.5px;
            color: var(--ink-dim);
        }

        .leg .meaning b {
            color: var(--ink);
            font-weight: 600;
        }

        .leg .guess {
            display: inline-block;
            margin-left: 6px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--c);
            border: 1px solid color-mix(in srgb, var(--c) 35%, transparent);
            border-radius: 5px;
            padding: 1px 6px;
        }

        .notify-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 0 20px 20px;
            padding-top: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .notify-actions .pill {
            font-size: 12.5px;
            color: var(--ink-dim);
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 9px;
            padding: 7px 12px;
        }

        @media (max-width: 760px) {
            .notify-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ---------- Language switch ---------- */
        .lang-switch {
            position: absolute;
            top: 64px;
            right: 0;
            display: inline-flex;
            gap: 2px;
            padding: 3px;
            border: 1px solid var(--panel-edge);
            border-radius: 100px;
            background: var(--panel);
        }

        .lang-switch a {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.04em;
            color: var(--ink-dim);
            text-decoration: none;
            padding: 5px 12px;
            border-radius: 100px;
            transition: color 0.15s, background 0.15s;
        }

        .lang-switch a:hover { color: var(--ink); }

        .lang-switch a.on {
            color: #1a1206;
            background: var(--gold);
        }

        @media (max-width: 620px) {
            .lang-switch { top: 24px; }
        }

        /* ---------- Ask-the-bot chat ---------- */
        .ask {
            --c: var(--gold);
            border: 1px solid var(--panel-edge);
            border-radius: 18px;
            background:
                linear-gradient(180deg, rgba(245, 185, 66, 0.05), transparent 28%),
                var(--panel);
            padding: 20px;
            margin: 6px 0 30px;
            box-shadow: 0 24px 70px -34px rgba(0, 0, 0, 0.9);
        }

        .ask-head {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 16px;
        }

        .ask-badge {
            flex: none;
            width: 46px;
            height: 46px;
            display: grid;
            place-items: center;
            font-size: 22px;
            border-radius: 12px;
            background: color-mix(in srgb, var(--c) 14%, transparent);
            border: 1px solid color-mix(in srgb, var(--c) 38%, transparent);
            box-shadow: 0 0 22px -6px color-mix(in srgb, var(--c) 60%, transparent);
        }

        .ask-title {
            font-family: 'Oswald', sans-serif;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 22px;
            margin: 2px 0 0;
        }

        .ask-sub {
            margin: 4px 0 0;
            color: var(--ink-dim);
            font-size: 13.5px;
        }

        .ask-clear {
            margin-left: auto;
            flex: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: inherit;
            font-size: 12.5px;
            color: var(--ink-dim);
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--panel-edge);
            border-radius: 9px;
            padding: 7px 11px;
            cursor: pointer;
            transition: color 0.15s, border-color 0.15s, background 0.15s;
        }

        .ask-clear svg {
            width: 15px;
            height: 15px;
            stroke: currentColor;
        }

        .ask-clear:hover {
            color: #f87171;
            border-color: rgba(248, 113, 113, 0.4);
            background: rgba(248, 113, 113, 0.08);
        }

        @media (max-width: 480px) {
            .ask-clear span { display: none; }
        }

        .ask-log {
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-height: 440px;
            overflow-y: auto;
            padding: 6px 4px 2px;
            scrollbar-width: thin;
            scrollbar-color: var(--panel-edge) transparent;
        }

        .msg {
            display: flex;
        }

        .msg.user { justify-content: flex-end; }
        .msg.bot { justify-content: flex-start; }

        .bubble-msg {
            max-width: 86%;
            padding: 11px 14px;
            border-radius: 14px;
            font-size: 14.5px;
            line-height: 1.62;
            word-break: break-word;
        }

        .msg.bot .bubble-msg {
            background: #1a1f2b;
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-bottom-left-radius: 5px;
            color: var(--ink);
        }

        .msg.user .bubble-msg {
            background: color-mix(in srgb, var(--c) 16%, #1a1f2b);
            border: 1px solid color-mix(in srgb, var(--c) 32%, transparent);
            border-bottom-right-radius: 5px;
            color: var(--ink);
        }

        .bubble-msg.streaming::after {
            content: '';
            display: inline-block;
            width: 7px;
            height: 15px;
            margin-left: 3px;
            background: var(--c);
            vertical-align: text-bottom;
            animation: blink 1s steps(2) infinite;
        }

        @keyframes blink {
            50% { opacity: 0; }
        }

        .bubble-msg code.copyable {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            color: var(--gold-soft);
            background: rgba(245, 185, 66, 0.10);
            border: 1px solid rgba(245, 185, 66, 0.28);
            border-radius: 7px;
            padding: 2px 9px;
            margin: 1px 0;
            cursor: pointer;
            transition: background 0.15s, border-color 0.15s;
        }

        .bubble-msg code.copyable:hover {
            background: rgba(245, 185, 66, 0.18);
        }

        .bubble-msg code.copyable::after {
            content: '⧉';
            font-size: 12px;
            opacity: 0.65;
        }

        .bubble-msg code.copyable.copied {
            color: #4ade80;
            border-color: rgba(74, 222, 128, 0.4);
            background: rgba(74, 222, 128, 0.12);
        }

        .bubble-msg code.copyable.copied::after {
            content: '✓';
            opacity: 1;
        }

        .bubble-msg pre.code-block {
            margin: 6px 0;
        }

        .bubble-msg pre.code-block code.copyable {
            display: flex;
            white-space: pre-wrap;
            line-height: 1.6;
        }

        .ask-form {
            display: flex;
            gap: 10px;
            margin-top: 14px;
        }

        .ask-form input {
            flex: 1;
            min-width: 0;
            background: var(--bg-soft);
            border: 1px solid var(--panel-edge);
            border-radius: 12px;
            padding: 14px 16px;
            color: var(--ink);
            font-family: inherit;
            font-size: 15px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .ask-form input:focus {
            border-color: color-mix(in srgb, var(--c) 55%, transparent);
            box-shadow: 0 0 0 4px color-mix(in srgb, var(--c) 10%, transparent);
        }

        .ask-form button {
            flex: none;
            width: 50px;
            border: none;
            border-radius: 12px;
            background: var(--c);
            display: grid;
            place-items: center;
            cursor: pointer;
            transition: opacity 0.15s, transform 0.08s;
        }

        .ask-form button svg {
            width: 20px;
            height: 20px;
            stroke: #1a1206;
        }

        .ask-form button:hover { opacity: 0.92; }
        .ask-form button:active { transform: scale(0.94); }
        .ask-form button:disabled { opacity: 0.45; cursor: not-allowed; }

        /* ---------- Badges grid ---------- */
        .badge-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
            gap: 10px;
            padding: 18px;
        }

        .badge {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 11px 14px;
            border-radius: 11px;
            background: rgba(255, 255, 255, 0.022);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: transform 0.16s, border-color 0.16s, background 0.16s;
        }

        .badge:hover {
            transform: translateY(-2px);
            background: color-mix(in srgb, var(--c) 8%, transparent);
            border-color: color-mix(in srgb, var(--c) 35%, transparent);
        }

        .badge .ico {
            font-size: 20px;
            line-height: 1;
            flex: none;
            width: 26px;
            text-align: center;
        }

        .badge .txt {
            font-size: 13.5px;
            color: var(--ink-dim);
        }

        /* ---------- Footer ---------- */
        footer {
            margin-top: 56px;
            padding-top: 26px;
            border-top: 1px solid var(--panel-edge);
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: var(--ink-faint);
        }

        footer .hint {
            color: var(--ink-dim);
        }

        @media (max-width: 620px) {
            .cmd {
                flex-direction: column;
                gap: 8px;
            }
            .cmd-sig {
                min-width: 0;
            }
            h1.title {
                font-size: clamp(38px, 13vw, 60px);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .section { animation: none; opacity: 1; transform: none; }
            .eyebrow .dot { animation: none; }
        }
    </style>
</head>

<body style="--ex-label: '{{ __('help.ex_word') }}'">
    <div class="wrap">
        <header class="hero">
            <div class="lang-switch">
                <a href="?lang=ru" class="{{ app()->getLocale() === 'ru' ? 'on' : '' }}">RU</a>
                <a href="?lang=en" class="{{ app()->getLocale() === 'en' ? 'on' : '' }}">EN</a>
            </div>
            <span class="eyebrow"><span class="dot"></span>Lords Mobile · Bot</span>
            <h1 class="title">{{ __('help.hero_title_a') }} <span class="accent">{{ __('help.hero_title_b') }}</span></h1>
            <p class="subtitle">{{ __('help.hero_sub') }}</p>
            <div class="legend">
                <span><code>{...}</code> {{ __('help.legend_arg') }}</span>
                <span><code>[...]</code> {{ __('help.legend_opt') }}</span>
                <span><code>{{ __('help.legend_cmd_token') }}</code> {{ __('help.legend_cmd') }}</span>
            </div>
        </header>

        <section class="ask" data-cat="notify">
            <div class="ask-head">
                <span class="ask-badge">💬</span>
                <div>
                    <h2 class="ask-title">{{ __('help.ask_title') }}</h2>
                    <p class="ask-sub">{{ __('help.ask_sub') }}</p>
                </div>
                <button type="button" class="ask-clear" id="chat-clear" title="{{ __('help.clear') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"></path>
                    </svg>
                    <span>{{ __('help.clear') }}</span>
                </button>
            </div>

            <div class="ask-log" id="chat-log" aria-live="polite">
                <div class="msg bot">
                    <div class="bubble-msg">{!! __('help.ask_greeting') !!}</div>
                </div>
            </div>

            <form class="ask-form" id="chat-form" autocomplete="off">
                <input type="text" id="chat-input" name="message" maxlength="1000"
                    placeholder="{{ __('help.input_ph') }}" autocomplete="off">
                <button type="submit" id="chat-send" aria-label="{{ __('help.send') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M13 6l6 6-6 6"></path>
                    </svg>
                </button>
            </form>
        </section>

        <div class="toolbar">
            <div class="search">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="7"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>
                <input id="filter" type="search" autocomplete="off" spellcheck="false"
                    placeholder="{{ __('help.filter_ph') }}">
            </div>
        </div>

        <main id="list">
            @foreach ($sections as $section)
                <section class="section" data-cat="{{ $section['key'] }}"
                    style="animation-delay: {{ $loop->index * 80 }}ms">
                    <div class="sec-head">
                        <span class="sec-badge">{{ $section['icon'] }}</span>
                        <h2 class="sec-title">{{ $section['title'] }}</h2>
                        <span class="sec-count">{{ count($section['commands']) }}</span>
                    </div>
                    <div class="panel">
                        @foreach ($section['commands'] as $command)
                            <div class="cmd"
                                data-search="{{ Str::lower($command['cmd'] . ' ' . $command['args'] . ' ' . $command['desc'] . ' ' . ($command['flag'] ?? '') . ' ' . ($command['example'] ?? '')) }}">
                                <div class="cmd-sig">
                                    <span class="cmd-name">{{ $command['cmd'] }}</span>@if (!empty($command['args']))<span class="cmd-args">{{ $command['args'] }}</span>@endif
                                </div>
                                <div class="cmd-body">
                                    <span class="desc-line">{{ $command['desc'] }}@if (!empty($command['flag']))<span class="flag">{{ $command['flag'] }}</span>@endif</span>
                                    @if (!empty($command['example']))
                                        <span class="cmd-ex">{{ $command['example'] }}</span>
                                    @endif
                                </div>
                            </div>
                            @if (!empty($command['sub']))
                                <div class="subcmds">
                                    <div class="subcmds-label">{{ __('help.subcmds_label') }} {{ $command['cmd'] }}</div>
                                    @foreach ($command['sub'] as $sub)
                                        <div class="subcmd"
                                            data-search="{{ Str::lower($command['cmd'] . ' ' . $sub['cmd'] . ' ' . $sub['args'] . ' ' . $sub['desc'] . ' ' . ($sub['example'] ?? '')) }}">
                                            <div class="cmd-sig">
                                                <span class="cmd-name">{{ $sub['cmd'] }}</span>@if (!empty($sub['args']))<span class="cmd-args">{{ $sub['args'] }}</span>@endif
                                            </div>
                                            <div class="cmd-body">
                                                <span class="desc-line">{{ $sub['desc'] }}</span>
                                                @if (!empty($sub['example']))
                                                    <span class="cmd-ex">{{ $sub['example'] }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @if (!empty($command['presets']))
                                <div class="subcmds">
                                    <div class="subcmds-label">{{ __('help.presets_label') }} {{ $command['cmd'] }}</div>
                                    @foreach ($command['presets'] as $preset)
                                        <div class="subcmd preset"
                                            data-search="{{ Str::lower($preset['n'] . ' ' . $preset['name'] . ' ' . $preset['desc']) }}">
                                            <div class="preset-head">
                                                <span class="pnum">{{ $preset['n'] }}</span>
                                                <span class="pname">{{ $preset['name'] }}</span>
                                                <span class="pdesc">— {{ $preset['desc'] }}</span>
                                            </div>
                                            <pre class="preset-ex">{{ $preset['example'] }}</pre>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </div>
                </section>
            @endforeach

            <section class="section" data-cat="notify" data-static
                style="animation-delay: {{ count($sections) * 80 }}ms">
                <div class="sec-head">
                    <span class="sec-badge">📡</span>
                    <h2 class="sec-title">{{ __('help.notify_title') }}</h2>
                </div>
                <div class="panel">
                    <p class="notify-intro">{{ __('help.notify_intro') }}</p>

                    <div class="bubble-row">
                        @foreach ($notifications as $n)
                            <figure class="bubble-fig">
                                <figcaption class="bubble-cap" style="--c: {{ $n['accent'] }}">
                                    {{ $n['icon'] }} {{ $n['type'] }}
                                    <span class="kind">{{ $n['kind'] }}</span>
                                </figcaption>
                                <div class="bubble" style="--c: {{ $n['accent'] }}">
                                    <span class="b-head">[ {{ $n['icon'] }} ] | {{ $n['type'] }}</span>
                                    @foreach ($n['lines'] as $line)
                                        <span class="b-line">{{ $line }}</span>
                                    @endforeach
                                </div>
                            </figure>
                        @endforeach
                    </div>

                    <div class="notify-legend-title">{{ __('help.notify_legend_title') }}</div>
                    <div class="notify-legend">
                        <div class="leg"><span class="ico">🛡️</span><span class="meaning"><b>{{ __('help.leg_shield') }}</b> — {{ __('help.leg_shield_d') }}</span></div>
                        <div class="leg"><span class="ico">‼️</span><span class="meaning"><b>{{ __('help.leg_fury') }}</b> — {{ __('help.leg_fury_d') }}</span></div>
                        <div class="leg"><span class="ico">🟢</span><span class="meaning"><b>{{ __('help.leg_online') }}</b> — {{ __('help.leg_online_d') }}</span></div>
                        <div class="leg"><span class="ico">💀</span><span class="meaning"><b>{{ __('help.leg_prisoners') }}</b> {{ __('help.leg_prisoners_d') }}</span></div>
                        <div class="leg"><span class="ico">🏰</span><span class="meaning"><b>{{ __('help.leg_tagname') }}</b> {{ __('help.leg_tagname_d') }}</span></div>
                        <div class="leg"><span class="ico">🎯</span><span class="meaning"><b>{{ __('help.leg_coords') }}</b> — {{ __('help.leg_coords_d') }}</span></div>
                        <div class="leg"><span class="ico">💪</span><span class="meaning"><b>{{ __('help.leg_might') }}</b> · <span style="opacity:.7">⚔️</span> {{ __('help.leg_might_d') }}</span></div>
                        <div class="leg"><span class="ico">⏳</span><span class="meaning"><b>{{ __('help.leg_activity') }}</b> — {{ __('help.leg_activity_d') }}</span></div>
                        <div class="leg"><span class="ico">🔥</span><span class="meaning"><b>{{ __('help.leg_burn') }}</b> — {{ __('help.leg_burn_d') }}</span></div>
                        <div class="leg"><span class="ico">🔗</span><span class="meaning"><b>{{ __('help.leg_captured') }}</b> — {{ __('help.leg_captured_d') }}</span></div>
                        <div class="leg"><span class="ico">🛡️</span><span class="meaning"><b>{{ __('help.leg_shieldtime') }}</b> — {{ __('help.leg_shieldtime_d') }}</span></div>
                        <div class="leg"><span class="ico">‼️</span><span class="meaning"><b>{{ __('help.leg_furytime') }}</b> — {{ __('help.leg_furytime_d') }}</span></div>
                    </div>

                    <div class="notify-actions">
                        <span class="pill">ℹ️ {{ __('help.act_info') }}</span>
                        <span class="pill">📊 {{ __('help.act_activity') }}</span>
                        <span class="pill">🎽 {{ __('help.act_gear') }}</span>
                        <span class="pill">📡 {{ __('help.act_ping') }}</span>
                        <span class="pill">🛸 {{ __('help.act_track') }}</span>
                        <span class="pill">💪 {{ __('help.act_phalanx') }}</span>
                        <span class="pill">{{ __('help.act_more') }}</span>
                    </div>
                </div>
            </section>

            <section class="section" data-cat="badges"
                style="animation-delay: {{ (count($sections) + 1) * 80 }}ms">
                <div class="sec-head">
                    <span class="sec-badge">📋</span>
                    <h2 class="sec-title">{{ __('help.badges_title') }}</h2>
                    <span class="sec-count">{{ count($badges) }}</span>
                </div>
                <div class="panel">
                    <div class="badge-grid">
                        @foreach ($badges as $badge)
                            <div class="badge" data-search="{{ Str::lower($badge['icon'] . ' ' . $badge['desc']) }}">
                                <span class="ico">{{ $badge['icon'] }}</span>
                                <span class="txt">{{ $badge['desc'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <p class="empty" id="empty">{{ __('help.empty') }}</p>
        </main>

        <footer>
            <span>© {{ date('Y') }} · {{ __('help.footer_copy') }}</span>
            <span class="hint">{{ __('help.footer_hint') }}</span>
        </footer>
    </div>

    <script>
        const input = document.getElementById('filter');
        const empty = document.getElementById('empty');
        const sections = Array.from(document.querySelectorAll('.section'));

        input.addEventListener('input', () => {
            const q = input.value.trim().toLowerCase();
            let anyVisible = false;

            sections.forEach((section) => {
                // Static reference sections (no commands) — show only when not searching.
                if (section.dataset.static !== undefined) {
                    section.style.display = q ? 'none' : '';
                    return;
                }

                let visibleInSection = 0;
                let total = 0;

                // Command groups: a .cmd row plus an optional trailing .subcmds block.
                section.querySelectorAll('.cmd').forEach((cmd) => {
                    total++;
                    const wrap = cmd.nextElementSibling?.classList.contains('subcmds')
                        ? cmd.nextElementSibling : null;

                    const cmdMatch = !q || cmd.dataset.search.includes(q);
                    let subVisible = 0;

                    if (wrap) {
                        wrap.querySelectorAll('.subcmd').forEach((sc) => {
                            const m = !q || sc.dataset.search.includes(q);
                            sc.style.display = m ? '' : 'none';
                            if (m) subVisible++;
                        });
                    }

                    const show = cmdMatch || subVisible > 0;
                    cmd.style.display = show ? '' : 'none';
                    if (wrap) wrap.style.display = show && subVisible > 0 ? '' : 'none';
                    if (show) visibleInSection++;
                });

                // Status badges (flat).
                section.querySelectorAll('.badge').forEach((b) => {
                    total++;
                    const m = !q || b.dataset.search.includes(q);
                    b.style.display = m ? '' : 'none';
                    if (m) visibleInSection++;
                });

                section.style.display = visibleInSection ? '' : 'none';
                if (visibleInSection) anyVisible = true;

                const count = section.querySelector('.sec-count');
                if (count) count.textContent = q ? visibleInSection : total;
            });

            empty.style.display = anyVisible ? 'none' : 'block';
        });

        // Keyboard focus shortcut: "/" (ignored while typing in any field)
        document.addEventListener('keydown', (e) => {
            const tag = document.activeElement?.tagName;
            if (e.key === '/' && tag !== 'INPUT' && tag !== 'TEXTAREA') {
                e.preventDefault();
                input.focus();
            }
        });
    </script>

    <script>
        (function () {
            const STORAGE_KEY = 'help-chat-history-v1';
            const PAIR_LIMIT = 5;                 // remember last 5 Q/A pairs
            const MSG_LIMIT = PAIR_LIMIT * 2;     // = 10 messages
            const ENDPOINT = "{{ route('help.chat') }}";
            const csrf = document.querySelector('meta[name="csrf-token"]').content;
            const T = {
                error: @json(__('help.js_error')),
                empty: @json(__('help.js_empty')),
            };

            const form = document.getElementById('chat-form');
            const input = document.getElementById('chat-input');
            const sendBtn = document.getElementById('chat-send');
            const clearBtn = document.getElementById('chat-clear');
            const log = document.getElementById('chat-log');

            const greetingHtml = log.innerHTML; // captured before history is rendered

            let history = loadHistory();
            let busy = false;

            renderHistory();

            // --- storage ---
            function loadHistory() {
                try { return JSON.parse(localStorage.getItem(STORAGE_KEY)) || []; }
                catch (_) { return []; }
            }
            function save() {
                try { localStorage.setItem(STORAGE_KEY, JSON.stringify(history)); } catch (_) {}
            }
            function trim() {
                while (history.length > MSG_LIMIT) history.shift();
            }

            // --- rendering ---
            function escapeHtml(s) {
                return s.replace(/[&<>"']/g, (c) => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
                }[c]));
            }

            // Trim whitespace and a single layer of surrounding quotes the model
            // sometimes adds around a command, e.g.  ' /i Player '  →  /i Player
            function cleanCode(s) {
                s = s.trim();
                const q = s.match(/^(['"`])([\s\S]*)\1$/);
                if (q) s = q[2].trim();
                return s;
            }

            // Minimal markdown: fenced ``` blocks + `inline` → copyable <code>; \n → <br>
            function renderMarkdown(text) {
                const fenceRe = /```(?:\w+)?\n?([\s\S]*?)```/g;
                let out = '', last = 0, m;
                while ((m = fenceRe.exec(text))) {
                    out += inline(text.slice(last, m.index));
                    out += '<pre class="code-block"><code class="copyable">'
                        + escapeHtml(cleanCode(m[1])) + '</code></pre>';
                    last = m.index + m[0].length;
                }
                out += inline(text.slice(last));
                return out;

                function inline(chunk) {
                    const re = /`([^`\n]+)`/g;
                    let res = '', pos = 0, mm;
                    while ((mm = re.exec(chunk))) {
                        res += escapeHtml(chunk.slice(pos, mm.index)).replace(/\n/g, '<br>');
                        res += '<code class="copyable">' + escapeHtml(cleanCode(mm[1])) + '</code>';
                        pos = mm.index + mm[0].length;
                    }
                    res += escapeHtml(chunk.slice(pos)).replace(/\n/g, '<br>');
                    return res;
                }
            }

            function addMessage(role, content) {
                const wrap = document.createElement('div');
                wrap.className = 'msg ' + role;
                const bubble = document.createElement('div');
                bubble.className = 'bubble-msg';
                if (role === 'user') bubble.textContent = content;
                else bubble.innerHTML = content || '';
                wrap.appendChild(bubble);
                log.appendChild(wrap);
                scrollDown();
                return bubble;
            }

            function renderHistory() {
                history.forEach((msg) => {
                    if (msg.role === 'user') addMessage('user', msg.content);
                    else addMessage('bot', renderMarkdown(msg.content));
                });
            }

            function scrollDown() { log.scrollTop = log.scrollHeight; }

            // --- copy-to-clipboard (event delegation) ---
            log.addEventListener('click', (e) => {
                const code = e.target.closest('code.copyable');
                if (!code) return;
                const text = code.textContent;
                navigator.clipboard?.writeText(text).then(() => {
                    code.classList.add('copied');
                    setTimeout(() => code.classList.remove('copied'), 1200);
                });
            });

            // --- clear chat + history ---
            clearBtn.addEventListener('click', () => {
                if (busy) return;
                history = [];
                save();
                log.innerHTML = greetingHtml;
                input.focus();
            });

            form.addEventListener('submit', (e) => { e.preventDefault(); send(); });

            function send() {
                const text = input.value.trim();
                if (!text || busy) return;
                input.value = '';
                addMessage('user', text);
                history.push({ role: 'user', content: text });
                trim(); save();
                streamAnswer(text);
            }

            async function streamAnswer(message) {
                busy = true;
                sendBtn.disabled = true;
                const bubble = addMessage('bot', '');
                bubble.classList.add('streaming');
                let full = '';

                // prior context = history without the just-added user message, capped
                const prior = history.slice(0, -1).slice(-MSG_LIMIT);

                try {
                    const res = await fetch(ENDPOINT, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'text/event-stream',
                        },
                        body: JSON.stringify({ message, history: prior }),
                    });
                    if (!res.ok || !res.body) throw new Error('HTTP ' + res.status);

                    const reader = res.body.getReader();
                    const decoder = new TextDecoder();
                    let buf = '';
                    while (true) {
                        const { value, done } = await reader.read();
                        if (done) break;
                        buf += decoder.decode(value, { stream: true });
                        const parts = buf.split('\n\n');
                        buf = parts.pop();
                        for (const part of parts) {
                            const line = part.trim();
                            if (!line.startsWith('data:')) continue;
                            const data = line.slice(5).trim();
                            if (data === '[DONE]') continue;
                            try {
                                const ev = JSON.parse(data);
                                if (ev.type === 'text_delta' && ev.delta) {
                                    full += ev.delta;
                                    bubble.innerHTML = renderMarkdown(full);
                                    scrollDown();
                                }
                            } catch (_) { /* ignore non-JSON keepalive lines */ }
                        }
                    }
                } catch (err) {
                    if (!full) full = T.error;
                } finally {
                    bubble.classList.remove('streaming');
                    if (!full.trim()) full = T.empty;
                    bubble.innerHTML = renderMarkdown(full);
                    history.push({ role: 'assistant', content: full });
                    trim(); save();
                    busy = false;
                    sendBtn.disabled = false;
                    input.focus();
                    scrollDown();
                }
            }
        })();
    </script>
</body>

</html>

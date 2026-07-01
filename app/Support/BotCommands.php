<?php

namespace App\Support;

use Illuminate\Support\Facades\App;

/**
 * Single source of truth for the bot's command reference (RU + EN).
 *
 * Consumed by:
 *  - HelpController  → renders the HTML help page
 *  - HelpChatAgent   → builds the grounded system prompt (toPrompt())
 *
 * Every public method accepts a locale ('ru' | 'en') and returns plain,
 * already-localized strings so consumers stay locale-agnostic.
 */
class BotCommands
{
    /** Pick a localized string from a ['ru' => , 'en' => ] pair. */
    private static function t(array $pair, string $locale): string
    {
        return $pair[$locale] ?? $pair['en'] ?? reset($pair);
    }

    private static function locale(?string $locale): string
    {
        $locale ??= App::getLocale();

        return $locale === 'ru' ? 'ru' : 'en';
    }

    /**
     * Command reference grouped into thematic sections.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function sections(?string $locale = null): array
    {
        $l = self::locale($locale);

        $flagFast = self::t(['ru' => '⚡ быстрый режим', 'en' => '⚡ fast mode'], $l);
        $flagSlow = self::t(['ru' => 'замедляет скан', 'en' => 'slows map scan'], $l);
        $name = self::t(['ru' => '{имя}', 'en' => '{name}'], $l);
        $tag = self::t(['ru' => '{тег}', 'en' => '{tag}'], $l);
        $range = self::t(['ru' => '{от} {до}', 'en' => '{from} {to}'], $l);

        return [
            [
                'icon' => '🔍',
                'title' => self::t(['ru' => 'Поиск игроков', 'en' => 'Player Search'], $l),
                'key' => 'search',
                'commands' => [
                    ['cmd' => '/i', 'args' => $name, 'desc' => self::t(['ru' => 'Найти игрока по точному имени', 'en' => 'Find player by exact name'], $l), 'example' => '/i Player'],
                    ['cmd' => '/ii', 'args' => $name, 'desc' => self::t(['ru' => 'Найти игрока по похожему нику', 'en' => 'Find player by similar nickname'], $l), 'example' => '/ii Player'],
                    ['cmd' => '/fl', 'args' => $name, 'desc' => self::t(['ru' => 'Отслеживать местоположение игрока', 'en' => 'Track player location'], $l), 'flag' => $flagFast, 'example' => '/fl Player'],
                    ['cmd' => '/p', 'args' => $name, 'desc' => self::t(['ru' => 'Пинговать игрока', 'en' => 'Ping specific player'], $l), 'flag' => $flagSlow, 'example' => '/p Player'],
                    ['cmd' => '/pt', 'args' => $name, 'desc' => self::t(['ru' => 'Тест фаланги игрока', 'en' => 'Phalanx test player'], $l), 'flag' => $flagSlow, 'example' => '/pt Player'],
                    ['cmd' => '/show', 'args' => $tag, 'desc' => self::t(['ru' => 'Показать членов гильдии без щита', 'en' => 'Show unshielded guild members'], $l), 'example' => '/show ABC'],
                    ['cmd' => '/ginfo', 'args' => $tag, 'desc' => self::t(['ru' => 'Показать всех членов гильдии', 'en' => 'Show all guild members'], $l), 'example' => '/ginfo ABC'],
                ],
            ],
            [
                'icon' => '⚔️',
                'title' => self::t(['ru' => 'Мониторинг', 'en' => 'Monitoring'], $l),
                'key' => 'monitoring',
                'commands' => [
                    ['cmd' => '/fury', 'args' => $range, 'desc' => self::t(['ru' => 'Игроки с запалом. Мощь указывается числом в миллионах: 100 = 100m, 1000 = 1b', 'en' => 'Players with battle fury. Might is given as a number in millions: 100 = 100m, 1000 = 1b'], $l), 'example' => '/fury 100 1000'],
                    ['cmd' => '/inactive', 'args' => self::t(['ru' => '{от} {до} {часы}', 'en' => '{from} {to} {hours}'], $l), 'desc' => self::t(['ru' => 'Найти неактивных игроков', 'en' => 'Find inactive players'], $l), 'example' => '/inactive 100 1000 24'],
                    ['cmd' => '/unshield', 'args' => $range, 'desc' => self::t(['ru' => 'Найти цели без щита в диапазоне', 'en' => 'Locate unshielded targets in range'], $l), 'example' => '/unshield 100 1000'],
                    ['cmd' => '/shields', 'args' => self::t(['ru' => '[от] [до] [часы]', 'en' => '[from] [to] [hours]'], $l), 'desc' => self::t(['ru' => 'Падение щитов в указанном диапазоне. Все параметры необязательны: без них показывает падения щитов по любой мощи за последний час', 'en' => 'Shield drops in the given range. All parameters are optional: without them it shows shield drops for any might over the last hour'], $l), 'example' => '/shields'],
                    ['cmd' => '/burn', 'args' => '', 'desc' => self::t(['ru' => 'Горящие замки', 'en' => 'Monitor burning castles'], $l)],
                    ['cmd' => '/teleports', 'args' => '', 'desc' => self::t(['ru' => 'Недавние телепорты (за 5 мин)', 'en' => 'Recent teleport activity (last 5 min)'], $l)],
                ],
            ],
            [
                'icon' => '🏰',
                'title' => self::t(['ru' => 'Королевство', 'en' => 'Kingdom'], $l),
                'key' => 'kingdom',
                'commands' => [
                    ['cmd' => '/kinfo', 'args' => '{kingdomId}', 'desc' => self::t(['ru' => 'Информация о королевстве', 'en' => 'Show kingdom information'], $l), 'example' => '/kinfo 1091'],
                    ['cmd' => '/ktop', 'args' => '{kingdomId}', 'desc' => self::t(['ru' => 'Топ игроков королевства (сборщики)', 'en' => 'Show kingdom top players (rally leads)'], $l), 'example' => '/ktop 1091'],
                    ['cmd' => '/baronpl', 'args' => '{kingdomId}', 'desc' => self::t(['ru' => 'Таблица игроков для ивента Барон. Без аргументов использует текущее королевство бота', 'en' => "Display player table for the Baron event. Use without arguments to use the bot's current kingdom"], $l), 'example' => '/baronpl 623'],
                    ['cmd' => '/baronlist', 'args' => '{kingdomId}', 'desc' => self::t(['ru' => 'Список королевств и назначения чудес для ивента Барон. Без аргументов показывает все', 'en' => 'Show kingdom list and wonder targets for the Baron event. Use without arguments to show all'], $l), 'example' => '/baronlist 623'],
                ],
            ],
            [
                'icon' => '🌍',
                'title' => self::t(['ru' => 'Миграция', 'en' => 'Migration'], $l),
                'key' => 'migration',
                'commands' => [
                    ['cmd' => '/migrate', 'args' => self::t(['ru' => '{мощь} {свитки}', 'en' => '{might} {scrolls}'], $l), 'desc' => self::t(['ru' => 'Найти королевства для миграции по мощи и макс. свиткам. Мощь можно указывать как 1.5b или числом в миллионах (1500)', 'en' => 'Find kingdoms for migration by might and max scrolls. Might can be given as 1.5b or a number in millions (1500)'], $l), 'example' => '/migrate 1.5b 3'],
                    ['cmd' => '/chalicemigration', 'args' => '', 'desc' => self::t(['ru' => 'Королевства доступные через релокейт (Migration Deal)', 'en' => 'Kingdoms available via relocate (Migration Deal)'], $l)],
                    ['cmd' => '/move', 'args' => '{id}', 'desc' => self::t(['ru' => 'Сменить королевство', 'en' => 'Change kingdom'], $l), 'example' => '/move 1091'],
                ],
            ],
            [
                'icon' => '⚙️',
                'title' => self::t(['ru' => 'Настройки', 'en' => 'Settings'], $l),
                'key' => 'settings',
                'commands' => [
                    ['cmd' => '/settings', 'args' => '', 'desc' => self::t(['ru' => 'Настроить бота', 'en' => 'Configure bot settings'], $l), 'flag' => self::t(['ru' => 'или /control', 'en' => 'or /control'], $l)],
                    ['cmd' => '/filters', 'args' => '', 'desc' => self::t(['ru' => 'Управление фильтрами сообщений', 'en' => 'Manage message filters'], $l), 'sub' => [
                        ['cmd' => '/tag', 'args' => '{tag}', 'desc' => self::t(['ru' => 'Отметить гильдию', 'en' => 'Tag a guild'], $l), 'example' => '/tag ABC'],
                        ['cmd' => '/untag', 'args' => '{tag}', 'desc' => self::t(['ru' => 'Удалить гильдию из списка', 'en' => 'Remove a guild from the list'], $l), 'example' => '/untag ABC'],
                        ['cmd' => '/mute', 'args' => '{tag}', 'desc' => self::t(['ru' => 'Игнорировать гильдию', 'en' => 'Ignore a guild'], $l), 'example' => '/mute ABC'],
                        ['cmd' => '/unmute', 'args' => '{tag}', 'desc' => self::t(['ru' => 'Снять игнор с гильдии', 'en' => 'Stop ignoring a guild'], $l), 'example' => '/unmute ABC'],
                        ['cmd' => '/mutefury', 'args' => '{tag}', 'desc' => self::t(['ru' => 'Замутить запал гильдии', 'en' => "Mute a guild's fury"], $l), 'example' => '/mutefury ABC'],
                        ['cmd' => '/unmutefury', 'args' => '{tag}', 'desc' => self::t(['ru' => 'Размутить запал гильдии', 'en' => "Unmute a guild's fury"], $l), 'example' => '/unmutefury ABC'],
                        ['cmd' => '/minlvl', 'args' => '{lvl}', 'desc' => self::t(['ru' => 'Минимальный уровень замка', 'en' => 'Minimum castle level'], $l), 'example' => '/minlvl 16'],
                        ['cmd' => '/minoffline', 'args' => '{minutes}', 'desc' => self::t(['ru' => 'Мин. время офлайн для уведомления (0 = выкл)', 'en' => 'Min. offline time for a notification (0 = off)'], $l), 'example' => '/minoffline 30'],
                        ['cmd' => '/power', 'args' => '{from} {to}', 'desc' => self::t(['ru' => 'Общий лимит мощи для уведомлений: показывать только игроков в этом диапазоне мощи (числа в миллионах: 100 = 100m, 1000 = 1b)', 'en' => 'General might limit for notifications: only show players within this might range (numbers in millions: 100 = 100m, 1000 = 1b)'], $l), 'example' => '/power 100 1000'],
                        ['cmd' => '/powerfury', 'args' => '{from} {to}', 'desc' => self::t(['ru' => 'Лимит мощи именно для уведомлений о ЗАПАЛЕ: в уведомления о запале попадут только игроки в этом диапазоне мощи (числа в миллионах: 100 = 100m, 1000 = 1b)', 'en' => 'Might limit specifically for FURY alerts: only players within this might range will trigger fury notifications (numbers in millions: 100 = 100m, 1000 = 1b)'], $l), 'example' => '/powerfury 100 1000'],
                        ['cmd' => '/settime', 'args' => '{offset}', 'desc' => self::t(['ru' => 'Установить ваш часовой пояс (например -3, +5:30)', 'en' => 'Set your timezone (e.g. -3, +5:30)'], $l), 'example' => '/settime +5:30'],
                    ]],
                    ['cmd' => '/chatbot', 'args' => '', 'desc' => self::t(['ru' => 'Панель управления ChatBot', 'en' => 'ChatBot control panel'], $l)],
                    ['cmd' => '/chatpreset', 'args' => '{1-3}', 'desc' => self::t(['ru' => 'Переключить формат сообщений ChatBot', 'en' => 'Switch ChatBot message format'], $l), 'example' => '/chatpreset 2', 'presets' => [
                        ['n' => '1', 'name' => self::t(['ru' => 'Стандартный', 'en' => 'Standard'], $l), 'desc' => self::t(['ru' => 'Базовая инфо + последняя активность', 'en' => 'Basic info + last activity'], $l), 'example' => self::t(['ru' => '[ABC]Player K:123 X:456 Y:789 3 минуты назад (Атака)', 'en' => '[ABC]Player K:123 X:456 Y:789 3 minutes ago (Attack)'], $l)],
                        ['n' => '2', 'name' => self::t(['ru' => 'Подробный', 'en' => 'Detailed'], $l), 'desc' => self::t(['ru' => 'Мощь, убийства, активность + время запала', 'en' => 'Might, kills, activity + fury time'], $l), 'example' => self::t(['ru' => "[ABC]Player K:123 X:456 Y:789 P:302M K:12M\n3 минуты назад (Атака)\nFired 4 days ago", 'en' => "[ABC]Player K:123 X:456 Y:789 P:302M K:12M\n3 minutes ago (Attack)\nFired 4 days ago"], $l)],
                        ['n' => '3', 'name' => self::t(['ru' => 'Минимальный', 'en' => 'Minimal'], $l), 'desc' => self::t(['ru' => 'Только координаты', 'en' => 'Coordinates only'], $l), 'example' => '[ABC]Player K:123 X:456 Y:789'],
                    ]],
                    ['cmd' => '/lang', 'args' => '', 'desc' => self::t(['ru' => 'Сменить язык', 'en' => 'Switch interface language'], $l)],
                    ['cmd' => '/kvklink', 'args' => '', 'desc' => self::t(['ru' => 'Привязка чатов для автоматической раздачи подписок KVK (требуется KVK Feature в вашей подписке)', 'en' => 'Link chats for automatic KVK subscription distribution (requires KVK Feature in your subscription)'], $l)],
                ],
            ],
        ];
    }

    /**
     * Example background notifications / button cards the bot sends.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function notifications(?string $locale = null): array
    {
        $l = self::locale($locale);
        $ru = $l === 'ru';

        return [
            [
                'type' => self::t(['ru' => 'Упал щит', 'en' => 'Shield Down'], $l),
                'icon' => '🛡️',
                'kind' => self::t(['ru' => 'Фоновое', 'en' => 'Auto'], $l),
                'accent' => '#2dd4bf',
                'note' => self::t(['ru' => 'У замка слетела защита — цель открыта для атаки.', 'en' => 'The castle lost its shield — the target is open to attack.'], $l),
                'lines' => $ru ? [
                    '🏰 [ABC]Player',
                    '🎯 (K:1091 X:141 Y:375)',
                    '💪 22.36M ⚔️ 4',
                    '⏳ 3 часов 58 минут назад (Охота)',
                    '🔥 11 часов назад 💪22.27M ➡️ 22.36M (➕92.96K)',
                    '🔗 [ABC]Player',
                    '🛡️ 04:00:17',
                ] : [
                    '🏰 [ABC]Player',
                    '🎯 (K:1091 X:141 Y:375)',
                    '💪 22.36M ⚔️ 4',
                    '⏳ 3 hours 58 minutes ago (Hunt)',
                    '🔥 11 hours ago 💪22.27M ➡️ 22.36M (➕92.96K)',
                    '🔗 [ABC]Player',
                    '🛡️ 04:00:17',
                ],
            ],
            [
                'type' => self::t(['ru' => 'Запал', 'en' => 'Battle Fury'], $l),
                'icon' => '‼️',
                'kind' => self::t(['ru' => 'Фоновое', 'en' => 'Auto'], $l),
                'accent' => '#f87171',
                'note' => self::t(['ru' => 'Замок вышел на запал — идёт сбор ярости / активная атака.', 'en' => 'The castle has gone into fury — gathering rage / active attack.'], $l),
                'lines' => $ru ? [
                    '💀 🏰 [ABC]Player',
                    '🎯 (K:1091 X:269 Y:749)',
                    '💪 1.97B ⚔️ 1.26B',
                    '⏳ 7 секунд назад (Разведка замка)',
                    '‼️ 14:54',
                ] : [
                    '💀 🏰 [ABC]Player',
                    '🎯 (K:1091 X:269 Y:749)',
                    '💪 1.97B ⚔️ 1.26B',
                    '⏳ 7 seconds ago (Castle Scout)',
                    '‼️ 14:54',
                ],
            ],
            [
                'type' => self::t(['ru' => 'Инфо', 'en' => 'Info'], $l),
                'icon' => 'ℹ️',
                'kind' => self::t(['ru' => 'По кнопке', 'en' => 'On tap'], $l),
                'accent' => '#38bdf8',
                'note' => self::t(['ru' => 'Карточка игрока по кнопке «Инфо»: те же поля плюс статус 🟢 онлайн и доступ к действиям «Следить» и «Тест фаланги».', 'en' => 'Player card from the «Info» button: same fields plus 🟢 online status and access to the «Track» and «Phalanx test» actions.'], $l),
                'lines' => $ru ? [
                    '🟢 💀 🏰 [ABC]Player',
                    '🎯 (K:1091 X:479 Y:205)',
                    '💪 1.97B ⚔️ 1.26B',
                    '⏳ 32 секунд назад (Разведка замка)',
                    '‼️ 14:28',
                ] : [
                    '🟢 💀 🏰 [ABC]Player',
                    '🎯 (K:1091 X:479 Y:205)',
                    '💪 1.97B ⚔️ 1.26B',
                    '⏳ 32 seconds ago (Castle Scout)',
                    '‼️ 14:28',
                ],
            ],
        ];
    }

    /**
     * Status badge legend.
     *
     * @return array<int, array<string, string>>
     */
    public static function badges(?string $locale = null): array
    {
        $l = self::locale($locale);

        return [
            ['icon' => '‼️', 'desc' => self::t(['ru' => 'Запал активен', 'en' => 'Battle fury active'], $l)],
            ['icon' => '⌛️', 'desc' => self::t(['ru' => 'Активность игрока', 'en' => 'Player activity'], $l)],
            ['icon' => '🚬', 'desc' => self::t(['ru' => 'Замок дымится', 'en' => 'Castle smoking'], $l)],
            ['icon' => '👑', 'desc' => self::t(['ru' => 'Возвращение лидера', 'en' => 'Return of the Lord'], $l)],
            ['icon' => '💀', 'desc' => self::t(['ru' => 'Есть пленники', 'en' => 'Has prisoners'], $l)],
            ['icon' => '🔰', 'desc' => self::t(['ru' => 'Щит активирован', 'en' => 'Shield protection active'], $l)],
            ['icon' => '🔥', 'desc' => self::t(['ru' => 'Замок горит', 'en' => 'Castle burning'], $l)],
            ['icon' => '🔗', 'desc' => self::t(['ru' => 'Лидер захвачен', 'en' => 'Leader captured'], $l)],
            ['icon' => '🟢', 'desc' => self::t(['ru' => 'Онлайн (активность за последние 30 минут)', 'en' => 'Online (activity within last 30 minutes)'], $l)],
        ];
    }

    /**
     * Render the whole reference as compact plain text for an LLM system prompt.
     */
    public static function toPrompt(?string $locale = null): string
    {
        $l = self::locale($locale);
        $out = [];

        $out[] = $l === 'ru' ? '== КОМАНДЫ ==' : '== COMMANDS ==';
        foreach (self::sections($l) as $section) {
            $out[] = '';
            $out[] = '# '.$section['title'];
            foreach ($section['commands'] as $cmd) {
                $out[] = self::commandLine($cmd, $l);
                foreach ($cmd['sub'] ?? [] as $sub) {
                    $out[] = '   '.self::commandLine($sub, $l);
                }
                foreach ($cmd['presets'] ?? [] as $preset) {
                    $example = str_replace("\n", ' / ', $preset['example']);
                    $label = $l === 'ru' ? 'пресет' : 'preset';
                    $exLabel = $l === 'ru' ? 'пример' : 'example';
                    $out[] = '   '.$label.' '.$preset['n'].' '.$preset['name'].' — '.$preset['desc'].' | '.$exLabel.': '.$example;
                }
            }
        }

        $out[] = '';
        $out[] = $l === 'ru' ? '== ЗНАЧКИ СТАТУСА ==' : '== STATUS ICONS ==';
        foreach (self::badges($l) as $badge) {
            $out[] = $badge['icon'].' — '.$badge['desc'];
        }

        $out[] = '';
        $out[] = $l === 'ru'
            ? '== ФОНОВЫЕ УВЕДОМЛЕНИЯ (бот сам присылает) =='
            : '== BACKGROUND NOTIFICATIONS (the bot sends them automatically) ==';
        foreach (self::notifications($l) as $n) {
            $out[] = $n['icon'].' '.$n['type'].' ('.$n['kind'].') — '.$n['note'];
        }

        return implode("\n", $out);
    }

    /**
     * Format a single command as one prompt line.
     *
     * @param  array<string, mixed>  $cmd
     */
    private static function commandLine(array $cmd, string $locale): string
    {
        $line = $cmd['cmd'];
        if (! empty($cmd['args'])) {
            $line .= ' '.$cmd['args'];
        }
        $line .= ' — '.$cmd['desc'];
        if (! empty($cmd['flag'])) {
            $line .= ' ['.$cmd['flag'].']';
        }
        if (! empty($cmd['example'])) {
            $line .= ' | '.($locale === 'ru' ? 'пример' : 'example').': '.$cmd['example'];
        }

        return $line;
    }
}

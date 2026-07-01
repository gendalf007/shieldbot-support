<?php

it('renders the help page in English', function () {
    $res = $this->get('/?lang=en');

    $res->assertOk();
    $res->assertSee('Player Search');
    $res->assertSee('Ask the bot');
    $res->assertSee('/i {name}');
    $res->assertSee('lang="en"', false);
});

it('renders the help page in Russian', function () {
    $res = $this->get('/?lang=ru');

    $res->assertOk();
    $res->assertSee('Поиск игроков');
    $res->assertSee('Спросить бота');
    $res->assertSee('/i {имя}');
    $res->assertSee('lang="ru"', false);
});

it('honours the locale stored in session', function () {
    $this->withSession(['locale' => 'ru'])
        ->get('/')
        ->assertSee('Поиск игроков');
});

it('detects Russian from Accept-Language on first visit', function () {
    $this->withHeaders(['Accept-Language' => 'ru-RU,ru;q=0.9,en;q=0.5'])
        ->get('/')
        ->assertSee('Поиск игроков');
});

it('defaults to English for other languages', function () {
    $this->withHeaders(['Accept-Language' => 'fr-FR,fr;q=0.9'])
        ->get('/')
        ->assertSee('Player Search');
});

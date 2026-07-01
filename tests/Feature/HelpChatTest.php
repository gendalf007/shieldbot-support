<?php

use App\Ai\Agents\HelpChatAgent;
use Laravel\Ai\Ai;

it('streams a grounded answer as SSE', function () {
    Ai::fakeAgent(HelpChatAgent::class, ['Используйте команду /i Player']);

    $response = $this->post('/help/chat', [
        'message' => 'как найти игрока?',
    ]);

    $response->assertOk();

    $content = $response->streamedContent();
    expect($content)->toContain('text_delta');
    expect($content)->toContain('[DONE]');
});

it('rejects an empty message', function () {
    $this->postJson('/help/chat', ['message' => ''])
        ->assertStatus(422);
});

it('accepts prior conversation history', function () {
    Ai::fakeAgent(HelpChatAgent::class, ['/ii Vasya']);

    $this->post('/help/chat', [
        'message' => 'а похожих?',
        'history' => [
            ['role' => 'user', 'content' => 'найди Vasya'],
            ['role' => 'assistant', 'content' => '/i Vasya'],
        ],
    ])->assertOk();
});

it('rejects malformed history entries', function () {
    $this->postJson('/help/chat', [
        'message' => 'привет',
        'history' => [
            ['role' => 'system', 'content' => 'oops'],
        ],
    ])->assertStatus(422);
});

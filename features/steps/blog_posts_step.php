<?php
use Behat\Behat\Context\Step\Given,
    Behat\Behat\Context\Step\When,
    Behat\Behat\Context\Step\Then;
App::uses('Fabricate', 'Fabricate.Lib');

$steps->Given('/^"([^"]*)" としてログインしている$/', function($world, $username) { // (1)
    $user = $world->getUser($username);
    return [
        new Given('"'.Router::url(['controller'=>'app_users','action'=>'login']).'" を表示している'),
        new When('"Email" フィールドに "'.$user['email'].'" と入力する'),
        new When('"Password" フィールドに "'.$user['password'].'" と入力する'),
        new When('"Submit" ボタンをクリックする'),
    ];
});

$steps->Given('/^"([^"]*)" の記事が (\d+) 件登録されている$/', function($world, $username, $num) {
    $user = $world->getUser($username);
    Fabricate::create('Post', $num, function($data, $world) use($user) {
        return [
            'title' => $world->sequence('title',function($i) { return "タイトル{$i}"; }),
            'author_id' => $user['id'],
        ];
    }); // (2)
});

$steps->Given('/^自分の投稿を一覧表示する$/', function($world) {
    return [
        new When('"'.Router::url(['controller'=>'posts', 'action'=>'index', 'user_account'=>'testuser']).'" を表示している'),
    ]; // (3)
});

$steps->Given('/^ページ (\d+) に投稿が新しい順で (\d+) 件 表示されている$/', function($world, $page, $count) {
    // (4)
    $active = $world->getSession()->getPage()->find('css', '.pagination .active a');
    if ($active && ($page != $active->getText())) {
        $world->getSession()->getPage()->find('css', '.pagination')->clickLink($page);
    }
    // (5)
    $world->assertSession()->elementsCount('css', 'article > section', $count);
    // (6)
    $titles = array_map(function($section) {
        return $section->find('css', 'h1')->getText();
    }, $world->getSession()->getPage()->findAll('css', 'article > section'));
    // (7)
    $expect = array_chunk(array_map(function($i) {
        return "タイトル{$i}";
    }, range($world->getModel('Post')->find('count'), 1)), 5)[$page - 1];

    assertEquals($expect, $titles);
});

<?php
App::uses('AppController', 'Controller');
/**
 * Posts Controller
 *
 */
class PostsController extends AppController {
    public $components = [
        'Paginator',
        'RequestHandler',
        'Session',
    ];

    public $paginate = [
        'limit' => 5,
        'order' => [
            'Post.created' => 'desc'
        ],
    ];

    public function index()
    {
        $this->Paginator->settings = $this->paginate;
        $this->set('posts', $this->Paginator->paginate());
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $this->Post->create($this->request->data);
            if ($this->Post->save()) {
                $this->Session->setFlash(__('新しい記事を受け付けました。'),
                    'alert', ['plugin' => 'BoostCake', 'class' => 'alert-success']);
                return $this->redirect(['action'=>'index']);
            } else {
                $this->Session->setFlash(__('記事の投稿に失敗しました。入力内容を確認して再度投稿してください。'),
                    'alert', ['plugin' => 'BoostCake', 'class' => 'alert-success']);
            }
        }
    }
}

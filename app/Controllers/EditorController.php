<?php

namespace App\Controllers;

class EditorController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(array $parameters = []): string
    {
        return $this->pageLoader->setPage('editor')->render(array_merge(['content' => '<p>default content<br><strong>bold</strong></p>'], $parameters));
    }

    public function editPost(): array
    {
        $data = json_decode(file_get_contents('php://input'), true) ?? [];

        $content = $data['content'] ?? '';

        return [
            'success' => true,
            'message' => 'Content saved successfully',
        ];
    }
}

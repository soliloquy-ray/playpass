<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class ArticleCardCell extends Cell
{
    public function render(array $data = []): string
    {
        $defaultData = [
            'image' => base_url('assets/images/placeholder.jpg'),
            'category' => 'Gaming',
            'title' => 'Article Title',
            'excerpt' => 'Brief description of the article...',
            'author' => 'Author Name',
            'date' => 'Dec 7, 2025',
            'readMoreUrl' => site_url('app/stories/1'),
        ];

        $data = array_merge($defaultData, $data);

        return view('cells/article_card', $data);
    }
}

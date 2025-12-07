<?php

namespace App\Cells;

class ArticleCell
{
    public function renderCard(array $article): string
    {
        // Logic to create a short excerpt
        $content = strip_tags($article['content']);
        $excerpt = strlen($content) > 100 ? substr($content, 0, 100) . '...' : $content;

        $data = [
            'article' => $article,
            'excerpt' => $excerpt,
            'date'    => date('M d, Y', strtotime($article['created_at'])),
        ];

        return view('App\Cells\article_card', $data);
    }
}
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
            'image'       => $article['image'] ?? base_url('assets/images/placeholder.jpg'),
            'category'    => $article['category'] ?? 'General',
            'title'       => $article['title'],
            'excerpt'     => $excerpt,
            'author'      => $article['author'] ?? 'Playpass',
            'date'        => date('M d, Y', strtotime($article['created_at'])),
            'readMoreUrl' => site_url('app/stories/' . ($article['slug'] ?? $article['id'])),
        ];

        return view('App\Cells\article_card', $data);
    }
}
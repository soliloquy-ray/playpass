<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StorySeeder extends Seeder
{
    public function run()
    {
        $stories = [
            // --- TRAILERS ---
            [
                'title'        => 'My Youth: Official Trailer Release',
                'slug'         => 'my-youth-trailer',
                'category'     => 'trailer',
                'image'        => 'https://placehold.co/600x338/1a1a1a/FFF?text=My+Youth',
                'excerpt'      => 'Catch the first look at the heartwarming new series coming this summer.',
                'content'      => 'Full content goes here...',
                'status'       => 'published',
                'published_at' => date('Y-m-d H:i:s', strtotime('-1 hour')),
            ],
            [
                'title'        => 'I Love You Since 1892: Official Trailer',
                'slug'         => 'love-you-since-1892',
                'category'     => 'trailer',
                'image'        => 'https://placehold.co/600x338/222/FFF?text=1892+Trailer',
                'excerpt'      => 'Travel back in time with this historical romance masterpiece.',
                'content'      => 'Full content goes here...',
                'status'       => 'published',
                'published_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
            ],
            [
                'title'        => 'Si Lak Bo: Official Trailer',
                'slug'         => 'si-lak-bo-trailer',
                'category'     => 'trailer',
                'image'        => 'https://placehold.co/600x338/991b1b/FFF?text=Si+Lak+Bo',
                'excerpt'      => 'Intense drama awaits in the upcoming blockbuster movie.',
                'content'      => 'Full content goes here...',
                'status'       => 'published',
                'published_at' => date('Y-m-d H:i:s', strtotime('-3 hours')),
            ],

            // --- PROMOS ---
            [
                'title'        => 'ONE-DAY DEAL: Get 50% Off!',
                'slug'         => 'one-day-deal-promo',
                'category'     => 'promo',
                'image'        => 'https://placehold.co/600x338/0044cc/FFF?text=One+Day+Deal',
                'excerpt'      => 'Don’t miss out on our biggest flash sale of the month. Today only!',
                'content'      => 'Full content goes here...',
                'status'       => 'published',
                'published_at' => date('Y-m-d H:i:s', strtotime('-4 hours')),
            ],
            [
                'title'        => 'Benta Fiesta: Sell Cards & Loads',
                'slug'         => 'benta-fiesta',
                'category'     => 'promo',
                'image'        => 'https://placehold.co/600x338/d8369f/FFF?text=Benta+Fiesta',
                'excerpt'      => 'Start your own business today with our reseller packages.',
                'content'      => 'Full content goes here...',
                'status'       => 'published',
                'published_at' => date('Y-m-d H:i:s', strtotime('-5 hours')),
            ],

            // --- STORIES / NEWS ---
            [
                'title'        => 'Queen of Divorce: Episode 1 Recap',
                'slug'         => 'queen-of-divorce-recap',
                'category'     => 'story',
                'image'        => 'https://placehold.co/600x338/333/FFF?text=Queen+Of+Divorce',
                'excerpt'      => 'A breakdown of the shocking events in the season premiere.',
                'content'      => 'Full content goes here...',
                'status'       => 'published',
                'published_at' => date('Y-m-d H:i:s', strtotime('-6 hours')),
            ],
            [
                'title'        => 'Light Shop Keeper: Behind the Scenes',
                'slug'         => 'light-shop-bts',
                'category'     => 'story',
                'image'        => 'https://placehold.co/600x338/444/FFF?text=BTS+Light+Shop',
                'excerpt'      => 'See how the magic was made in this exclusive featurette.',
                'content'      => 'Full content goes here...',
                'status'       => 'published',
                'published_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],

            // --- EVENTS ---
            [
                'title'        => 'Vivarkada: The Ultimate Fancon',
                'slug'         => 'vivarkada-fancon',
                'category'     => 'event',
                'image'        => 'https://placehold.co/600x338/1e1b4b/FFF?text=Vivarkada',
                'excerpt'      => 'Join us for a night of music, games, and meet-and-greets!',
                'content'      => 'Full content goes here...',
                'status'       => 'published',
                'published_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            
            // --- MORE FILLER FOR SCROLLING ---
            [
                'title'        => 'Summer Gaming Festival',
                'slug'         => 'summer-gaming-fest',
                'category'     => 'event',
                'image'        => 'https://placehold.co/600x338/064e3b/FFF?text=Gaming+Fest',
                'excerpt'      => 'Compete with the best players in the region.',
                'content'      => 'Full content...',
                'status'       => 'published',
                'published_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],
            [
                'title'        => 'New Payment Channels Added',
                'slug'         => 'new-payment-channels',
                'category'     => 'story',
                'image'        => 'https://placehold.co/600x338/555/FFF?text=Payment+Update',
                'excerpt'      => 'We now accept crypto and direct bank transfers.',
                'content'      => 'Full content...',
                'status'       => 'published',
                'published_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
            ],
        ];

        // Using author_id = 1 (Assuming an admin user exists, or field allows null/default)
        foreach ($stories as &$story) {
            $story['created_at'] = date('Y-m-d H:i:s');
        }

        $this->db->table('stories')->insertBatch($stories);
    }
}
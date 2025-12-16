<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Models\Certificate;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = collect();

        // Static pages
        $staticPages = [
            ['url' => '/', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => '/verifikasi', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['url' => '/blockchain/verify', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['url' => '/harga', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['url' => '/bantuan', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => '/kontak', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => '/tentang', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['url' => '/privasi', 'priority' => '0.5', 'changefreq' => 'yearly'],
            ['url' => '/syarat', 'priority' => '0.5', 'changefreq' => 'yearly'],
            ['url' => '/status', 'priority' => '0.6', 'changefreq' => 'hourly'],
        ];

        foreach ($staticPages as $page) {
            $urls->push([
                'loc' => config('app.url') . $page['url'],
                'lastmod' => now()->toIso8601String(),
                'changefreq' => $page['changefreq'],
                'priority' => $page['priority'],
            ]);
        }

        // Generate XML
        $content = view('sitemap.index', compact('urls'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
}

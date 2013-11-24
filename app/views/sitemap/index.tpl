<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
@foreach ($urls as $url)
    <url>
        <loc>{{$url->url}}</loc>
        @if ($url->lastmod)
            <lastmod>{{$url->lastmod}}</lastmod>
        @end
        @if ($url->changefreq)
            <changefreq>{{$url->changefreq}}</changefreq>
        @end
        <priority>0.8</priority>
    </url>
@end
</urlset>

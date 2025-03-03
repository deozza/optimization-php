# Web Performance Optimization for Guitare Boissières

## Introduction

Web performance is super important for websites these days. Users hate waiting. For example 53% of people will bounce if a site takes more than 3 seconds to load. For an e-commerce site like Guitare Boissières, slow performance means losing customers and money. Plus, Google ranks faster sites higher, so being slow is a double fail.

- Users get annoyed with slow sites
- More speed = more money
- Better SEO rankings = more traffic

## Hypothesis

After digging through the code, I found several issues that are probably killing performance:

- N+1 Query Problem: The controller is doing nested database queries for each carousel item.
- Unoptimized Images: The image folder is nearly 1GB. These are not web-optimized.
- Zero Caching: No caching implemented anywhere, so the server is working hard on every single page load.
- No Pagination: Everything loads at once instead of loading just what's needed.
- CSS/JS Not Optimized: No minification or proper asset management.
- seo: no meta tags, no alt tags, no structured data

## Tests and Measurements
I ran some tests to confirm these problems:

### Tools I Used
- Lighthouse in Chrome DevTools
- Network Panel to check request waterfall
- Symfony Profiler to see what SQL queries are happening

### What I Found
**Lighthouse Performance**
- Score: a terrible 38/100
- LCP: 12.2s (should be under 2.5s)
- Total Blocking Time: 2,500ms (should be under 200ms)

**Network Analysis**

- Page takes 20s to load completely
- Downloads 738 MB of data (that's huge for one page)
- Makes 147 requests, the large majority are just for images

**Database Issues**
- 164 SQL queries for a single page! That's way to many queries !!!!
- Querie taking 25.ms total
- Same queries being repeated for each carousel item

**Image Problems**

- Images averaging more than 1 MO each
- Using old-school JPGs without optimization
- Images are way bigger than they need to be for display

## Solutions

1. Fix the Database Queries
   Problem: The controller is doing the N+1 query anti-pattern.

   How I'll Fix It:
   - Optimize the controller to use batch loading instead of nested queries
   - Fetch all required entities in separate but minimized queries
   - Use memory-based collections to associate related entities
   - Reduce the number of database round-trips to just 4

2. Sort Out Those Images
   Problem: Massive unoptimized images.
   
    How I'll Fix It:
    - Resize all images to the actual display size
    - Convert everything to WebP (way smaller file size)
    - Add lazy loading for images below the fold
    - Add width/height attributes to prevent layout shifts

3. Implement Caching
   Problem: No caching at all.
   
    How I'll Fix It:
    - Add caching for database queries
    - Use HTTP caching for static assets
    - Use Symfony's cache system for database results

4. Add Pagination
    Problem: Loading everything at once.
    
     How I'll Fix It:
     - Add pagination to the carousel


After My Fixes
After implementing all these changes, the metrics improved dramatically:
![after-fix](assets/Capture%20d’écran%202025-03-03%20à%2016.43.24.png)
